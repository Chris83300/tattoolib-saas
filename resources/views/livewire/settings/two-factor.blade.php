<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Paramètres d\'authentification à deux facteurs') }}</flux:heading>

    <x-settings.layout
        :heading="__('Double authentification (2FA)')"
        :subheading="__('Gérez votre authentification à deux facteurs')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">{{ __('Activée') }}</flux:badge>
                    </div>

                    <flux:text>
                        {{ __('La double authentification est activée. À chaque connexion, vous devrez saisir un code généré par votre application d\'authentification (Google Authenticator, Authy…).') }}
                    </flux:text>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation/>

                    <div class="flex justify-start">
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            wire:click="disable"
                        >
                            {{ __('Désactiver le 2FA') }}
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">{{ __('Désactivée') }}</flux:badge>
                    </div>

                    <flux:text variant="subtle">
                        {{ __('En activant la double authentification, vous ajoutez une couche de sécurité supplémentaire à votre compte. Un code unique sera demandé à chaque connexion.') }}
                    </flux:text>

                    <flux:button
                        variant="primary"
                        icon="shield-check"
                        icon:variant="outline"
                        wire:click="enable"
                    >
                        {{ __('Activer le 2FA') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <flux:modal
        name="two-factor-setup-modal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        wire:model="showModal"
    >
        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        <div class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>
                        <div class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++)
                                <div></div>
                            @endfor
                        </div>
                        <flux:icon.qr-code class="relative z-20 dark:text-accent-foreground"/>
                    </div>
                </div>

                <div class="space-y-2 text-center">
                    <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                    <flux:text>{{ $this->modalConfig['description'] }}</flux:text>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center space-y-3 justify-center">
                        <flux:otp
                            name="code"
                            wire:model="code"
                            length="6"
                            label="Code OTP"
                            label:sr-only
                            class="mx-auto"
                        />
                    </div>

                    @error('code')
                        <flux:text color="red" class="text-sm text-center">{{ $message }}</flux:text>
                    @enderror

                    <div class="flex items-center space-x-3">
                        <flux:button variant="outline" class="flex-1" wire:click="resetVerification">
                            {{ __('Retour') }}
                        </flux:button>
                        <flux:button
                            variant="primary"
                            class="flex-1"
                            wire:click="confirmTwoFactor"
                            x-bind:disabled="$wire.code.length < 6"
                        >
                            {{ __('Confirmer') }}
                        </flux:button>
                    </div>
                </div>
            @else
                @error('setupData')
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}"/>
                @enderror

                <div class="flex justify-center">
                    <div class="relative w-64 overflow-hidden border rounded-lg border-stone-200 dark:border-stone-700 aspect-square">
                        @empty($qrCodeSvg)
                            <div class="absolute inset-0 flex items-center justify-center bg-white dark:bg-stone-700 animate-pulse">
                                <flux:icon.loading/>
                            </div>
                        @else
                            <div x-data class="flex items-center justify-center h-full p-4">
                                <div
                                    class="bg-white p-3 rounded"
                                    :class="document.documentElement.classList.contains('dark') ? 'invert brightness-150' : ''"
                                >
                                    {!! $qrCodeSvg !!}
                                </div>
                            </div>
                        @endempty
                    </div>
                </div>

                <div>
                    <flux:button
                        :disabled="$errors->has('setupData')"
                        variant="primary"
                        class="w-full"
                        wire:click="showVerificationIfNecessary"
                    >
                        {{ $this->modalConfig['buttonText'] }}
                    </flux:button>
                </div>

                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-stone-200 dark:bg-stone-600"></div>
                        <span class="relative px-2 text-sm bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-400">
                            {{ __('ou saisir la clé manuellement') }}
                        </span>
                    </div>

                    <div
                        class="flex items-center space-x-2"
                        x-data="{
                            copied: false,
                            async copy() {
                                try {
                                    await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 1500);
                                } catch (e) {
                                    console.warn('Could not copy to clipboard');
                                }
                            }
                        }"
                    >
                        <div class="flex items-stretch w-full border rounded-xl dark:border-stone-700">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3 bg-stone-100 dark:bg-stone-700">
                                    <flux:icon.loading variant="mini"/>
                                </div>
                            @else
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $manualSetupKey }}"
                                    class="w-full p-3 bg-transparent outline-none text-stone-900 dark:text-stone-100"
                                />
                                <button
                                    @click="copy()"
                                    class="px-3 transition-colors border-l cursor-pointer border-stone-200 dark:border-stone-600"
                                    aria-label="Copier la clé dans le presse-papiers"
                                >
                                    <flux:icon.document-duplicate x-show="!copied" variant="outline"/>
                                    <flux:icon.check x-show="copied" variant="solid" class="text-green-500"/>
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>
</section>
