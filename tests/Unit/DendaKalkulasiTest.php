<?php

use PHPUnit\Framework\TestCase;

/**
 * Test kalkulasi denda keterlambatan.
 * Test ini murni unit test — tidak membutuhkan koneksi database sama sekali.
 */
class DendaKalkulasiTest extends TestCase {

    /**
     * Metode hitungDenda diekstrak agar dapat diuji secara terisolasi.
     * Logika: Rp 2.000 per hari keterlambatan.
     */
    private function hitungDenda(string $tglJatuhTempo, string $tglKembali): float {
        $dendaPerHari = 2000;
        $jatuhTempo   = new DateTime($tglJatuhTempo);
        $kembali      = new DateTime($tglKembali);
        $terlambat    = $kembali > $jatuhTempo;
        if (!$terlambat) {
            return 0;
        }
        $selisih = $jatuhTempo->diff($kembali)->days;
        return $selisih * $dendaPerHari;
    }

    public function testDendaNolJikaTepatWaktu(): void {
        $this->assertEquals(0, $this->hitungDenda('2026-08-08', '2026-08-08'));
    }

    public function testDendaNolJikaKembalikanLebihAwal(): void {
        $this->assertEquals(0, $this->hitungDenda('2026-08-08', '2026-08-05'));
    }

    public function testDendaSatuHari(): void {
        $this->assertEquals(2000, $this->hitungDenda('2026-08-08', '2026-08-09'));
    }

    public function testDendaLimaBelasHari(): void {
        $this->assertEquals(30000, $this->hitungDenda('2026-08-01', '2026-08-16'));
    }

    public function testDendaSebulan(): void {
        // Agustus memiliki 31 hari, sehingga 1 Agustus ke 1 September = 31 hari keterlambatan
        $this->assertEquals(62000, $this->hitungDenda('2026-08-01', '2026-09-01'));
    }

    public function testDendaSelangSehari(): void {
        $this->assertEquals(4000, $this->hitungDenda('2026-01-15', '2026-01-17'));
    }
}
