<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    auth: Object,
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const stockFilter = ref(props.filters.stock || '');

// Debounced search
let searchTimeout;
watch([search, category, stockFilter], () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(route('admin.products.index'), {
            search: search.value,
            category: category.value,
            stock: stockFilter.value,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 300);
});

const deleteProduct = (product) => {
    if (confirm(`Are you sure you want to delete "${product.name}"?`)) {
        router.delete(route('admin.products.destroy', product.id), {
            preserveScroll: true,
        });
    }
};

const formatCurrency = (amount) => {
    return `${parseFloat(amount).toFixed(2)} DT`;
};

const getStockBadge = (stock) => {
    if (stock === 0) return { class: 'bg-red-100 text-red-800', text: 'Out of Stock' };
    if (stock <= 10) return { class: 'bg-yellow-100 text-yellow-800', text: 'Low Stock' };
    return { class: 'bg-green-100 text-green-800', text: 'In Stock' };
};
</script>

<template>
    <AdminLayout :auth="auth">
        <Head title="Manage Products" />

        <template #header>
            Products
        </template>

        <!-- Header Actions -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Product Management</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your product catalog, prices, and inventory
                </p>
            </div>
            <Link
                :href="route('admin.products.create')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </Link>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        Search Products
                    </label>
                    <div class="relative">
                        <input
                            id="search"
                            v-model="search"
                            type="text"
                            placeholder="Search by name..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pl-10"
                        />
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Category
                    </label>
                    <select
                        id="category"
                        v-model="category"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                        <option value="">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Stock Filter -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                        Stock Status
                    </label>
                    <select
                        id="stock"
                        v-model="stockFilter"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    >
                        <option value="">All Stock Levels</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock (≤10)</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ product.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ product.slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ product.category.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ formatCurrency(product.price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ product.stock }} units</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="getStockBadge(product.stock).class"
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                >
                                    {{ getStockBadge(product.stock).text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <Link
                                    :href="route('admin.products.edit', product.id)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteProduct(product)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="products.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="text-sm text-gray-500 mb-4">No products found</p>
                                    <Link
                                        :href="route('admin.products.create')"
                                        class="text-indigo-600 hover:text-indigo-700 font-medium"
                                    >
                                        Create your first product →
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.data.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <Link
                            v-if="products.prev_page_url"
                            :href="products.prev_page_url"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Previous
                        </Link>
                        <Link
                            v-if="products.next_page_url"
                            :href="products.next_page_url"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Next
                        </Link>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ products.from }}</span>
                                to
                                <span class="font-medium">{{ products.to }}</span>
                                of
                                <span class="font-medium">{{ products.total }}</span>
                                products
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <component
                                    v-for="link in products.links"
                                    :key="link.label"
                                    :is="link.url ? Link : 'span'"
                                    :href="link.url"
                                    :class="[
                                        link.active
                                            ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                            : link.url
                                            ? 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            : 'bg-gray-100 border-gray-300 text-gray-400 cursor-not-allowed',
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                                    ]"
                                    v-html="link.label"
                                />
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
