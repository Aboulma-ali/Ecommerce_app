<div>
    <!-- ============================================ -->
    <!-- HERO SECTION -->
    <!-- ============================================ -->
    <section class="relative w-full min-h-screen flex items-center bg-gray-900 overflow-hidden">
        <!-- Background Grid -->
        <div class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
        <!-- Background Aurora -->
        <div class="absolute inset-0 opacity-40">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600 rounded-full mix-blend-lighten filter blur-3xl animate-aurora"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-600 rounded-full mix-blend-lighten filter blur-3xl animate-aurora animation-delay-2000"></div>
            <div class="absolute -bottom-20 right-20 w-80 h-80 bg-pink-600 rounded-full mix-blend-lighten filter blur-3xl animate-aurora animation-delay-4000"></div>
        </div>

        <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="text-white text-center lg:text-left">
                    <span class="inline-block bg-white/10 text-sm font-semibold px-4 py-1 rounded-full mb-4">Bienvenue sur ShopVibe ✨</span>
                    <h1 class="text-5xl lg:text-6xl font-extrabold mb-6 leading-tight tracking-tighter">
                        Votre Style,
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">
                            Votre Vibe.
                        </span>
                    </h1>
                    <p class="text-xl lg:text-2xl mb-10 text-gray-300 max-w-lg mx-auto lg:mx-0">
                        Explorez des milliers de produits tendance et trouvez les articles qui vous ressemblent vraiment.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#featured-products" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transform transition-all duration-300 shadow-xl">
                            Explorer la collection
                        </a>
                        <a href="#categories" class="px-8 py-4 bg-white/10 border border-white/20 text-white rounded-full font-bold text-lg hover:bg-white/20 transform transition-all duration-200">
                            Voir les catégories
                        </a>
                    </div>
                </div>

                <!-- Right Visual: Floating Product Cards -->
                <div class="hidden lg:block relative h-[500px]">
                    <div class="absolute w-48 top-0 left-20 animate-float" style="animation-duration: 6s;">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/10">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400" class="rounded-lg">
                        </div>
                    </div>
                    <div class="absolute w-36 top-48 left-0 animate-float" style="animation-duration: 8s; animation-delay: 1s;">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/10">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400" class="rounded-lg">
                        </div>
                    </div>
                    <div class="absolute w-56 bottom-0 left-32 animate-float" style="animation-duration: 7s; animation-delay: 0.5s;">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/10">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400" class="rounded-lg">
                        </div>
                    </div>
                    <div class="absolute w-40 top-10 right-0 animate-float" style="animation-duration: 9s; animation-delay: 1.5s;">
                        <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/10">
                            <img src="https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?q=80&w=400" class="rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- TRUST/BRANDS BAR -->
    <!-- ============================================ -->
    <div class="bg-gray-100 py-8">
        <div class="max-w-screen-lg mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 items-center justify-items-center">
                <span class="font-semibold text-gray-500 text-center col-span-2 md:col-span-1">Nos partenaires:</span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Stripe_logo%2C_revised_2016.svg" alt="Stripe" class="h-6 opacity-60 hover:opacity-100 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a4/Paypal_2014_logo.png" alt="Paypal" class="h-6 opacity-60 hover:opacity-100 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" alt="Apple Pay" class="h-6 opacity-60 hover:opacity-100 transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Visa.svg/1200px-Visa.svg.png" alt="Visa" class="h-4 opacity-60 hover:opacity-100 transition">
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- CATEGORIES SECTION -->
    <!-- ============================================ -->
    <section id="categories" class="py-20 sm:py-28 bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Parcourez par Univers</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Trouvez l'inspiration pour chaque aspect de votre vie.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Category Card 1: Mode -->
                <a href="#" class="group relative block h-80 rounded-2xl overflow-hidden shadow-lg">
                    <!-- Background Image -->
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=600"
                         alt="Catégorie Mode"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110">

                    <!-- Gradient Overlay for Readability -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>

                    <!-- Content -->
                    <div class="relative h-full flex flex-col justify-end p-8 text-white">
                        <h3 class="text-3xl font-bold mb-1">Mode</h3>
                        <p class="text-gray-200">Vêtements & Accessoires</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -translate-x-4 group-hover:translate-x-0">
                            <span class="inline-block px-5 py-2 text-sm font-semibold bg-white text-black rounded-full">Explorer</span>
                        </div>
                    </div>
                </a>

                <!-- Category Card 2: High-Tech -->
                <a href="#" class="group relative block h-80 rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=600" alt="Catégorie High-Tech" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-end p-8 text-white">
                        <h3 class="text-3xl font-bold mb-1">High-Tech</h3>
                        <p class="text-gray-200">Électronique & Gadgets</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -translate-x-4 group-hover:translate-x-0">
                            <span class="inline-block px-5 py-2 text-sm font-semibold bg-white text-black rounded-full">Explorer</span>
                        </div>
                    </div>
                </a>

                <!-- Category Card 3: Maison -->
                <a href="#" class="group relative block h-80 rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1618220179428-22790b461013?q=80&w=600" alt="Catégorie Maison" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-end p-8 text-white">
                        <h3 class="text-3xl font-bold mb-1">Maison</h3>
                        <p class="text-gray-200">Décoration & Meubles</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -translate-x-4 group-hover:translate-x-0">
                            <span class="inline-block px-5 py-2 text-sm font-semibold bg-white text-black rounded-full">Explorer</span>
                        </div>
                    </div>
                </a>

                <!-- Category Card 4: Sport -->
                <a href="#" class="group relative block h-80 rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?q=80&w=600" alt="Catégorie Sport" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-end p-8 text-white">
                        <h3 class="text-3xl font-bold mb-1">Sport</h3>
                        <p class="text-gray-200">Équipements & Fitness</p>
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform -translate-x-4 group-hover:translate-x-0">
                            <span class="inline-block px-5 py-2 text-sm font-semibold bg-white text-black rounded-full">Explorer</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- FEATURED PRODUCTS SECTION -->
    <!-- ============================================ -->
    <section id="featured-products" class="py-20 sm:py-28 bg-gray-50">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Notre Sélection Tendance</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Les articles que nos clients adorent, choisis pour vous.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Product Card 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <div class="relative">
                        <a href="#" class="block h-64">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400" alt="Chaussures de sport" class="w-full h-full object-cover">
                        </a>
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">-30%</div>
                        <button class="absolute bottom-4 right-4 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <span class="text-sm text-gray-500">Chaussures</span>
                        <h3 class="font-semibold text-lg text-gray-800 mt-1 mb-2">Baskets X-Runner Pro</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-blue-600">89,99€</span>
                            <span class="text-gray-400 line-through">119,99€</span>
                        </div>
                    </div>
                </div>
                <!-- ... Ajoutez 3 autres cartes produits sur le même modèle ... -->
                <!-- Product Card 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <div class="relative">
                        <a href="#" class="block h-64">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400" alt="Casque audio" class="w-full h-full object-cover">
                        </a>
                        <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">Nouveau</div>
                        <button class="absolute bottom-4 right-4 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <span class="text-sm text-gray-500">Audio</span>
                        <h3 class="font-semibold text-lg text-gray-800 mt-1 mb-2">Casque Pro-Sound II</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-blue-600">149,00€</span>
                        </div>
                    </div>
                </div>
                <!-- Product Card 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <div class="relative">
                        <a href="#" class="block h-64">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=400" alt="Montre connectée" class="w-full h-full object-cover">
                        </a>
                        <button class="absolute bottom-4 right-4 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <span class="text-sm text-gray-500">Accessoires</span>
                        <h3 class="font-semibold text-lg text-gray-800 mt-1 mb-2">Montre Fit-Time 3</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-blue-600">199,50€</span>
                        </div>
                    </div>
                </div>
                <!-- Product Card 4 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <div class="relative">
                        <a href="#" class="block h-64">
                            <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?q=80&w=400" alt="Clavier mécanique" class="w-full h-full object-cover">
                        </a>
                        <div class="absolute top-4 left-4 bg-yellow-500 text-gray-900 text-xs font-bold px-2.5 py-1 rounded-full">Hot</div>
                        <button class="absolute bottom-4 right-4 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <span class="text-sm text-gray-500">Gaming</span>
                        <h3 class="font-semibold text-lg text-gray-800 mt-1 mb-2">Clavier Mécanique RGB</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-blue-600">125,00€</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-16">
                <a href="#" class="px-8 py-4 bg-gray-900 text-white rounded-full font-bold text-lg hover:bg-gray-700 transform transition-all duration-200">
                    Voir tous les produits
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- TESTIMONIALS SECTION -->
    <!-- ============================================ -->
    <section class="py-20 sm:py-28 bg-white">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Ce que nos clients disent</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">La satisfaction de nos clients est notre plus grande fierté.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/68.jpg" alt="Avatar">
                        <div>
                            <p class="font-bold">Sophie L.</p>
                            <p class="text-sm text-gray-500">Cliente vérifiée</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"Expérience incroyable ! Le site est magnifique, la livraison a été super rapide et la qualité du produit est au-delà de mes attentes. Je recommande à 100% !"</p>
                </div>
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Avatar">
                        <div>
                            <p class="font-bold">Marc D.</p>
                            <p class="text-sm text-gray-500">Client vérifié</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"Enfin un site e-commerce qui allie design et efficacité. Navigation fluide, checkout simple et un service client réactif. Bravo à toute l'équipe de ShopVibe."</p>
                </div>
                <!-- Testimonial 3 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-100">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/44.jpg" alt="Avatar">
                        <div>
                            <p class="font-bold">Amina K.</p>
                            <p class="text-sm text-gray-500">Cliente vérifiée</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"J'ai commandé une montre connectée, elle est arrivée en 24h. Le packaging était soigné et le produit est parfait. Je suis plus que satisfaite de mon achat."</p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================ -->
    <!-- FOOTER -->
    <!-- ============================================ -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-screen-xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                <div class="col-span-2 md:col-span-4 lg:col-span-1">
                    <a href="#" class="flex items-center group mb-4">
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
                    <!-- Social icons -->
                </div>
            </div>
        </div>
    </footer>


    <!-- Styles pour les animations custom -->
    <style>
        @keyframes float {
            0% { transform: translatey(0px); }
            50% { transform: translatey(-20px); }
            100% { transform: translatey(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes aurora {
            from { transform: translate(50px, 50px) rotate(0deg); }
            to { transform: translate(150px, 80px) rotate(360deg); }
        }
        .animate-aurora {
            animation: aurora 20s alternate-reverse infinite linear;
        }

        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</div>
