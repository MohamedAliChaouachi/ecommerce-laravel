<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const getDemoImage = (item) => {
    const name = item.product_name || item.name || 'Product';
    return `https://via.placeholder.com/80/4F46E5/FFFFFF?text=$\{encodeURIComponent(name.substring(0, 10))}`;
};

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
        pending: 'Pending',
        processing: 'Processing',
        shipped: 'Shipped',
        delivered: 'Delivered',
        cancelled: 'Cancelled'
    };
    return labels[status] || status;
};

const getTimelineSteps = () => {
    const allSteps = [
        { key: 'pending', label: 'Order Placed', icon: 'check' },
        { key: 'processing', label: 'Preparing', icon: 'package' },
        { key: 'shipped', label: 'Shipped', icon: 'truck' },
        { key: 'delivered', label: 'Delivered', icon: 'home' }
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
    if (!confirm('Are you sure you want to cancel this order?')) {
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
        <Head :title="`Order ${order.order_number}`" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <Link
                        :href="route('orders.index')"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to my orders
                    </Link>
                </div>

                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                Order {{ order.order_number }}
                            </h1>
                            <p class="text-sm text-gray-600">
                                Placed on {{ new Date(order.created_at).toLocaleDateString('en-US', { 
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
                                {{ isSubmitting ? 'Cancelling...' : 'Cancel Order' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div v-if="order.status !== 'cancelled'" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Tracking</h2>
                    
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
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ordered Items</h2>
                            
                            <div class="space-y-4">
                                <div
                                    v-for="item in order.order_items"
                                    :key="item.id"
                                    class="flex items-center space-x-4 pb-4 border-b last:border-0"
                                >
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
                                            Unit price: {{ parseFloat(item.product_price).toFixed(2) }} DT
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Quantity: {{ item.quantity }}
                                        </p>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">
                                            {{ parseFloat(item.subTotal).toFixed(2) }} DT
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
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-medium">{{ parseFloat(order.subTotal).toFixed(2) }} DT</span>
                                </div>
                                
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Tax</span>
                                    <span class="font-medium">{{ parseFloat(order.tax).toFixed(2) }} DT</span>
                                </div>
                                
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Shipping</span>
                                    <span class="font-medium" :class="order.shipping_cost === 0 ? 'text-green-600' : ''">
                                        {{ order.shipping_cost === 0 ? 'Free' : parseFloat(order.shipping_cost).toFixed(2) + ' DT' }}
                                    </span>
                                </div>
                                
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">Total</span>
                                        <span class="font-bold text-xl text-indigo-600">
                                            {{ parseFloat(order.Total).toFixed(2) }} DT
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                                Shipping Address
                            </h2>
                            
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-medium">{{ order.shipping_address.name }}</p>
                                <p>{{ order.shipping_address.address }}</p>
                                <p>{{ order.shipping_address.postal_code }} {{ order.shipping_address.city }}</p>
                                <p>Tel: {{ order.shipping_address.phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                                Payment
                            </h2>
                            
                            <div class="text-sm text-gray-700 space-y-2">
                                <p>
                                    <span class="font-medium">Method:</span>
                                    <span v-if="order.payment_method === 'cash_on_delivery'"> Cash on Delivery</span>
                                    <span v-else-if="order.payment_method === 'credit_card'"> Credit Card</span>
                                    <span v-else-if="order.payment_method === 'paypal'"> PayPal</span>
                                </p>
                                
                                <p>
                                    <span class="font-medium">Status:</span>
                                    <span 
                                        :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'" 
                                        class="font-semibold ml-1"
                                    >
                                        {{ order.payment_status === 'paid' ? 'Paid' : 
                                           order.payment_status === 'pending' ? 'Pending' : 
                                           order.payment_status === 'failed' ? 'Failed' : 'Refunded' }}
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
