@props(['disabled' => false, 'start' => 1900, 'end' => now()->year, 'selected' => null])

<select @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'w-full border-gray-300 dark:border-gray-700 dark:bg-stone-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-50 focus:ring-indigo-500 dark:focus:ring-indigo-50 rounded-md shadow-sm'
    ]) }}>
    @for ($year = $end; $year >= $start; $year--)
        <option value="{{ $year }}" {{ $selected == $year ? 'selected' : '' }}>{{ $year }}</option>
    @endfor
</select>
