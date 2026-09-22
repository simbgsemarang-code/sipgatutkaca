<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Klien Google Drive API v3 minimal (REST + cURL, tanpa Composer -
 * proyek ini CodeIgniter 3 murni tanpa vendor). Dipakai supaya berkas
 * yang diunggah PU/TPA/pemohon/admin tidak bergantung pada disk
 * server, jadi aman kalau nanti domain/hosting berpindah.
 *
 * Kredensial & folder tujuan sepenuhnya dari application/config/gdrive.php
 * - ganti akun Google Drive kapan pun cukup ganti file JSON + folder ID
 *   di config itu, tanpa menyentuh kode di sini atau di controller manapun.
 */
class Gdrive
{
	private $enabled;
	private $folder_id;
	private $creds;
	private $token;

	public function __construct()
	{
		$ci =& get_instance();
		$ci->config->load('gdrive', TRUE);

		$this->enabled   = (bool) $ci->config->item('gdrive_enabled', 'gdrive');
		$this->folder_id = (string) $ci->config->item('gdrive_folder_id', 'gdrive');
		$path            = (string) $ci->config->item('gdrive_credentials_path', 'gdrive');

		if ($this->enabled && $this->folder_id !== '' && is_readable($path))
		{
			$json = json_decode(file_get_contents($path), TRUE);
			if (is_array($json) && ! empty($json['client_email']) && ! empty($json['private_key']))
			{
				$this->creds = $json;
			}
			else
			{
				$this->enabled = FALSE;
				log_message('error', 'Gdrive: file kredensial tidak valid di ' . $path);
			}
		}
		else
		{
			$this->enabled = FALSE;
		}
	}

	/** Aktif hanya jika enabled=TRUE, folder_id terisi, dan file kredensial valid. */
	public function aktif()
	{
		return $this->enabled;
	}

	/**
	 * Unggah satu berkas lokal ke folder Drive yang dikonfigurasi, lalu
	 * jadikan bisa dilihat siapa pun yang punya tautannya (viewer).
	 * Return array('id'=>..., 'url'=>...) kalau berhasil, atau NULL
	 * kalau gagal (caller sebaiknya fallback ke penyimpanan lokal).
	 */
	public function upload($path_lokal, $nama_file, $mime)
	{
		if (! $this->enabled || ! is_readable($path_lokal)) return NULL;

		try
		{
			$token = $this->access_token();
			if (! $token) return NULL;

			$file_id = $this->unggah_multipart($token, $path_lokal, $nama_file, $mime);
			if (! $file_id) return NULL;

			$this->buat_izin_publik($token, $file_id);

			return array('id' => $file_id, 'url' => 'https://drive.google.com/file/d/' . $file_id . '/view');
		}
		catch (Exception $e)
		{
			log_message('error', 'Gdrive upload gagal: ' . $e->getMessage());
			return NULL;
		}
	}

	public function hapus($file_id)
	{
		if (! $this->enabled || ! $file_id) return FALSE;
		$token = $this->access_token();
		if (! $token) return FALSE;
		$this->kirim_permintaan('DELETE', 'https://www.googleapis.com/drive/v3/files/' . rawurlencode($file_id), $token);
		return TRUE;
	}

	private function access_token()
	{
		if ($this->token && $this->token['exp'] > time() + 30) return $this->token['nilai'];

		$now   = time();
		$head  = $this->base64url(json_encode(array('alg' => 'RS256', 'typ' => 'JWT')));
		$claim = $this->base64url(json_encode(array(
			'iss'   => $this->creds['client_email'],
			'scope' => 'https://www.googleapis.com/auth/drive.file',
			'aud'   => 'https://oauth2.googleapis.com/token',
			'iat'   => $now,
			'exp'   => $now + 3600,
		)));
		$unsigned = $head . '.' . $claim;

		$signature = '';
		$ok = openssl_sign($unsigned, $signature, $this->creds['private_key'], 'sha256WithRSAEncryption');
		if (! $ok) { log_message('error', 'Gdrive: gagal menandatangani JWT.'); return NULL; }

		$jwt = $unsigned . '.' . $this->base64url($signature);

		$resp = $this->kirim_permintaan('POST', 'https://oauth2.googleapis.com/token', NULL, array(
			'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
			'assertion'  => $jwt,
		), 'form');

		$data = json_decode($resp, TRUE);
		if (empty($data['access_token']))
		{
			log_message('error', 'Gdrive: gagal ambil access token - ' . $resp);
			return NULL;
		}

		$this->token = array('nilai' => $data['access_token'], 'exp' => $now + (int) ($data['expires_in'] ?? 3000));
		return $this->token['nilai'];
	}

	private function unggah_multipart($token, $path_lokal, $nama_file, $mime)
	{
		$boundary  = 'gdrive-' . bin2hex(random_bytes(12));
		$metadata  = json_encode(array('name' => $nama_file, 'parents' => array($this->folder_id)));
		$isi_file  = file_get_contents($path_lokal);

		$body  = "--{$boundary}\r\n";
		$body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
		$body .= $metadata . "\r\n";
		$body .= "--{$boundary}\r\n";
		$body .= "Content-Type: {$mime}\r\n\r\n";
		$body .= $isi_file . "\r\n";
		$body .= "--{$boundary}--";

		$resp = $this->kirim_permintaan(
			'POST',
			'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&fields=id',
			$token,
			$body,
			'raw',
			array('Content-Type: multipart/related; boundary=' . $boundary)
		);

		$data = json_decode($resp, TRUE);
		if (empty($data['id']))
		{
			log_message('error', 'Gdrive: gagal unggah berkas - ' . $resp);
			return NULL;
		}
		return $data['id'];
	}

	private function buat_izin_publik($token, $file_id)
	{
		$this->kirim_permintaan(
			'POST',
			'https://www.googleapis.com/drive/v3/files/' . rawurlencode($file_id) . '/permissions',
			$token,
			json_encode(array('role' => 'reader', 'type' => 'anyone')),
			'json'
		);
	}

	private function kirim_permintaan($method, $url, $token = NULL, $body = NULL, $mode = 'json', $extra_headers = array())
	{
		$ch = curl_init($url);
		$headers = $extra_headers;
		if ($token) $headers[] = 'Authorization: Bearer ' . $token;

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		if ($body !== NULL)
		{
			if ($mode === 'json') { $headers[] = 'Content-Type: application/json'; curl_setopt($ch, CURLOPT_POSTFIELDS, $body); }
			elseif ($mode === 'form') { curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body)); }
			else { curl_setopt($ch, CURLOPT_POSTFIELDS, $body); }
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$resp = curl_exec($ch);
		if ($resp === FALSE)
		{
			$err = curl_error($ch);
			curl_close($ch);
			throw new Exception('cURL error: ' . $err);
		}
		curl_close($ch);
		return $resp;
	}

	private function base64url($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}
