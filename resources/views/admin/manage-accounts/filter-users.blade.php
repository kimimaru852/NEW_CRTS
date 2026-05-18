<section>
    <form method="GET" action="{{ route('search') }}">
        <div class="flex items-center justify-around py-2 ">
            <!-- <x-input-label :value="__('Search: ')" class="mr-2" /> -->
            <x-text-input placeholder="Ex. Juan P. Dela Cruz" name="search" id="search" value="{{ request('search') }}" class="w-full outline-none text-gray-700 dark:bg-stone-800 px-4 py-2 rounded-full text-sm" />
            <span class="flex justify-center items-center">
                <button type="submit" class="flex p-2 transition-transform transform hover:scale-110 border-2 rounded-r-2xl dark:border-gray-600 bg-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-gray-600">
                        <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
                    </svg>search
                </button>
            </span>
        </div>
    </form>
</section>