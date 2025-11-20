<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const getDemoImage = (item) => {
    const name = item.product_name || item.name || 'Product';
    return `https://via.placeholder.com/80/4F46E5/FFFFFF?text=$\{encodeURIComponent(name.substring(0, 10))}`;
};

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
        <Head title="Order Confirmed" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Message -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
                    <p class="text-xl text-gray-600">Thank you for your order</p>
                    <p class="mt-2 text-gray-600">
                        Order number: <span class="font-semibold text-indigo-600">{{ order.order_number }}</span>
                    </p>
                </div>

                <!-- Order Details Card -->
                <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Shipping Address -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">
                                Shipping Address
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                <p class="font-medium">{{ order.shipping_address.name }}</p>
                                <p>{{ order.shipping_address.address }}</p>
                                <p>{{ order.shipping_address.postal_code }} {{ order.shipping_address.city }}</p>
                                <p>Tel: {{ order.shipping_address.phone }}</p>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">
                                Payment Method
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                <p v-if="order.payment_method === 'cash_on_delivery'" class="font-medium">
                                    Cash on Delivery
                                </p>
                                <p v-else-if="order.payment_method === 'credit_card'" class="font-medium">
                                    Credit Card
                                </p>
                                <p v-else-if="order.payment_method === 'paypal'" class="font-medium">
                                    PayPal
                                </p>
                                <p class="mt-2 text-xs">
                                    Status: <span :class="order.payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'" class="font-semibold">
                                        {{ order.payment_status === 'paid' ? 'Paid' : 'Pending' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Ordered Items</h3>
                        <div class="space-y-4">
                            <div
                                v-for="item in items"
                                :key="item.id"
                                class="flex items-center space-x-4 pb-4 border-b last:border-0"
                            >
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ item.product_name }}</h4>
                                    <p class="text-sm text-gray-600">Unit price: {{ parseFloat(item.product_price).toFixed(2) }} DT</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ parseFloat(item.subTotal).toFixed(2) }} DT</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-t pt-6 mt-6">
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold">{{ parseFloat(order.subTotal).toFixed(2) }} DT</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span class="font-semibold">{{ parseFloat(order.tax).toFixed(2) }} DT</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="font-semibold" :class="order.shipping_cost === 0 ? 'text-green-600' : ''">
                                    {{ order.shipping_cost === 0 ? 'Free' : parseFloat(order.shipping_cost).toFixed(2) + ' DT' }}
                                </span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between text-2xl font-bold text-gray-900">
                                    <span>Total</span>
                                    <span class="text-indigo-600">{{ parseFloat(order.Total).toFixed(2) }} DT</span>
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
                        View Details
                    </Link>
                    
                    <button
                        @click="print"
                        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
                    >
                        Print Receipt
                    </button>
                    
                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition"
                    >
                        Continue Shopping
                    </Link>
                </div>

                <!-- What's Next -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="font-semibold text-blue-900 mb-3">
                        What's Next?
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-900 list-disc list-inside">
                        <li>
                            We are preparing your order
                        </li>
                        <li>
                            You will receive a confirmation email
                        </li>
                        <li>
                            Track your order from your account
                        </li>
                        <li>
                            Estimated delivery: 2-5 business days
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
