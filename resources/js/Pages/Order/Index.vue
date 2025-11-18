<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    orders: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const statusFilter = ref(props.filters.status || 'all');

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        shipped: 'bg-purple-100 text-purple-800',
        delivered: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        processing: 'En cours',
        shipped: 'Expédiée',
        delivered: 'Livrée',
        cancelled: 'Annulée'
    };
    return labels[status] || status;
};

const filterOrders = () => {
    const params = statusFilter.value === 'all' ? {} : { status: statusFilter.value };
    router.get(route('orders.index'), params, { preserveState: true });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Mes commandes" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Mes commandes</h1>
                    <p class="mt-2 text-gray-600">Consultez l'historique de vos commandes</p>
                </div>

                <!-- Filter -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <label for="status-filter" class="text-sm font-medium text-gray-700">
                                Filtrer par statut:
                            </label>
                            <select
                                id="status-filter"
                                v-model="statusFilter"
                                @change="filterOrders"
                                class="rounded-lg border-gray-300 py-2 px-4 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="all">Toutes</option>
                                <option value="pending">En attente</option>
                                <option value="processing">En cours</option>
                                <option value="shipped">Expédiées</option>
                                <option value="delivered">Livrées</option>
                                <option value="cancelled">Annulées</option>
                            </select>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            {{ orders.total }} commande(s) trouvée(s)
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div v-if="orders.data.length > 0" class="space-y-4">
                    <div
                        v-for="order in orders.data"
                        :key="order.id"
                        class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <!-- Order Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="font-semibold text-lg text-gray-900">
                                            {{ order.order_number }}
                                        </h3>
                                        <span
                                            :class="getStatusColor(order.status)"
                                            class="px-3 py-1 rounded-full text-xs font-semibold"
                                        >
                                            {{ getStatusLabel(order.status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Commandé le {{ new Date(order.created_at).toLocaleDateString('fr-FR', { 
                                                day: 'numeric', 
                                                month: 'long', 
                                                year: 'numeric' 
                                            }) }}
                                        </p>
                                        
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            {{ order.order_items_count }} article(s)
                                        </p>
                                        
                                        <p class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            {{ order.payment_method === 'cash_on_delivery' ? 'Paiement à la livraison' : 
                                               order.payment_method === 'credit_card' ? 'Carte bancaire' : 'PayPal' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Order Total & Actions -->
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 lg:border-l lg:pl-6">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Total</p>
                                        <p class="text-2xl font-bold text-indigo-600">
                                            {{ parseFloat(order.total).toFixed(2) }} DT
                                        </p>
                                    </div>
                                    
                                    <Link
                                        :href="route('orders.show', order.id)"
                                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm hover:shadow-md"
                                    >
                                        Voir les détails
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div v-if="order.order_items && order.order_items.length > 0" class="mt-4 pt-4 border-t">
                                <div class="flex items-center space-x-4 overflow-x-auto">
                                    <div
                                        v-for="item in order.order_items.slice(0, 4)"
                                        :key="item.id"
                                        class="flex-shrink-0"
                                    >
                                        <img
                                            :src="item.product?.image_path || 'https://via.placeholder.com/60'"
                                            :alt="item.product_name"
                                            class="w-16 h-16 object-cover rounded-lg"
                                        />
                                    </div>
                                    <div v-if="order.order_items.length > 4" class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-semibold text-gray-600">
                                        +{{ order.order_items.length - 4 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune commande</h3>
                    <p class="text-gray-600 mb-6">
                        {{ statusFilter !== 'all' ? 'Aucune commande avec ce statut' : 'Vous n\'avez pas encore passé de commande' }}
                    </p>
                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Commencer mes achats
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="orders.data.length > 0 && (orders.prev_page_url || orders.next_page_url)" class="mt-6">
                    <div class="flex items-center justify-between bg-white rounded-lg shadow-sm p-4">
                        <Link
                            v-if="orders.prev_page_url"
                            :href="orders.prev_page_url"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Précédent
                        </Link>
                        <div v-else></div>
                        
                        <span class="text-sm text-gray-600">
                            Page {{ orders.current_page }} sur {{ orders.last_page }}
                        </span>
                        
                        <Link
                            v-if="orders.next_page_url"
                            :href="orders.next_page_url"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
                        >
                            Suivant
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                        <div v-else></div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
