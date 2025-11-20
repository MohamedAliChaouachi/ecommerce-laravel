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
        <Head title="Home - E-commerce" />

        <!-- Hero Section with Modern Design -->
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
                <div class="text-center text-white">
                    <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
                        Welcome to <br />
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200">
                            Our Store
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-10 text-purple-100 max-w-2xl mx-auto">
                        Discover our exceptional quality products. From innovative technology to lifestyle items.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link
                            :href="route('products.index')"
                            class="inline-flex items-center gap-2 bg-white text-indigo-600 font-bold px-8 py-4 rounded-xl hover:bg-purple-50 transition-all shadow-xl hover:shadow-2xl hover:scale-105"
                        >
                            <span>Explore Products</span>
                        </Link>
                        <button class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white font-bold px-8 py-4 rounded-xl hover:bg-white/20 transition-all border-2 border-white/30">
                            <span>New Arrivals</span>
                        </button>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 mt-16 max-w-3xl mx-auto">
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">{{ categories.length }}+</div>
                            <div class="text-purple-100 text-sm">Categories</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">{{ featuredProducts.length * 4 }}+</div>
                            <div class="text-purple-100 text-sm">Products</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">1000+</div>
                            <div class="text-purple-100 text-sm">Happy Customers</div>
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
                        Explore by Category
                    </h2>
                    <p class="text-lg text-gray-600">
                        Find exactly what you're looking for
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('products.index', { category: category.id })"
                        class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 text-center border-2 border-transparent hover:border-indigo-200"
                    >
                        <h3 class="font-bold text-gray-900 text-lg mb-2 hover:text-indigo-600 transition-colors">
                            {{ category.name }}
                        </h3>
                        <p class="text-sm text-gray-500 font-medium">
                            {{ category.products_count }} products
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
                        Featured Products
                    </h2>
                    <p class="text-gray-600">
                        Discover our selection of the best products
                    </p>
                </div>
                <Link
                    :href="route('products.index')"
                    class="mt-4 md:mt-0 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold text-lg"
                >
                    <span>View all products</span>
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
                <p class="text-gray-500 text-lg">No products available at the moment.</p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Free Shipping</h3>
                        <p class="text-gray-600">On all orders over 100 DT</p>
                    </div>
                    <div class="text-center p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Secure Payment</h3>
                        <p class="text-gray-600">Your transactions are 100% secure</p>
                    </div>
                    <div class="text-center p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Easy Returns</h3>
                        <p class="text-gray-600">Free returns within 30 days</p>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>
