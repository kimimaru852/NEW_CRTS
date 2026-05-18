@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-stone-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-50 focus:ring-indigo-500 dark:focus:ring-indigo-50 rounded-md shadow-sm']) }}>
