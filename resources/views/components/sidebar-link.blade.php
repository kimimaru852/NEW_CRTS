@props(['href', 'active' => false])

<a href="{{ $href }}" 
   class="block py-2 rounded-md text-gray-900 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-stone-700 
          {{ $active ? 'bg-stone-200 border-r-2 border-r-green-500 dark:bg-stone-700  dark:text-green-500' : '' }}">
   {{ $slot }}
</a>
