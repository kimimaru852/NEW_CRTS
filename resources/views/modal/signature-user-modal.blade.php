<div
    x-data="{
        show: false,
        userId: null,
        userSignature: '',
        init() {
            window.addEventListener('open-modal', event => {
                if (event.detail.name === 'signature-user') {
                    this.userId = event.detail.userId;
                    this.userSignature = event.detail.userSignature;
                    this.show = true;
                }
            });
        }
    }"
    x-init="init()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;">

    <!-- Overlay (Backdrop) -->
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-stone-900 opacity-75"></div>
    </div>

    <!-- Modal Content -->
    <div
        x-show="show"
        class="mb-6 bg-white dark:bg-stone-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="p-6">
            <form action="" method="POST" enctype="multipart/form-data" x-data="signaturePad()" x-ref="form">
                @csrf
                @method('patch')
                <div class="text-lg font-bold text-center">Upload E-Signature</div>
                <!-- Signature -->
                <div>
                    <x-input-label for="signature" :value="__('Current Signature')" />

                    <!-- Current Signature -->
                    <img
                        :src="userSignature ? '/storage/' + userSignature : '/images/fall-back-signature.png'"
                        class="h-20 border rounded">

                    {{-- File input for new upload --}}
                    <input
                        id="signature"
                        name="signature"
                        type="file"
                        class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300"
                        accept="image/*" />

                    <x-input-error class="mt-2" :messages="$errors->get('signature')" />
                </div>
            </form>
        </div>

    </div>
</div>