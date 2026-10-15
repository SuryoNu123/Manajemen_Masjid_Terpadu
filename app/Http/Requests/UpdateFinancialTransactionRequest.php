<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateFinancialTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:financial_categories,id',
            ],

            'type' => [
                'required',
                'in:income,expense',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'source' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'evidence' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'type' => 'jenis transaksi',
            'amount' => 'nominal',
            'transaction_date' => 'tanggal transaksi',
            'source' => 'sumber',
            'description' => 'keterangan',
            'evidence' => 'bukti transaksi',
        ];
    }
}
