<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-10 items-center justify-center rounded-lg border border-transparent bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-50']) }}>
    {{ $slot }}
</button>
