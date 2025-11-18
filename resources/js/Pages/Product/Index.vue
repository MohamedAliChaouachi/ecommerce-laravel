<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const sortBy = ref(props.filters.sort || 'latest');

// Debounced search
let searchTimeout = null;
watch([search, selectedCategory, sortBy], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('products.index'),
            {
                search: search.value,
                category: selectedCategory.value,
                sort: sortBy.value,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 300);
});

const clearFilters = () => {
    search.value = '';
    selectedCategory.value = '';
    sortBy.value = 'latest';
};
</script>

<template>
    <component :is="auth.user ? AuthenticatedLayout : GuestLayout">
        <Head title="Produits" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Tous les produits
                </h1>
                <p class="text-gray-600">
                    Découvrez notre catalogue complet
                </p>
            </div>

            <!-- Filters Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label
                            for="search"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Rechercher
                        </label>
                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Nom du produit..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label
                            for="category"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Catégorie
                        </label>
                        <select
                            id="category"
                            v-model="selectedCategory"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">
                                Toutes les catégories
                            </option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label
                            for="sort"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Trier par
                        </label>
                        <select
                            id="sort"
                            v-model="sortBy"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="latest">
                                Plus récent
                            </option>
                            <option value="name">
                                Nom (A-Z)
                            </option>
                            <option value="price_asc">
                                Prix croissant
                            </option>
                            <option value="price_desc">
                                Prix décroissant
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                <div
                    v-if="filters.search || filters.category || filters.sort !== 'latest'"
                    class="mt-4"
                >
                    <button
                        @click="clearFilters"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-medium"
                    >
                        Effacer les filtres
                    </button>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                {{ products.total }} produit(s) trouvé(s)
            </div>

            <!-- Products Grid -->
            <div
                v-if="products.data.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8"
            >
                <ProductCard
                    v-for="product in products.data"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="text-center py-12"
            >
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    Aucun produit trouvé
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Essayez de modifier vos filtres de recherche
                </p>
            </div>

            <!-- Pagination -->
            <div
                v-if="products.data.length > 0 && products.last_page > 1"
                class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-lg"
            >
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="products.prev_page_url"
                        :href="products.prev_page_url"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Précédent
                    </Link>
                    <Link
                        v-if="products.next_page_url"
                        :href="products.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Suivant
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-medium">{{ products.from }}</span>
                            à
                            <span class="font-medium">{{ products.to }}</span>
                            sur
                            <span class="font-medium">{{ products.total }}</span>
                            résultats
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                            <Link
                                v-if="products.prev_page_url"
                                :href="products.prev_page_url"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                                <span class="sr-only">Précédent</span>
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>

                            <Link
                                v-for="link in products.links.slice(1, -1)"
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'
                                        : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50',
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold',
                                ]"
                                v-html="link.label"
                            />

                            <Link
                                v-if="products.next_page_url"
                                :href="products.next_page_url"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                                <span class="sr-only">Suivant</span>
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
