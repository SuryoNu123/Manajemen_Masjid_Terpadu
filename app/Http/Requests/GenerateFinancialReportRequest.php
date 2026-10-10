<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateFinancialReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period_type' => [
                'required',
                'in:monthly,yearly',
            ],

            'month' => [
                'nullable',
                'integer',
                'between:1,12',
            ],

            'year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if (
                $this->period_type === 'monthly'
                && !$this->month
            ) {
                $validator->errors()->add(
                    'month',
                    'Bulan wajib diisi untuk laporan bulanan.'
                );
            }

            if (
                $this->period_type === 'yearly'
                && $this->month
            ) {
                $validator->errors()->add(
                    'month',
                    'Bulan tidak diperlukan untuk laporan tahunan.'
                );
            }
        });
    }
}