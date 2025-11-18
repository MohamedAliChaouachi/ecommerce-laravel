<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true
    }
});

const isSubmitting = ref(false);

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800 border-yellow-200',
        processing: 'bg-blue-100 text-blue-800 border-blue-200',
        shipped: 'bg-purple-100 text-purple-800 border-purple-200',
        delivered: 'bg-green-100 text-green-800 border-green-200',
        cancelled: 'bg-red-100 text-red-800 border-red-200'
    };
    return colors[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        processing: 'En cours de traitement',
        shipped: 'Expédiée',
        delivered: 'Livrée',
        cancelled: 'Annulée'
    };
    return labels[status] || status;
};

const getTimelineSteps = () => {
    const allSteps = [
        { key: 'pending', label: 'Commande passée', icon: 'check' },
        { key: 'processing', label: 'En préparation', icon: 'package' },
        { key: 'shipped', label: 'Expédiée', icon: 'truck' },
        { key: 'delivered', label: 'Livrée', icon: 'home' }
    ];
    
    const currentIndex = allSteps.findIndex(step => step.key === props.order.status);
    
    return allSteps.map((step, index) => ({
        ...step,
        completed: index <= currentIndex && props.order.status !== 'cancelled',
        current: index === currentIndex && props.order.status !== 'cancelled',
        cancelled: props.order.status === 'cancelled'
    }));
};

const cancelOrder = () => {
    if (!confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        return;
    }
    
    isSubmitting.value = true;
    
    router.post(route('orders.cancel', props.order.id), {}, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Commande ${order.order_number}`" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <Link
                        :href="route('orders.index')"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour à mes commandes
                    </Link>
                </div>

                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                Commande {{ order.order_number }}
                            </h1>
                            <p class="text-sm text-gray-600">
                                Passée le {{ new Date(order.created_at).toLocaleDateString('fr-FR', { 
                                    day: 'numeric', 
                                    month: 'long', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) }}
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span
                                :class="getStatusColor(order.status)"
                                class="px-4 py-2 rounded-lg text-sm font-semibold border-2"
                            >
                                {{ getStatusLabel(order.status) }}
                            </span>
                            
                            <button
                                v-if="order.can_cancel"
                                @click="cancelOrder"
                                :disabled="isSubmitting"
                                class="px-4 py-2 border border-red-300 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-50 transition disabled:opacity-50"
                            >
                                {{ isSubmitting ? 'Annulation...' : 'Annuler la commande' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div v-if="order.status !== 'cancelled'" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Suivi de commande</h2>
                    
                    <div class="relative">
                        <!-- Progress Line -->
                        <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-200">
                            <div 
                                class="h-full bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-500"
                                :style="{ width: `${(getTimelineSteps().filter(s => s.completed).length - 1) * 33.33}%` }"
                            ></div>
                        </div>
                        
                        <!-- Steps -->
                        <div class="relative flex justify-between">
                            <div
                                v-for="step in getTimelineSteps()"
                                :key="step.key"
                                class="flex flex-col items-center"
                            >
                                <div
                                    :class="[
                                        'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all',
                                        step.completed 
                                            ? 'bg-gradient-to-r from-indigo-600 to-purple-600 border-indigo-600 text-white' 
                                            : 'bg-white border-gray-300 text-gray-400'
                                    ]"
                                >
                                    <svg v-if="step.icon === 'check'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <svg v-else-if="step.icon === 'package'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <svg v-else-if="step.icon === 'truck'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    </svg>
                                    <svg v-else-if="step.icon === 'home'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <p
                                    :class="[
                                        'mt-2 text-xs font-medium text-center',
                                        step.completed ? 'text-gray-900' : 'text-gray-400'
                                    ]"
                                >
                                    {{ step.label }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Items -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Articles commandés</h2>
                            
                            <div class="space-y-4">
                                <div
                                    v-for="item in order.order_items"
                                    :key="item.id"
                                    class="flex items-center space-x-4 pb-4 border-b last:border-0"
                                >
                                    <img
                                        :src="item.product?.image_path || 'https://via.placeholder.com/100'"
                                        :alt="item.product_name"
                                        class="w-24 h-24 object-cover rounded-lg"
                                    />
                                    
                                    <div class="flex-1">
                                        <Link
                                            v-if="item.product"
                                            :href="route('products.show', item.product.slug)"
                                            class="font-medium text-gray-900 hover:text-indigo-600"
                                        >
                                            {{ item.product_name }}
                                        </Link>
                                        <h4 v-else class="font-medium text-gray-900">{{ item.product_name }}</h4>
                                        
                                        <p class="text-sm text-gray-600 mt-1">
                                            Prix unitaire: {{ parseFloat(item.product_price).toFixed(2) }} DT
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Quantité: {{ item.quantity }}
                                        </p>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            {{ parseFloat(item.subtotal).toFixed(2) }} DT
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary & Details -->
                    <div class="space-y-6">
                        <!-- Order Summary -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Récapitulatif</h2>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Sous-total</span>
                                    <span class="font-medium">{{ parseFloat(order.subtotal).toFixed(2) }} DT</span>
                                </div>
                                
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>TVA</span>
                                    <span class="font-medium">{{ parseFloat(order.tax).toFixed(2) }} DT</span>
                                </div>
                                
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Livraison</span>
                                    <span class="font-medium" :class="order.shipping_cost === 0 ? 'text-green-600' : ''">
                                        {{ order.shipping_cost === 0 ? 'Gratuite' : parseFloat(order.shipping_cost).toFixed(2) + ' DT' }}
                                    </span>
                                </div>
                                
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">Total</span>
                                        <span class="font-bold text-xl text-indigo-600">
                                            {{ parseFloat(order.total).toFixed(2) }} DT
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Adresse de livraison
                            </h2>
                            
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-medium">{{ order.shipping_address.name }}</p>
                                <p>{{ order.shipping_address.address }}</p>
                                <p>{{ order.shipping_address.postal_code }} {{ order.shipping_address.city }}</p>
                                <p>Tél: {{ order.shipping_address.phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Paiement
                            </h2>
                            
                            <div class="text-sm text-gray-700 space-y-2">
                                <p>
                                    <span class="font-medium">Mode:</span>
                                    <span v-if="order.payment_method === 'cash_on_delivery'"> Paiement à la livraison</span>
                                    <span v-else-if="order.payment_method === 'credit_card'"> Carte bancaire</span>
                                    <span v-else-if="order.payment_method === 'paypal'"> PayPal</span>
                                </p>
                                
                                <p>
                                    <span class="font-medium">Statut:</span>
                                    <span 
                                        :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'" 
                                        class="font-semibold ml-1"
                                    >
                                        {{ order.payment_status === 'paid' ? 'Payé' : 
                                           order.payment_status === 'pending' ? 'En attente' : 
                                           order.payment_status === 'failed' ? 'Échoué' : 'Remboursé' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div v-if="order.notes" class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-blue-900 mb-2">Note</h3>
                            <p class="text-sm text-blue-800">{{ order.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
