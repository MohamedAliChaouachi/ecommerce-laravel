<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';

const props = defineProps({
    product: Object,
    relatedProducts: Array,
    auth: {
        type: Object,
        default: () => ({ user: null }),
    },
});

const quantity = ref(1);
const isAddingToCart = ref(false);

const incrementQuantity = () => {
    if (quantity.value < props.product.stock) {
        quantity.value++;
    }
};

const decrementQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const addToCart = () => {
    if (isAddingToCart.value) return;
    
    isAddingToCart.value = true;
    
    router.post(
        route('cart.add', props.product.id),
        { quantity: quantity.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                quantity.value = 1;
            },
            onFinish: () => {
                isAddingToCart.value = false;
            }
        }
    );
};
</script>

<template>
    <component :is="auth.user ? AuthenticatedLayout : GuestLayout">
        <Head :title="product.name" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm">
                <Link
                    :href="route('home')"
                    class="text-gray-500 hover:text-gray-700"
                >
                    Accueil
                </Link>
                <span class="mx-2 text-gray-400">/</span>
                <Link
                    :href="route('products.index')"
                    class="text-gray-500 hover:text-gray-700"
                >
                    Produits
                </Link>
                <span class="mx-2 text-gray-400">/</span>
                <Link
                    :href="route('products.index', { category: product.category_id })"
                    class="text-gray-500 hover:text-gray-700"
                >
                    {{ product.category?.name }}
                </Link>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-900">{{ product.name }}</span>
            </nav>

            <!-- Product Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                <!-- Product Image -->
                <div class="bg-gray-100 rounded-lg overflow-hidden">
                    <img
                        :src="product.image_path || '/images/placeholder.jpg'"
                        :alt="product.name"
                        class="w-full h-96 object-cover object-center"
                    />
                </div>

                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">
                        {{ product.name }}
                    </h1>

                    <div class="mb-4">
                        <Link
                            :href="route('products.index', { category: product.category_id })"
                            class="inline-block text-sm text-indigo-600 hover:text-indigo-800"
                        >
                            {{ product.category?.name }}
                        </Link>
                    </div>

                    <div class="mb-6">
                        <span class="text-4xl font-bold text-indigo-600">
                            {{ product.formatted_price || `${parseFloat(product.price).toFixed(2)} DT` }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">
                            Description
                        </h2>
                        <p class="text-gray-700 leading-relaxed">
                            {{ product.description }}
                        </p>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        <div
                            v-if="product.stock > 0"
                            class="flex items-center text-green-600"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="font-medium">
                                En stock ({{ product.stock }} disponible(s))
                            </span>
                        </div>
                        <div
                            v-else
                            class="flex items-center text-red-600"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="font-medium">Rupture de stock</span>
                        </div>
                        <div
                            v-if="product.stock > 0 && product.stock < 10"
                            class="mt-2 text-sm text-orange-600"
                        >
                            ⚠️ Stock limité - Il ne reste que {{ product.stock }} article(s)
                        </div>
                    </div>

                    <!-- Quantity Selector & Add to Cart -->
                    <div
                        v-if="product.stock > 0"
                        class="mb-6"
                    >
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Quantité
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button
                                    @click="decrementQuantity"
                                    :disabled="quantity <= 1"
                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    -
                                </button>
                                <input
                                    v-model.number="quantity"
                                    type="number"
                                    min="1"
                                    :max="product.stock"
                                    class="w-16 text-center border-0 focus:ring-0"
                                />
                                <button
                                    @click="incrementQuantity"
                                    :disabled="quantity >= product.stock"
                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    +
                                </button>
                            </div>
                            <button
                                @click="addToCart"
                                :disabled="isAddingToCart"
                                class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="isAddingToCart">Ajout en cours...</span>
                                <span v-else>Ajouter au panier</span>
                            </button>
                        </div>
                    </div>

                    <!-- Out of Stock Message -->
                    <div
                        v-else
                        class="bg-red-50 border border-red-200 rounded-md p-4"
                    >
                        <p class="text-red-800 font-medium">
                            Ce produit est actuellement en rupture de stock.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div
                v-if="relatedProducts.length > 0"
                class="mt-16"
            >
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Produits similaires
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="relatedProduct in relatedProducts"
                        :key="relatedProduct.id"
                        :product="relatedProduct"
                    />
                </div>
            </div>
        </div>
    </component>
</template>
