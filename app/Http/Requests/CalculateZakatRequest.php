<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateZakatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zakat_type' => [
                'required',
                'in:fitrah,maal',
            ],

            /*
             * Zakat Fitrah
             */
            'jumlah_orang' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'nilai_per_orang' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
             * Zakat Maal
             */
            'total_harta' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'pengurang' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'nisab' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'persentase' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'zakat_type' => 'jenis zakat',
            'jumlah_orang' => 'jumlah orang',
            'nilai_per_orang' => 'nilai zakat per orang',
            'total_harta' => 'total harta',
            'pengurang' => 'pengurang/hutang',
            'nisab' => 'nisab',
            'persentase' => 'persentase zakat',
        ];
    }
}