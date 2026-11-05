@props([
    'label',
    'value',
    'detail' => null,
    'tone' => 'slate',
    'featured' => false,
])

@php
    $tones = [
        'emerald' => ['accent' => 'border-t-emerald-600', 'value' => 'text-emerald-800', 'featured' => 'bg-emerald-800 text-white'],
        'slate' => ['accent' => 'border-t-slate-700', 'value' => 'text-slate-900', 'featured' => 'bg-slate-900 text-white'],
        'blue' => ['accent' => 'border-t-blue-600', 'value' => 'text-blue-800', 'featured' => 'bg-blue-800 text-white'],
        'rose' => ['accent' => 'border-t-rose-600', 'value' => 'text-rose-800', 'featured' => 'bg-rose-800 text-white'],
        'amber' => ['accent' => 'border-t-amber-500', 'value' => 'text-amber-800', 'featured' => 'bg-amber-700 text-white'],
        'violet' => ['accent' => 'border-t-violet-600', 'value' => 'text-violet-800', 'featured' => 'bg-violet-800 text-white'],
    ];
    $selectedTone = $tones[$tone] ?? $tones['slate'];
@endphp

<article {{ $attributes->class([
    'overflow-hidden rounded-2xl border border-slate-200 border-t-4 p-5 shadow-sm sm:p-6',
    $selectedTone['accent'] => ! $featured,
    'border-emerald-800 border-t-emerald-400' => $featured,
    $selectedTone['featured'] => $featured,
]) }}>
    <p @class([
        'text-xs font-semibold uppercase tracking-[0.12em]',
        'text-white/70' => $featured,
        'text-slate-500' => ! $featured,
    ])>{{ $label }}</p>
    <p @class([
        'mt-3 text-2xl font-bold tracking-tight sm:text-3xl',
        'text-white' => $featured,
        $selectedTone['value'] => ! $featured,
    ])>{{ $value }}</p>
    @if($detail)
        <div @class([
            'mt-3 text-sm',
            'text-white/75' => $featured,
            'text-slate-500' => ! $featured,
        ])>{{ $detail }}</div>
    @endif
</article>
