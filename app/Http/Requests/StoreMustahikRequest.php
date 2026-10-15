<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMustahikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'asnaf' => [
                'required',
                'in:fakir,miskin,amil,muallaf,riqab,gharim,fisabilillah,ibnu_sabil',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:pending,distributed',
            ],

            'distributed_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama mustahik',
            'asnaf' => 'kategori asnaf',
            'address' => 'alamat',
            'phone' => 'nomor HP',
            'amount' => 'nominal',
            'status' => 'status penyaluran',
            'distributed_at' => 'tanggal penyaluran',
            'notes' => 'catatan',
        ];
    }
}
