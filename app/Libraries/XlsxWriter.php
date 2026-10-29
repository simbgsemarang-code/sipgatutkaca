<?php

namespace App\Libraries;

/**
 * Pembuat berkas Excel (.xlsx) sederhana tanpa pustaka tambahan (hanya ext-zip).
 * Satu lembar kerja: baris judul berwarna + beku + autofilter, kolom berlebar tetap,
 * sel teks (inline string) dan angka/tanggal asli.
 *
 * Kolom: array of ['judul' => string, 'lebar' => int, 'tipe' => 'teks'|'angka'|'tanggal'].
 * Baris: array of array nilai (urutan sesuai kolom). Tanggal = string 'Y-m-d H:i:s' atau kosong.
 */
class XlsxWriter
{
    /** @return string isi berkas .xlsx (biner) dengan satu lembar kerja */
    public static function buat(string $namaLembar, array $kolom, array $baris): string
    {
        return self::buatBanyak([[nama => $namaLembar, kolom => $kolom, baris => $baris]]);
    }

    /**
     * Beberapa lembar kerja sekaligus.
     * @param list<array{nama:string,kolom:array,baris:array}> $lembar
     * @return string isi berkas .xlsx (biner)
     */
    public static function buatBanyak(array $lembar): string
    {
        $tmp = tempnam(sys_get_temp_dir(), "xlsx");
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);

        $tipe = "";
        $sheets = "";
        $rels = "";
        $namaTerpakai = [];
        foreach ($lembar as $i => $l) {
            $n = $i + 1;
            $nama = self::namaLembar($l["nama"]);
            $dasar = $nama;
            $k = 2;
            while (in_array(mb_strtolower($nama), $namaTerpakai, true)) {
                $nama = mb_substr($dasar, 0, 28) . " " . $k++;
            }
            $namaTerpakai[] = mb_strtolower($nama);
            $tipe .= "<Override PartName=\"/xl/worksheets/sheet$n.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>";
            $sheets .= "<sheet name=\"" . self::esc($nama) . "\" sheetId=\"$n\" r:id=\"rId$n\"/>";
            $rels .= "<Relationship Id=\"rId$n\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet\" Target=\"worksheets/sheet$n.xml\"/>";
            $zip->addFromString("xl/worksheets/sheet$n.xml", self::lembar($l["kolom"], $l["baris"]));
        }
        $idGaya = count($lembar) + 1;

        $zip->addFromString("[Content_Types].xml",
            "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
            . "<Types xmlns=\"http://schemas.openxmlformats.org/package/2006/content-types\">"
            . "<Default Extension=\"rels\" ContentType=\"application/vnd.openxmlformats-package.relationships+xml\"/>"
            . "<Default Extension=\"xml\" ContentType=\"application/xml\"/>"
            . "<Override PartName=\"/xl/workbook.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml\"/>"
            . $tipe
            . "<Override PartName=\"/xl/styles.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml\"/>"
            . "</Types>");
        $zip->addFromString("_rels/.rels",
            "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
            . "<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">"
            . "<Relationship Id=\"rId1\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument\" Target=\"xl/workbook.xml\"/>"
            . "</Relationships>");
        $zip->addFromString("xl/workbook.xml",
            "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
            . "<workbook xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" xmlns:r=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships\">"
            . "<sheets>$sheets</sheets></workbook>");
        $zip->addFromString("xl/_rels/workbook.xml.rels",
            "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>"
            . "<Relationships xmlns=\"http://schemas.openxmlformats.org/package/2006/relationships\">"
            . $rels
            . "<Relationship Id=\"rId$idGaya\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles\" Target=\"styles.xml\"/>"
            . "</Relationships>");
        $zip->addFromString("xl/styles.xml", self::gaya());
        $zip->close();

        $isi = (string) file_get_contents($tmp);
        @unlink($tmp);

