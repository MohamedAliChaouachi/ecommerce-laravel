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
        <Head title="Products" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    All Products
                </h1>
                <p class="text-gray-600">
                    Discover our complete catalog
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
                            Search
                        </label>
                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Product name..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label
                            for="category"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Category
                        </label>
                        <select
                            id="category"
                            v-model="selectedCategory"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">
                                All categories
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
                            Sort by
                        </label>
                        <select
                            id="sort"
                            v-model="sortBy"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="latest">
                                Latest
                            </option>
                            <option value="name">
                                Name (A-Z)
                            </option>
                            <option value="price_asc">
                                Price: Low to High
                            </option>
                            <option value="price_desc">
                                Price: High to Low
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
                        Clear filters
                    </button>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-4 text-sm text-gray-600">
                {{ products.total }} product(s) found
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
                <h3 class="text-sm font-medium text-gray-900">
                    No products found
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Try adjusting your search filters
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
                        Previous
                    </Link>
                    <Link
                        v-if="products.next_page_url"
                        :href="products.next_page_url"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ products.from }}</span>
                            to
                            <span class="font-medium">{{ products.to }}</span>
                            of
                            <span class="font-medium">{{ products.total }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                            <Link
                                v-if="products.prev_page_url"
                                :href="products.prev_page_url"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                                <span class="sr-only">Previous</span>
                                «
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
                                <span class="sr-only">Next</span>
                                »
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
