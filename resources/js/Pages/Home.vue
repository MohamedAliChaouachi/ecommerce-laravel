<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';

defineProps({
    featuredProducts: Array,
    categories: Array,
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});
</script>

<template>
    <component :is="auth.user ? AuthenticatedLayout : GuestLayout">
        <Head title="Accueil - E-commerce" />

        <!-- Hero Section with Modern Design -->
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
                <div class="text-center text-white">
                    <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
                        Bienvenue sur <br />
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200">
                            Notre Boutique
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-10 text-purple-100 max-w-2xl mx-auto">
                        Découvrez nos produits de qualité exceptionnelle. Des technologies innovantes aux articles lifestyle.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link
                            :href="route('products.index')"
                            class="group inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-8 py-4 rounded-xl hover:bg-purple-50 transition-all shadow-xl hover:shadow-2xl hover:scale-105"
                        >
                            <span>Explorer les produits</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                        <button class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all border-2 border-white/30">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                            </svg>
                            <span>Nouveautés</span>
                        </button>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-16 max-w-3xl mx-auto">
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">{{ categories.length }}+</div>
                            <div class="text-purple-100 text-sm">Catégories</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">{{ featuredProducts.length * 4 }}+</div>
                            <div class="text-purple-100 text-sm">Produits</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">1000+</div>
                            <div class="text-purple-100 text-sm">Clients satisfaits</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Section with Enhanced Design -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-4">
                        Explorez par Catégorie
                    </h2>
                    <p class="text-lg text-gray-600">
                        Trouvez exactement ce que vous cherchez
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('products.index', { category: category.id })"
                        class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 text-center border-2 border-transparent hover:border-indigo-200"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-indigo-600 transition-colors">
                            {{ category.name }}
                        </h3>
                        <p class="text-sm text-gray-500 font-medium">
                            {{ category.products_count }} produits
                        </p>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Featured Products Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">
                        Produits en vedette
                    </h2>
                    <p class="text-gray-600">
                        Découvrez notre sélection des meilleurs produits
                    </p>
                </div>
                <Link
                    :href="route('products.index')"
                    class="group mt-4 md:mt-0 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold text-lg"
                >
                    <span>Voir tous les produits</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </Link>
            </div>
            <div
                v-if="featuredProducts.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <ProductCard
                    v-for="product in featuredProducts"
                    :key="product.id"
                    :product="product"
                />
            </div>
            <div
                v-else
                class="text-center py-20 bg-gray-50 rounded-2xl"
            >
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 text-lg">Aucun produit disponible pour le moment.</p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-indigo-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Livraison Gratuite</h3>
                        <p class="text-gray-600">Sur toutes les commandes de plus de 100 DT</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-purple-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Paiement Sécurisé</h3>
                        <p class="text-gray-600">Vos transactions sont 100% sécurisées</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-pink-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Retour Facile</h3>
                        <p class="text-gray-600">Retours gratuits sous 30 jours</p>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