        return $isi;
    }

    /** Gaya sel: 0 normal, 1 judul, 2 teks (bungkus, rata atas), 3 angka 2 desimal, 4 tanggal-jam, 5 bilangan bulat. */
    private static function gaya(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="dd/mm/yyyy\ hh:mm"/></numFmts>'
            . '<fonts count="2"><font><sz val="10"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font></fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF2E7D32"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFC9D3D9"/></left><right style="thin"><color rgb="FFC9D3D9"/></right>'
            . '<top style="thin"><color rgb="FFC9D3D9"/></top><bottom style="thin"><color rgb="FFC9D3D9"/></bottom><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="6">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'
            . '<xf numFmtId="4" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment vertical="top"/></xf>'
            . '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment vertical="top"/></xf>'
            . '<xf numFmtId="1" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment vertical="top"/></xf>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private static function lembar(array $kolom, array $baris): string
    {
        $jmlKolom = count($kolom);
        $akhirKol = self::huruf($jmlKolom);
        $jmlBaris = count($baris) + 1;

        $x  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $x .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $x .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
        $x .= '<sheetFormatPr defaultRowHeight="15"/>';
        $x .= '<cols>';
        foreach ($kolom as $i => $k) {
            $x .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . (int) ($k['lebar'] ?? 16) . '" customWidth="1"/>';
        }
        $x .= '</cols><sheetData>';

        // baris judul
        $x .= '<row r="1" ht="32" customHeight="1">';
        foreach ($kolom as $i => $k) {
            $x .= '<c r="' . self::huruf($i + 1) . '1" s="1" t="inlineStr"><is><t xml:space="preserve">' . self::esc($k['judul']) . '</t></is></c>';
        }
        $x .= '</row>';

        $r = 2;
        foreach ($baris as $baris1) {
            $x .= '<row r="' . $r . '">';
            foreach ($kolom as $i => $k) {
                $ref  = self::huruf($i + 1) . $r;
                $nilai = $baris1[$i] ?? null;
                $tipe = $k['tipe'] ?? 'teks';
                if ($nilai === null || $nilai === '') {
                    $x .= '<c r="' . $ref . '" s="2"/>';
                } elseif ($tipe === 'angka' && is_numeric($nilai)) {
                    $x .= '<c r="' . $ref . '" s="3"><v>' . (0 + $nilai) . '</v></c>';
                } elseif ($tipe === 'bulat' && is_numeric($nilai)) {
                    $x .= '<c r="' . $ref . '" s="5"><v>' . (int) $nilai . '</v></c>';
                } elseif ($tipe === 'tanggal' && ($ts = strtotime((string) $nilai)) !== false) {
                    // serial Excel: hari sejak 1899-12-30 (zona waktu server dianggap lokal)
                    $serial = ($ts + date('Z', $ts)) / 86400 + 25569;
                    $x .= '<c r="' . $ref . '" s="4"><v>' . round($serial, 9) . '</v></c>';
                } else {
                    $x .= '<c r="' . $ref . '" s="2" t="inlineStr"><is><t xml:space="preserve">' . self::esc((string) $nilai) . '</t></is></c>';
                }
            }
            $x .= '</row>';
            $r++;
        }
        $x .= '</sheetData>';
        $x .= '<autoFilter ref="A1:' . $akhirKol . $jmlBaris . '"/>';
        $x .= '<pageMargins left="0.5" right="0.5" top="0.6" bottom="0.6" header="0.3" footer="0.3"/>';
        $x .= '<pageSetup orientation="landscape" paperSize="9" fitToHeight="0"/>';
        $x .= '</worksheet>';

        return $x;
    }

    /** 1 => A, 27 => AA */
    private static function huruf(int $n): string
    {
        $s = '';
        while ($n > 0) {
            $m = ($n - 1) % 26;
            $s = chr(65 + $m) . $s;
            $n = intdiv($n - $m - 1, 26);
        }

        return $s;
    }

    private static function namaLembar(string $nama): string
    {
        $nama = preg_replace('/[\\\\\/\?\*\[\]:]/', ' ', $nama);

        return mb_substr(trim($nama) !== '' ? trim($nama) : 'Data', 0, 31);
    }

    /** Escape XML + buang karakter kontrol yang tidak sah di XML 1.0. */
    private static function esc(string $s): string
    {
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $s);

        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
