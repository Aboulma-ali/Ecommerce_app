<a href="{{ route('cart.index') }}" wire:navigate class="relative p-2.5 text-gray-500 transition-colors duration-200 hover:text-blue-600 hover:bg-blue-50/70 rounded-full group">

    <!-- Icône de sac de shopping -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
         class="h-6 w-6 transform transition-transform duration-200 group-hover:scale-110">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
    </svg>

    @if($count > 0)
        <!-- Badge du compteur avec une petite animation -->
        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white shadow-md transform transition-transform duration-200 group-hover:scale-110">
            {{ $count }}
        </span>
    @endif

    <!-- Tooltip (infobulle) -->
    <div class="absolute top-full mt-2 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
        Voir le panier
    </div>
</a>
