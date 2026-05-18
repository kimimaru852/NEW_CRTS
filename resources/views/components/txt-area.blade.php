@props(['disabled' => false, 'rows' => 4])

<textarea
    rows="{{ $rows }}"
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'w-full border-gray-300 dark:border-gray-700 dark:bg-stone-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-50 focus:ring-indigo-500 dark:focus:ring-indigo-50 rounded-md shadow-sm'
    ]) }}>{{ $slot }}</textarea>
