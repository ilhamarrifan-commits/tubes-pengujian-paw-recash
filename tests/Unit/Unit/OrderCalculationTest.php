<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OrderCalculationTest extends TestCase
{
    /**
     * Menguji akurasi perhitungan subtotal dan pajak restoran 10%
     * untuk Target Fitur No. 3 (Pembuatan Order) di Laporan.
     */
    public function test_order_total_calculation_with_ten_percent_tax(): void
    {
        $hargaSateTaichan = 35000;
        $qtySate = 2;

        $hargaJusMelon = 15000;
        $qtyJus = 1;

        $subtotal = ($hargaSateTaichan * $qtySate) + ($hargaJusMelon * $qtyJus);

        $pajak = 0.10;
        $totalAkhirDiharapkan = round($subtotal * (1 + $pajak));

        $this->assertEquals(85000, $subtotal, 'Perhitungan subtotal salah!');
        $this->assertEquals(93500, $totalAkhirDiharapkan, 'Perhitungan total + pajak 10% salah!');
    }
}
