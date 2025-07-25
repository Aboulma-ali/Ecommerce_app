<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-bold text-red-700">
            Zone de Danger
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Supprimer le Compte</x-danger-button>

    {{-- La modale reste identique, elle est déjà bien conçue --}}
    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Êtes-vous absolument sûr ?
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Cette action est irréversible. Pour confirmer, veuillez entrer votre mot de passe.
            </p>
            <div class="mt-6">
                <input wire:model="password" type="password" class="mt-1 block w-3/4 border-gray-300 ..." placeholder="Votre mot de passe" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>
                <x-danger-button class="ms-3">
                    Supprimer Définitivement
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
