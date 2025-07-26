<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            Informations Personnelles
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Mettez à jour votre nom et votre adresse email.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-6">
        <!-- Nom -->
        <div>
            <label for="name" class="text-sm font-medium text-gray-700">Nom complet</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" ...></svg></span>
                <input wire:model="name" id="name" type="text" class="block w-full max-w-lg pl-10 pr-3 py-2 border border-gray-300 rounded-md ...">
            </div>
            @error('name') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" ...></svg></span>
                <input wire:model="email" id="email" type="email" class="block w-full max-w-lg pl-10 pr-3 py-2 border border-gray-300 rounded-md ...">
            </div>
            @error('email') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror

            {{-- Section de vérification d'email (si nécessaire) --}}
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:scale-105 transition-transform">
                Enregistrer
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">
                    Modifications enregistrées.
                </p>
            @endif
        </div>
    </form>
</section>
