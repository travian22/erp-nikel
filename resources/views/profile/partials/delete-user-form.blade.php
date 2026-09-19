<section class="space-y-6">
    <header>
        <h2 class="text-base font-bold text-slate-900">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data terkait akan dihapus secara permanen.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="text-xs font-semibold rounded-md shadow-2xs"
    >{{ __('Hapus Akun Saya') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-slate-900">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-2 text-xs text-slate-500">
                {{ __('Setelah akun dihapus, semua data tidak dapat dikembalikan. Silakan masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full text-xs rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500"
                    placeholder="{{ __('Masukkan Kata Sandi Anda') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="text-xs font-semibold rounded-md">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="text-xs font-semibold rounded-md shadow-2xs">
                    {{ __('Ya, Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
