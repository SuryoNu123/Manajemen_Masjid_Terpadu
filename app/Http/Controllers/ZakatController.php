<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateZakatRequest;
use Illuminate\View\View;

class ZakatController extends Controller
{
    /**
     * Halaman kalkulator zakat.
     */
    public function index(): View
    {
        return view('zakat.index');
    }

    /**
     * Melakukan perhitungan zakat.
     *
     * Parameter dibuat configurable karena
     * SRS belum menetapkan formula/angka baku.
     */
    public function calculate(
        CalculateZakatRequest $request
    ): View {
        $data = $request->validated();

        $result = 0;
        $netAmount = 0;
        $meetsNisab = null;

        /*
        |--------------------------------------------------------------------------
        | ZAKAT FITRAH
        |--------------------------------------------------------------------------
        */

        if ($data['zakat_type'] === 'fitrah') {

            $jumlahOrang =
                (int) ($data['jumlah_orang'] ?? 0);

            $nilaiPerOrang =
                (float) ($data['nilai_per_orang'] ?? 0);

            $result =
                $jumlahOrang * $nilaiPerOrang;
        }


        /*
        |--------------------------------------------------------------------------
        | ZAKAT MAAL
        |--------------------------------------------------------------------------
        */

        if ($data['zakat_type'] === 'maal') {

            $totalHarta =
                (float) ($data['total_harta'] ?? 0);

            $pengurang =
                (float) ($data['pengurang'] ?? 0);

            $nisab =
                (float) ($data['nisab'] ?? 0);

            $persentase =
                (float) ($data['persentase'] ?? 0);

            /*
             * Harta bersih tidak boleh negatif.
             */
            $netAmount = max(
                0,
                $totalHarta - $pengurang
            );

            /*
             * Jika nisab diisi > 0,
             * lakukan pengecekan.
             */
            if ($nisab > 0) {

                $meetsNisab =
                    $netAmount >= $nisab;

            } else {

                $meetsNisab = true;
            }

            if ($meetsNisab) {

                $result =
                    $netAmount * ($persentase / 100);

            } else {

                $result = 0;
            }
        }

        return view(
            'zakat.index',
            [
                'result' => $result,
                'netAmount' => $netAmount,
                'meetsNisab' => $meetsNisab,

                'oldData' => $data,
            ]
        );
    }
}