<section>
    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($users as $user)
        <!-- Cards -->
        <div class="relative overflow-hidden rounded-3xl bg-white/80 dark:bg-stone-800/80 backdrop-blur-xl border border-stone-200/60 dark:border-stone-700 shadow-xl hover:shadow-2xl transition-all duration-300">

            <!-- Decorative Background -->
            <div class="absolute top-0 right-0 h-40 w-40 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-32 w-32 bg-emerald-500/10 rounded-full blur-3xl"></div>

            <div class="relative p-6">

                <!-- Header -->
                <div class="flex justify-between items-start">
                    <div class="flex justify-between w-full flex-wrap">
                        <!-- User Profile -->
                        <div class="flex gap-4 items-center">

                            <!-- Avatar -->
                            <div class="relative">
                                <div class="h-18 w-18 rounded-2xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 p-[2px] shadow-lg">
                                    <div class="h-18 w-18 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10"
                                            viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Status Indicator -->
                                <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-4 border-white dark:border-stone-800
                        {{ $user->is_locked ? 'bg-red-500' : 'bg-green-500' }}">
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="min-w-0">
                                <h2 class="text-lg font-bold text-stone-800 dark:text-white truncate capitalize">
                                    {{ $user->name }}
                                </h2>

                                <p class="text-sm text-stone-500 dark:text-stone-400 capitalize">
                                    {{ $user->display_roles ?? 'No Role Assigned' }}
                                </p>

                                <!-- Status Badge -->
                                @if($user->is_locked)
                                <span class="inline-flex mt-2 items-center gap-2 rounded-full bg-red-100 dark:bg-red-900/30 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-300">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                    Account Locked
                                </span>
                                @else
                                <span class="inline-flex mt-2 items-center gap-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Active User
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Cost Center -->
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-2 text-white text-sm font-semibold shadow-md truncate max-w-[180px]"
                                title="{{ $user->office?->department ?? 'No Office Assigned' }}">
                                {{ $user->office?->department ?? 'No Office Assigned' }}
                            </span>

                            <span class="text-xs text-stone-500 dark:text-stone-400 mt-1">
                                Cost Center
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Divider -->
                <div class="my-6 border-t border-stone-200 dark:border-stone-700"></div>

                <!-- Info Grid -->
                <div class="grid md:grid-cols-2 gap-4">

                    <!-- Email -->
                    <div class="rounded-2xl bg-stone-100 dark:bg-stone-900 p-4">
                        <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">
                            Email
                        </p>

                        <a href="mailto:{{ $user->email }}"
                            class="mt-1 block truncate text-blue-600 dark:text-blue-400 font-medium hover:underline">
                            {{ $user->email }}
                        </a>
                    </div>

                    <!-- Signature -->
                    <div class="rounded-2xl bg-stone-100 dark:bg-stone-900 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">
                                Signature
                            </p>

                            <p class="text-sm font-medium text-stone-700 dark:text-stone-300">
                                User Signature
                            </p>
                        </div>

                        <img src="{{ $user->signature ? asset('storage/' . $user->signature) : asset('images/fall-back-signature.png') }}"
                            class="h-14 w-14 rounded-xl border border-stone-300 dark:border-stone-600 object-cover shadow">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex flex-wrap justify-end gap-3">

                    <!-- Edit -->
                    <x-success-button
                        type="button"
                        class="rounded-2xl shadow-md hover:scale-105 transition"
                        x-data=""
                        x-on:click="$dispatch('open-modal', { 
                    name: 'edit-user', 
                    userId: {{ $user->id }}, 
                    userName: '{{ $user->name }}', 
                    userEmail: '{{ $user->email }}',
                    userSignature: '{{ $user->signature }}'
                })">
                        Edit
                    </x-success-button>

                    <!-- Unlock -->
                    @if($user->is_locked)
                    <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <x-primary-button
                            type="submit"
                            class="rounded-2xl shadow-md hover:scale-105 transition">
                            Unlock
                        </x-primary-button>
                    </form>
                    @endif

                    <!-- Delete -->
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <x-danger-button
                            type="submit"
                            class="rounded-2xl shadow-md hover:scale-105 transition"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', {
                        name: 'delete-user',
                        userId: {{ $user->id }}
                    })">
                            Delete
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>

        @endforeach
        @include('modal.edit-user-modal')
        @include('modal.delete-user-modal')
        @include('modal.signature-user-modal')
    </div>
</section>