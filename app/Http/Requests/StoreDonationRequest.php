<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'donor_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'donor_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'type' => [
                'required',
                'in:infaq,sedekah,zakat,qurban',
            ],

            'amount' => [
                'required',
                'integer',
                'min:1000',
                'max:9999999999999',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_method' => [
                'required',
                'in:qris,transfer,cash,other',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'donor_name' => 'nama donatur',
            'donor_email' => 'email',
            'donor_phone' => 'nomor HP',
            'type' => 'jenis donasi',
            'amount' => 'nominal',
            'note' => 'keterangan',
            'payment_method' => 'metode pembayaran',
        ];
    }
}
