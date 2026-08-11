<?php
/**
 * Endpoint webhook GitHub untuk auto-deploy.
 *
 * Alur: push ke GitHub -> GitHub kirim POST ke file ini -> tanda
 * tangan HMAC diverifikasi -> `git fetch` + `git reset --hard` di
 * folder ini (document root) -> kode di server langsung sinkron
 * dengan branch yang di-deploy.
 *
 * PENTING: begitu webhook ini aktif, JANGAN lagi edit file langsung
 * di cPanel File Manager -- `git reset --hard` pada deploy berikutnya
 * akan MENIMPA perubahan manual apa pun yang tidak berasal dari git.
 * Selalu ubah kode lewat git (push ke GitHub), biarkan webhook yang
 * menyalinkan ke server.
 *
 * SETUP lengkap ada di DEPLOY.md bagian "10. Auto-deploy via webhook".
 * Ringkas:
 * 1. Salin deploy-hook.config.php.example -> deploy-hook.config.php,
 *    isi 'secret' dengan string acak panjang (bukan file ini yang
 *    di-commit ke repo publik -- sudah di-gitignore).
 * 2. GitHub repo -> Settings -> Webhooks -> Add webhook:
 *      Payload URL : https://domain-anda/deploy-hook.php
 *      Content type: application/json
 *      Secret      : (SECRET yang sama seperti langkah 1)
 *      Events      : Just the push event
 * 3. Pastikan folder ini adalah git working copy hasil clone (lewat
 *    cPanel Git Version Control), pada branch yang benar.
 *
 * Sebelum langkah 2, cek dulu apakah server ini bisa menjalankan
 * perintah shell lewat mode diagnostik (GET, tidak memicu deploy):
 *   https://domain-anda/deploy-hook.php?diag=SECRET_ANDA
 *
 * CATATAN KOMPATIBILITAS: banyak shared hosting mematikan exec() demi
 * keamanan. Script ini coba beberapa fungsi alternatif secara
 * berurutan (exec, shell_exec, system, passthru, proc_open) lewat
 * deploy_hook_shell() -- kalau semuanya dimatikan, deploy otomatis
 * lewat file ini memang tidak bisa jalan di hosting tsb, perlu jalur
 * lain (lihat DEPLOY.md #10h).
 */

// --- 0. Muat konfigurasi rahasia (tidak ikut ter-commit) -------------------
$config_file = __DIR__ . '/deploy-hook.config.php';
if (! is_file($config_file))
{
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	exit("Konfigurasi belum dibuat.\nSalin deploy-hook.config.php.example -> deploy-hook.config.php lalu isi secret-nya. Lihat DEPLOY.md #10.");
}

$config = require $config_file;
$secret = isset($config['secret']) ? (string) $config['secret'] : '';
$branch = isset($config['branch']) ? (string) $config['branch'] : 'main';

if ($secret === '' || strlen($secret) < 20)
{
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	exit('Secret di deploy-hook.config.php belum diisi / terlalu pendek (minimal 20 karakter acak).');
}

// Validasi nama branch dengan allowlist karakter ketat -- dipakai
// langsung dalam perintah shell di bawah, jadi jangan longgar.
if (! preg_match('/^[A-Za-z0-9._\/-]+$/', $branch))
{
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	exit('Nama branch pada konfigurasi mengandung karakter yang tidak diizinkan.');
}

/**
 * Coba jalankan perintah shell lewat fungsi apa pun yang tersedia dan
 * tidak diblokir disable_functions, dicoba berurutan sesuai preferensi.
 *
 * @return array{0: string|null, 1: string, 2: int|null} [nama fungsi
 *         yang dipakai (NULL kalau tidak ada satu pun tersedia),
 *         gabungan stdout+stderr, exit code (NULL kalau fungsinya
 *         tidak bisa melaporkan exit code, mis. shell_exec)]
 */
