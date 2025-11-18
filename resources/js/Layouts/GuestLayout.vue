<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const cartCount = computed(() => page.props.cartCount || 0);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="border-b border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('home')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800"
                                />
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <Link
                                :href="route('home')"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-gray-900 transition duration-150 ease-in-out hover:border-gray-300 focus:border-gray-300 focus:outline-none"
                            >
                                Accueil
                            </Link>
                            <Link
                                :href="route('products.index')"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-gray-900 transition duration-150 ease-in-out hover:border-gray-300 focus:border-gray-300 focus:outline-none"
                            >
                                Produits
                            </Link>
                        </div>
                    </div>

                    <!-- Right Side: Cart & Auth Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                        <!-- Cart Icon -->
                        <Link
                            :href="route('cart.index')"
                            class="relative p-2 text-gray-700 hover:text-indigo-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span
                                v-if="cartCount > 0"
                                class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
                            >
                                {{ cartCount }}
                            </span>
                        </Link>

                        <Link
                            :href="route('login')"
                            class="text-sm text-gray-700 hover:text-gray-900"
                        >
                            Connexion
                        </Link>
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                        >
                            S'inscrire
                        </Link>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden"
            >
                <div class="space-y-1 pb-3 pt-2">
                    <Link
                        :href="route('home')"
                        class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                    >
                        Accueil
                    </Link>
                    <Link
                        :href="route('products.index')"
                        class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                    >
                        Produits
                    </Link>
                </div>

                <div class="border-t border-gray-200 pb-3 pt-4">
                    <div class="space-y-1">
                        <Link
                            :href="route('cart.index')"
                            class="flex items-center justify-between py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                        >
                            <span>Panier</span>
                            <span
                                v-if="cartCount > 0"
                                class="bg-indigo-600 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center"
                            >
                                {{ cartCount }}
                            </span>
                        </Link>
                        <Link
                            :href="route('login')"
                            class="block py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                        >
                            Connexion
                        </Link>
                        <Link
                            :href="route('register')"
                            class="block py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:bg-gray-50 hover:text-gray-800 focus:bg-gray-50 focus:text-gray-800 focus:outline-none"
                        >
                            S'inscrire
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-gray-500 text-sm">
                    © 2025 E-commerce Laravel. Tous droits réservés.
                </p>
            </div>
        </footer>
    </div>
</template>
