<x-app-layout>
    {{-- Header de la page --}}
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Mon Profil
        </h2>
    </x-slot>

    {{-- Conteneur principal avec un fond subtil --}}
    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Carte 1 : Informations du Profil --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    {{-- Le contenu de votre formulaire sera injecté ici --}}
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            {{-- Carte 2 : Mise à jour du mot de passe --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            {{-- Carte 3 : Suppression du compte --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-t-4 border-red-500">
                <div class="p-6 sm:p-8">
                    <livewire:profile.delete-user-form />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