function deploy_hook_shell($cmd)
{
	static $disabled = null;
	if ($disabled === null)
	{
		$disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
	}
	$available = function ($fn) use ($disabled) {
		return function_exists($fn) && ! in_array($fn, $disabled, true);
	};

	if ($available('exec'))
	{
		$out = array();
		$code = null;
		exec($cmd, $out, $code);
		return array('exec', implode("\n", $out), $code);
	}

	if ($available('shell_exec'))
	{
		$out = shell_exec($cmd);
		return array('shell_exec', $out === null ? '(shell_exec mengembalikan NULL)' : $out, null);
	}

	if ($available('system'))
	{
		ob_start();
		$last_line = system($cmd, $code);
		$captured = ob_get_clean();
		return array('system', $captured !== '' ? $captured : (string) $last_line, $code);
	}

	if ($available('passthru'))
	{
		ob_start();
		passthru($cmd, $code);
		$captured = ob_get_clean();
		return array('passthru', $captured, $code);
	}

	if ($available('proc_open'))
	{
		$descriptors = array(1 => array('pipe', 'w'), 2 => array('pipe', 'w'));
		$proc = @proc_open($cmd, $descriptors, $pipes);
		if (is_resource($proc))
		{
			$stdout = stream_get_contents($pipes[1]);
			$stderr = stream_get_contents($pipes[2]);
			fclose($pipes[1]);
			fclose($pipes[2]);
			$code = proc_close($proc);
			return array('proc_open', $stdout . $stderr, $code);
		}
	}

	return array(null, '', null);
}

// --- 1. Mode diagnostik (GET) -- cek kesiapan server tanpa deploy ---------
if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
	$diag = isset($_GET['diag']) ? (string) $_GET['diag'] : '';
	if ($diag === '' || ! hash_equals($secret, $diag))
	{
		http_response_code(404);
		exit; // sembunyikan keberadaan endpoint ini dari yang tidak tahu secret
	}

	header('Content-Type: text/plain; charset=UTF-8');
	echo "=== Diagnostik deploy-hook.php ===\n\n";
	echo 'PHP version    : ' . PHP_VERSION . "\n\n";

	echo "Fungsi shell yang tersedia (tidak diblokir disable_functions):\n";
	foreach (array('exec', 'shell_exec', 'system', 'passthru', 'proc_open', 'popen') as $fn)
	{
		$disabled_list = array_map('trim', explode(',', (string) ini_get('disable_functions')));
		$ok = function_exists($fn) && ! in_array($fn, $disabled_list, true);
		echo '  - ' . str_pad($fn, 12) . ': ' . ($ok ? 'YA' : 'tidak') . "\n";
	}

	list($used_fn, , ) = deploy_hook_shell('echo ok');
	echo "\nFungsi yang akan dipakai untuk deploy: " . ($used_fn !== null ? $used_fn : 'TIDAK ADA SATU PUN TERSEDIA') . "\n";

	if ($used_fn === null)
	{
		echo "\n=> Semua fungsi eksekusi shell dimatikan di hosting ini. Deploy\n";
		echo "   otomatis lewat file PHP ini TIDAK BISA jalan di server ini.\n";
		echo "   Lihat DEPLOY.md bagian 10h untuk rencana cadangan.\n";
	}
	else
	{
		list(, $out, $code) = deploy_hook_shell('git --version 2>&1');
		echo 'git binary     : ' . (trim($out) !== '' ? trim($out) : '(kosong)') . ($code !== null ? " (exit {$code})" : '') . "\n";

		list(, $out2, $code2) = deploy_hook_shell('cd ' . escapeshellarg(__DIR__) . ' && git rev-parse --is-inside-work-tree 2>&1');
		$is_repo = trim($out2) === 'true';
		echo 'folder ini git working copy: ' . ($is_repo ? 'YA' : 'TIDAK (output: ' . trim($out2) . ')') . "\n";

		if ($is_repo)
		{
			list(, $out3, ) = deploy_hook_shell('cd ' . escapeshellarg(__DIR__) . ' && git branch --show-current 2>&1');
			echo 'branch saat ini: ' . trim($out3) . " (target deploy: {$branch})\n";

			list(, $out4, ) = deploy_hook_shell('cd ' . escapeshellarg(__DIR__) . ' && git log -1 --oneline 2>&1');
			echo 'commit terakhir: ' . trim($out4) . "\n";

			list(, $out5, $code5) = deploy_hook_shell('cd ' . escapeshellarg(__DIR__) . ' && git fetch origin ' . escapeshellarg($branch) . ' 2>&1');
			echo 'tes fetch dari origin: ' . ($code5 === 0 ? 'BERHASIL' : ($code5 === null ? 'TIDAK DIKETAHUI (fungsi ini tidak melaporkan exit code)' : 'GAGAL (exit ' . $code5 . ')')) . "\n";
			echo "  " . str_replace("\n", "\n  ", trim($out5)) . "\n";
		}
	}

	echo "\nFolder writable: " . (is_writable(__DIR__) ? 'YA' : 'TIDAK') . "\n";
	exit;
}

