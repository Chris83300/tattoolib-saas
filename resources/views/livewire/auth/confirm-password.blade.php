<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirmer le mot de passe')"
            :description="__('Zone sécurisée. Veuillez confirmer votre mot de passe pour continuer.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Votre mot de passe')"
                viewable
            />

            @error('password')
                <flux:text color="red" class="text-sm -mt-4">{{ $message }}</flux:text>
            @enderror

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirmer') }}
            </flux:button>
        </form>
    </div>
</x-layouts.auth>
