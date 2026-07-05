<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 font-bold">
        <div class="flex underline underline-offset-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <a href="{{ route('admin.grdslists') }}">GRDS/RDS Lists</a>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('admin.registerForm') }}">Add Cost Center Head</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>
    <header class="flex justify-between items-center mx-2 mt-4 ">
        <h2 class="w-full py-6 px-4 bg-gradient-to-b from-blue-600 to-indigo-800 text-gray-50 font-bold text-xl rounded-t-lg">Cost Center Head & Users</h2>
    </header>

    <div class="mx-2 py-4 sm:px-6 bg-white dark:bg-stone-800 shadow shadow-stone-500/50">
        @if($users->count() > 0)
        @include('admin.manage-accounts.filter-users')
        @include('admin.manage-accounts.retrieve-users')
        @include('pagination.retrieve-user-pagination')
        @else
        <p class="text-red-500 text-center">No users found.</p>
        @endif
    </div>



    @if(session('error'))
    <div x-data="{ show: true }" x-show="show"
        z-50 bg-red-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 z-50 bg-green-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('success') }}</p>
    </div>
    @endif


</x-app-layout>