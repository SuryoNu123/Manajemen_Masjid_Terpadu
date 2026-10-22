@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'min-h-10 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-none placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500']) }}>
