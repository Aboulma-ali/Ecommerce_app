<!-- ============================================ -->
<!-- FOOTER -->
<!-- ============================================ -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-screen-xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            <div class="col-span-2 md:col-span-4 lg:col-span-1">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center group mb-4">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-xl mr-3">
                        <x-application-logo class="block h-8 w-auto fill-current text-white" />
                    </div>
                    <span class="text-2xl font-bold">ShopVibe</span>
                </a>
                <p class="text-gray-400">La destination shopping pour un style unique et une qualité inégalée.</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Shop</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Nouveautés</a></li>
                    <li><a href="#" class="hover:text-white">Promotions</a></li>
                    <li><a href="#" class="hover:text-white">Meilleures ventes</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Support</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Contact</a></li>
                    <li><a href="#" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Suivi de commande</a></li>
                    <li><a href="#" class="hover:text-white">Retours</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Légal</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Conditions de vente</a></li>
                    <li><a href="#" class="hover:text-white">Politique de confidentialité</a></li>
                    <li><a href="#" class="hover:text-white">Mentions légales</a></li>
                </ul>
            </div>
            <div class="col-span-2 md:col-span-1">
                <h3 class="font-bold mb-4">Newsletter</h3>
                <p class="text-gray-400 mb-4">Recevez nos offres en avant-première.</p>
                <div class="flex">
                    <input type="email" placeholder="Votre email" class="w-full px-4 py-2 rounded-l-md border-0 bg-gray-800 focus:ring-2 focus:ring-blue-500">
                    <button class="bg-blue-600 px-4 rounded-r-md hover:bg-blue-700">OK</button>
                </div>
            </div>
        </div>
        <div class="mt-16 pt-8 border-t border-gray-800 flex flex-col sm:flex-row justify-between items-center">
            <p class="text-gray-500">&copy; {{ date('Y') }} ShopVibe. Tous droits réservés.</p>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <!-- Icônes des réseaux sociaux ici -->
            </div>
        </div>
    </div>
</footer>
