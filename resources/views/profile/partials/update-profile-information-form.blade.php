<!-- FIleUpload -->
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>
        @if(auth()->user()->hasRole('admin'))
        <!-- Signature -->
        <div>
            <x-input-label for="signature" :value="__('Signature')" />

            {{-- Show current signature --}}
            @if ($user->signature)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $user->signature) }}"
                    alt="Current Signature"
                    class="h-20 border rounded">
            </div>

            {{-- keep old value --}}
            <input type="hidden" name="old_signature" value="{{ $user->signature }}">
            @endif

            {{-- File input for new upload --}}
            <input
                id="signature"
                name="signature"
                type="file"
                class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300"
                accept="image/*" />

            <x-input-error class="mt-2" :messages="$errors->get('signature')" />
        </div>
        @endif
        

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>


<!-- Canvas -->
<!-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    
    <form
        method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        x-data="signaturePad()"
        x-ref="form"
        class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="signature" :value="__('Signature')" />

            @if ($user->signature)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $user->signature) }}"
                    class="h-20 border rounded">
            </div>
            @endif

            <div class="mt-2 space-y-2 border rounded p-2">
                <canvas
                    x-ref="canvas"
                    class="w-full h-40 bg-white border rounded">
                </canvas>

                <div class="flex gap-2">
                    <button type="button"
                        @click="clear()"
                        class="px-3 py-1 bg-red-500 text-white rounded">
                        Clear
                    </button>
                </div>
                <input type="hidden" name="signature" x-ref="signatureInput">
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('signature')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button type="button" @click="submitForm">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Saved.') }}
            </p>
            @endif
        </div>
    </form>
    <script>
        function signaturePad() {
            return {
                isDrawing: false,
                ctx: null,

                init() {
                    this.$nextTick(() => {
                        const canvas = this.$refs.canvas;

                        if (!canvas) return;

                        canvas.width = canvas.offsetWidth;
                        canvas.height = 160;

                        this.ctx = canvas.getContext('2d');
                        this.ctx.strokeStyle = '#000';
                        this.ctx.lineWidth = 2;

                        // mouse events
                        canvas.addEventListener('mousedown', (e) => this.start(e));
                        canvas.addEventListener('mousemove', (e) => this.draw(e));
                        canvas.addEventListener('mouseup', () => this.stop());

                        // touch events
                        canvas.addEventListener('touchstart', (e) => this.start(e));
                        canvas.addEventListener('touchmove', (e) => this.draw(e));
                        canvas.addEventListener('touchend', () => this.stop());
                    });
                },

                start(e) {
                    this.isDrawing = true;
                    this.ctx.beginPath();
                },

                draw(e) {
                    if (!this.isDrawing) return;

                    const rect = this.$refs.canvas.getBoundingClientRect();

                    const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
                    const y = (e.touches ? e.touches[0].clientY : e.clientY) - rect.top;

                    this.ctx.lineTo(x, y);
                    this.ctx.stroke();
                },

                stop() {
                    this.isDrawing = false;
                },

                clear() {
                    this.ctx.clearRect(
                        0,
                        0,
                        this.$refs.canvas.width,
                        this.$refs.canvas.height
                    );
                },

                save() {
                    const dataURL = this.$refs.canvas.toDataURL('image/png');

                    console.log('SIGNATURE:', dataURL); // DEBUG

                    this.$refs.signatureInput.value = dataURL;
                },

                submitForm() {
                    this.save();

                    this.$nextTick(() => {
                        this.$refs.form.submit();
                    });
                }
            }
        }
    </script>
</section> -->