// --- 2. Selain GET diagnostik, hanya terima POST --------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	http_response_code(405);
	exit('Method not allowed');
}

// --- 3. Ambil payload mentah + header tanda tangan -------------------------
$payload    = file_get_contents('php://input');
$sig_header = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';

if ($payload === '' || $sig_header === '')
{
	http_response_code(400);
	exit('Payload atau signature kosong.');
}

// --- 4. Verifikasi HMAC SHA-256 (satu-satunya pertahanan endpoint ini) ----
$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (! hash_equals($expected, $sig_header))
{
	http_response_code(403);
	deploy_hook_log('DITOLAK: signature tidak cocok.');
	exit('Signature tidak valid.');
}

// --- 5. Hanya proses event "push"; balas ping supaya test GitHub hijau ---
$event = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : '';
if ($event === 'ping')
{
	http_response_code(200);
	exit('pong');
}
if ($event !== 'push')
{
	http_response_code(202);
	exit('Event "' . $event . '" diabaikan (bukan push).');
}

// --- 6. Hanya proses push ke branch yang memang di-deploy -----------------
$data         = json_decode($payload, true);
$ref          = isset($data['ref']) ? (string) $data['ref'] : '';
$expected_ref = 'refs/heads/' . $branch;

if ($ref !== $expected_ref)
{
	http_response_code(200);
	exit('Push ke "' . $ref . '" diabaikan, bukan branch deploy ("' . $expected_ref . '").');
}

// --- 7. Cegah dua proses deploy berjalan bersamaan (lock file) ------------
$lock_file = __DIR__ . '/deploy-hook.lock';
$lock = fopen($lock_file, 'c');
if ($lock === FALSE || ! flock($lock, LOCK_EX | LOCK_NB))
{
	http_response_code(429);
	exit('Deploy lain sedang berjalan, coba lagi sebentar.');
}

// --- 8. Jalankan git fetch + reset --hard (BUKAN git pull) ----------------
// git reset --hard menimpa file yang DILACAK git supaya selalu sama persis
// dengan commit terbaru di GitHub -- tapi tidak menyentuh file yang
// sengaja tidak dilacak (application/config/database.php,
// deploy-hook.config.php, dll ada di .gitignore, jadi aman).
$dir_esc = escapeshellarg(__DIR__);
$cmd = "cd {$dir_esc} && git fetch origin {$branch} 2>&1 && git reset --hard origin/{$branch} 2>&1";

list($used_fn, $output_text, $exit_code) = deploy_hook_shell($cmd);

flock($lock, LOCK_UN);
fclose($lock);

if ($used_fn === null)
{
	http_response_code(500);
	deploy_hook_log('GAGAL: tidak ada fungsi eksekusi shell yang tersedia di server ini (semua diblokir disable_functions).');
	exit('Tidak ada fungsi eksekusi shell yang tersedia di hosting ini - lihat DEPLOY.md #10h rencana cadangan.');
}

// exit code NULL berarti fungsi yang dipakai (mis. shell_exec) tidak
// melaporkan exit code -- anggap sukses selama tidak ada indikasi lain,
// tapi catat di log supaya tetap bisa diperiksa manual.
$success = ($exit_code === 0 || $exit_code === null);

deploy_hook_log(($success ? 'SUKSES' : 'GAGAL (exit ' . $exit_code . ')') . " [via {$used_fn}]:\n" . $output_text);

http_response_code($success ? 200 : 500);
echo $success ? 'Deploy sukses.' : 'Deploy gagal, cek deploy-hook.log di server.';

// --- Fungsi bantu: catat log lokal (output git TIDAK dikirim balik ke
// pemanggil webhook, supaya tidak bocor lewat response HTTP) ---------------
function deploy_hook_log($message)
{
	$line = '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n" . str_repeat('-', 60) . "\n";
	@file_put_contents(__DIR__ . '/deploy-hook.log', $line, FILE_APPEND | LOCK_EX);
}
