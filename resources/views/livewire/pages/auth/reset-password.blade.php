<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email', '');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $validated = $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        // Redirige vers la page de connexion avec un message de succès.
        session()->flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

    <!-- La page commence directement par la carte, qui est insérée dans le layout 'guest' -->
<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">

    <!-- Colonne de gauche : Formulaire -->
    <div class="p-8 sm:p-12 order-2 lg:order-1 flex flex-col justify-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" wire:navigate class="flex items-center group mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl mr-3">
                <x-application-logo class="block h-8 w-auto fill-current text-white" />
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                ShopVibe
            </span>
        </a>

        <!-- Titre -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Choisissez un nouveau mot de passe</h1>
            <p class="mt-2 text-gray-600">La dernière étape ! Assurez-vous qu'il soit sécurisé.</p>
        </div>

        <!-- Formulaire -->
        <form wire:submit="resetPassword" class="space-y-5">
            <!-- Champ Email (caché, mais nécessaire pour la logique) -->
            <input type="hidden" wire:model="email">

            <!-- Nouveau Mot de passe -->
            <div>
                <label for="password" class="text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>
                    <input wire:model="password" id="password" type="password" required autofocus autocomplete="new-password" placeholder="••••••••" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                @error('password') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Confirmation du mot de passe -->
            <div>
                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirmez le nouveau mot de passe</label>
                <div class="mt-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></span>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror

            <!-- Bouton de soumission -->
            <div class="pt-4">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg hover:scale-105 transform transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span wire:loading.remove wire:target="resetPassword">Réinitialiser le mot de passe</span>
                    <span wire:loading wire:target="resetPassword">Mise à jour...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Colonne de droite : Visuel -->
    <div class="hidden lg:flex items-center justify-center p-8 bg-gradient-to-br from-blue-600 to-purple-600 order-1 lg:order-2">
        <div class="text-white text-center">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/password-successfully-changed-5108846-4272183.png" alt="Illustration de réinitialisation de mot de passe" class="w-full max-w-sm mx-auto mb-8">
            <h2 class="text-3xl font-bold mb-3">Sécurité et simplicité</h2>
            <p class="text-blue-200">Votre compte est maintenant protégé. Vous pouvez vous connecter en toute confiance avec votre nouveau mot de passe.</p>
        </div>
    </div>
</div>
