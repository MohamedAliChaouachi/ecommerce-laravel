<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const getDemoImage = (item) => {
    const name = item.product_name || item.name || 'Product';
    return `https://via.placeholder.com/80/4F46E5/FFFFFF?text=$\{encodeURIComponent(name.substring(0, 10))}`;
};

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
        pending: 'Pending',
        processing: 'Processing',
        shipped: 'Shipped',
        delivered: 'Delivered',
        cancelled: 'Cancelled'
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
        <Head title="My Orders" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                    <p class="mt-2 text-gray-600">View your order history and track your purchases</p>
                </div>

                <!-- Filter -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <label for="status-filter" class="text-sm font-medium text-gray-700">
                                Filter by Status:
                            </label>
                            <select
                                id="status-filter"
                                v-model="statusFilter"
                                @change="filterOrders"
                                class="rounded-lg border-gray-300 py-2 px-4 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="all">All</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            {{ orders.Total }} order(s) found
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
                                        <p>
                                            Ordered on {{ new Date(order.created_at).toLocaleDateString('en-US', { 
                                                day: 'numeric', 
                                                month: 'long', 
                                                year: 'numeric' 
                                            }) }}
                                        </p>
                                        
                                        <p>
                                            {{ order.order_items_count }} item(s)
                                        </p>
                                        
                                        <p>
                                            {{ order.payment_method === 'cash_on_delivery' ? 'Cash on Delivery' : 
                                               order.payment_method === 'credit_card' ? 'Credit Card' : 'PayPal' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Order Total & Actions -->
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 lg:border-l lg:pl-6">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-1">Total</p>
                                        <p class="text-2xl font-bold text-indigo-600">
                                            {{ parseFloat(order.Total).toFixed(2) }} DT
                                        </p>
                                    </div>
                                    
                                    <Link
                                        :href="route('orders.show', order.id)"
                                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm hover:shadow-md"
                                    >
                                        View Details
                                    </Link>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div v-if="order.order_items && order.order_items.length > 0" class="mt-4 pt-4 border-t">
                                <p class="text-sm text-gray-600">
                                    {{ order.order_items_count }} item(s) in this order
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No orders</h3>
                    <p class="text-gray-600 mb-6">
                        {{ statusFilter !== 'all' ? 'No orders with this status' : 'You haven\'t placed any orders yet' }}
                    </p>
                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition"
                    >
                        Start Shopping
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
