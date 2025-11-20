<script setup>
import { router, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({ user: null })
    },
    cart: {
        type: Object,
        required: true
    }
});

const updateQuantity = (productId, quantity) => {
    if (quantity < 1) return;
    
    router.put(route('cart.update', productId), { quantity }, {
        preserveScroll: true
    });
};

const removeItem = (productId) => {
    if (confirm('Are you sure you want to remove this product?')) {
        router.delete(route('cart.remove', productId), {
            preserveScroll: true
        });
    }
};

const clearCart = () => {
    if (confirm('Are you sure you want to empty your cart?')) {
        router.delete(route('cart.clear'));
    }
};

const getImageUrl = (imagePath) => {
    return imagePath || 'https://via.placeholder.com/400x400?text=No+Image';
};
</script>

<template>
    <component :is="auth.user ? AuthenticatedLayout : GuestLayout" :auth="auth">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900">My Cart</h1>
                    <p class="mt-2 text-gray-600">
                        {{ cart.count }} {{ cart.count > 1 ? 'items' : 'item' }} in your cart
                    </p>
                </div>

                <!-- Cart Content -->
                <div v-if="!cart.isEmpty" class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Clear Cart Button -->
                        <div class="flex justify-end mb-4">
                            <button
                                @click="clearCart"
                                class="text-sm text-red-600 hover:text-red-700 font-medium"
                            >
                                Empty Cart
                            </button>
                        </div>

                        <!-- Cart Items List -->
                        <div
                            v-for="item in cart.items"
                            :key="item.id"
                            class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow"
                        >
                            <div class="flex items-start space-x-4">
                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a
                                            :href="route('products.show', item.slug)"
                                            class="hover:text-indigo-600 transition-colors"
                                        >
                                            {{ item.name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-xl font-bold text-indigo-600">
                                        {{ parseFloat(item.price).toFixed(2) }} DT
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Available stock: {{ item.stock }}
                                    </p>
                                </div>

                                <!-- Quantity Controls & Remove -->
                                <div class="flex flex-col items-end space-y-4">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-2">
                                        <button
                                            @click="updateQuantity(item.id, item.quantity - 1)"
                                            :disabled="item.quantity <= 1"
                                            class="px-3 py-1 rounded border-2 border-gray-300 hover:border-indigo-600 hover:text-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            -
                                        </button>
                                        <span class="w-12 text-center font-semibold text-gray-900">
                                            {{ item.quantity }}
                                        </span>
                                        <button
                                            @click="updateQuantity(item.id, item.quantity + 1)"
                                            :disabled="item.quantity >= item.stock"
                                            class="px-3 py-1 rounded border-2 border-gray-300 hover:border-indigo-600 hover:text-indigo-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <!-- Subtotal -->
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ (parseFloat(item.price) * item.quantity).toFixed(2) }} DT
                                    </p>

                                    <!-- Remove Button -->
                                    <button
                                        @click="removeItem(item.id)"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1 mt-8 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Summary</h2>
                            
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold">{{ cart.subtotal.toFixed(2) }} DT</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="font-semibold text-green-600">Free</span>
                                </div>
                                <div class="border-t pt-4">
                                    <div class="flex justify-between text-xl font-bold text-gray-900">
                                        <span>Total</span>
                                        <span class="text-indigo-600">{{ cart.total.toFixed(2) }} DT</span>
                                    </div>
                                </div>
                            </div>

                            <Link
                                v-if="auth.user"
                                :href="route('checkout.index')"
                                class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center"
                            >
                                Proceed to Checkout
                            </Link>
                            <Link
                                v-else
                                :href="route('login')"
                                class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center"
                            >
                                Login to Checkout
                            </Link>

                            <a
                                :href="route('products.index')"
                                class="block w-full text-center mt-4 text-indigo-600 hover:text-indigo-700 font-medium"
                            >
                                ← Continue shopping
                            </a>

                            <!-- Trust Badges -->
                            <div class="mt-6 pt-6 border-t space-y-3 text-sm text-gray-600">
                                <div>• Secure payment</div>
                                <div>• Free shipping</div>
                                <div>• 14-day returns</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-else class="text-center py-16">
                    <h2 class="text-3xl font-bold text-gray-900">Your cart is empty</h2>
                    <p class="mt-2 text-gray-600">Start shopping now!</p>
                    <a
                        :href="route('products.index')"
                        class="inline-block mt-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl"
                    >
                        Discover our products
                    </a>
                </div>
            </div>
        </div>
    </component>
</template>
