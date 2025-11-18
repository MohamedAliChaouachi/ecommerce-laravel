<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true
    },
    items: {
        type: Array,
        required: true
    }
});

const print = () => {
    window.print();
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Commande confirmée" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Message -->
                <div class="text-center mb-12">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Commande confirmée !</h1>
                    <p class="text-xl text-gray-600">Merci pour votre commande</p>
                    <p class="mt-2 text-gray-600">
                        Numéro de commande: <span class="font-semibold text-indigo-600">{{ order.order_number }}</span>
                    </p>
                </div>

                <!-- Order Details Card -->
                <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Détails de la commande</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Shipping Address -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Adresse de livraison
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                <p class="font-medium">{{ order.shipping_address.name }}</p>
                                <p>{{ order.shipping_address.address }}</p>
                                <p>{{ order.shipping_address.postal_code }} {{ order.shipping_address.city }}</p>
                                <p>Tél: {{ order.shipping_address.phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Mode de paiement
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                <p v-if="order.payment_method === 'cash_on_delivery'" class="font-medium">
                                    Paiement à la livraison
                                </p>
                                <p v-else-if="order.payment_method === 'credit_card'" class="font-medium">
                                    Carte bancaire
                                </p>
                                <p v-else-if="order.payment_method === 'paypal'" class="font-medium">
                                    PayPal
                                </p>
                                <p class="mt-2 text-xs">
                                    Statut: <span :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'" class="font-semibold">
                                        {{ order.payment_status === 'paid' ? 'Payé' : 'En attente' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Articles commandés</h3>
                        <div class="space-y-4">
                            <div
                                v-for="item in items"
                                :key="item.id"
                                class="flex items-center space-x-4 pb-4 border-b last:border-0"
                            >
                                <img
                                    :src="item.product?.image_path || 'https://via.placeholder.com/80'"
                                    :alt="item.product_name"
                                    class="w-20 h-20 object-cover rounded-lg"
                                />
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ item.product_name }}</h4>
                                    <p class="text-sm text-gray-600">Prix unitaire: {{ parseFloat(item.product_price).toFixed(2) }} DT</p>
                                    <p class="text-sm text-gray-600">Quantité: {{ item.quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ parseFloat(item.subtotal).toFixed(2) }} DT</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-6 mt-6">
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total</span>
                                <span class="font-semibold">{{ parseFloat(order.subtotal).toFixed(2) }} DT</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>TVA</span>
                                <span class="font-semibold">{{ parseFloat(order.tax).toFixed(2) }} DT</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Livraison</span>
                                <span class="font-semibold" :class="order.shipping_cost === 0 ? 'text-green-600' : ''">
                                    {{ order.shipping_cost === 0 ? 'Gratuite' : parseFloat(order.shipping_cost).toFixed(2) + ' DT' }}
                                </span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between text-2xl font-bold text-gray-900">
                                    <span>Total</span>
                                    <span class="text-indigo-600">{{ parseFloat(order.total).toFixed(2) }} DT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <Link
                        :href="route('orders.show', order.id)"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Voir les détails
                    </Link>
                    
                    <button
                        @click="print"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer le reçu
                    </button>
                    
                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Continuer mes achats
                    </Link>
                </div>

                <!-- What's Next -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Et maintenant ?
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-900">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Nous préparons votre commande
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Vous recevrez un email de confirmation
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Suivez votre commande depuis votre espace client
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Livraison estimée : 2-5 jours ouvrables
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media print {
    .bg-gray-50 {
        background: white !important;
    }
    button, a {
        display: none !important;
    }
}
</style>
