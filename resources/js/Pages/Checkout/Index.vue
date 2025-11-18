<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    cart: {
        type: Object,
        required: true
    }
});

const currentStep = ref(1);
const isSubmitting = ref(false);

// Form data
const shippingAddress = ref({
    name: '',
    address: '',
    city: '',
    postal_code: '',
    phone: ''
});

const billingAddress = ref({
    name: '',
    address: '',
    city: '',
    postal_code: '',
    phone: ''
});

const sameAsShipping = ref(true);
const paymentMethod = ref('cash_on_delivery');
const notes = ref('');

// Validation errors
const errors = ref({});

// Step validation
const isStep1Valid = computed(() => {
    return shippingAddress.value.name &&
           shippingAddress.value.address &&
           shippingAddress.value.city &&
           shippingAddress.value.postal_code &&
           shippingAddress.value.phone;
});

const isStep2Valid = computed(() => {
    return paymentMethod.value !== '';
});

// Navigation
const nextStep = () => {
    if (currentStep.value === 1 && !isStep1Valid.value) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }
    if (currentStep.value === 2 && !isStep2Valid.value) {
        alert('Veuillez choisir un mode de paiement');
        return;
    }
    if (currentStep.value < 3) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const copyShippingToBilling = () => {
    if (sameAsShipping.value) {
        billingAddress.value = { ...shippingAddress.value };
    }
};

// Submit order
const submitOrder = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    
    const orderData = {
        shipping_address: shippingAddress.value,
        billing_address: sameAsShipping.value ? shippingAddress.value : billingAddress.value,
        payment_method: paymentMethod.value,
        notes: notes.value
    };
    
    router.post(route('checkout.store'), orderData, {
        onSuccess: () => {
            // Will redirect to confirmation page
        },
        onError: (err) => {
            errors.value = err;
            isSubmitting.value = false;
            currentStep.value = 1; // Go back to first step
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Paiement" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-900">Paiement</h1>
                    <p class="mt-2 text-gray-600">Complétez votre commande en quelques étapes</p>
                </div>

                <!-- Step Indicator -->
                <div class="mb-12">
                    <div class="flex items-center justify-center">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div :class="[
                                'w-12 h-12 rounded-full flex items-center justify-center font-semibold transition-colors',
                                currentStep >= 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Livraison</span>
                        </div>
                        
                        <!-- Line -->
                        <div :class="[
                            'w-24 h-1 mx-4 transition-colors',
                            currentStep >= 2 ? 'bg-indigo-600' : 'bg-gray-200'
                        ]"></div>
                        
                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div :class="[
                                'w-12 h-12 rounded-full flex items-center justify-center font-semibold transition-colors',
                                currentStep >= 2 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Paiement</span>
                        </div>
                        
                        <!-- Line -->
                        <div :class="[
                            'w-24 h-1 mx-4 transition-colors',
                            currentStep >= 3 ? 'bg-indigo-600' : 'bg-gray-200'
                        ]"></div>
                        
                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div :class="[
                                'w-12 h-12 rounded-full flex items-center justify-center font-semibold transition-colors',
                                currentStep >= 3 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600'
                            ]">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Confirmation</span>
                        </div>
                    </div>
                </div>

                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <!-- Form Steps -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <!-- Step 1: Shipping Address -->
                            <div v-if="currentStep === 1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Adresse de livraison</h2>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nom complet *
                                        </label>
                                        <input
                                            v-model="shippingAddress.name"
                                            type="text"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Adresse *
                                        </label>
                                        <input
                                            v-model="shippingAddress.address"
                                            type="text"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required
                                        />
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Ville *
                                            </label>
                                            <input
                                                v-model="shippingAddress.city"
                                                type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Code postal *
                                            </label>
                                            <input
                                                v-model="shippingAddress.postal_code"
                                                type="text"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Téléphone *
                                        </label>
                                        <input
                                            v-model="shippingAddress.phone"
                                            type="tel"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Payment Method -->
                            <div v-if="currentStep === 2">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Mode de paiement</h2>
                                
                                <div class="space-y-4">
                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors hover:border-indigo-500" :class="paymentMethod === 'cash_on_delivery' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                        <input
                                            v-model="paymentMethod"
                                            type="radio"
                                            value="cash_on_delivery"
                                            class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <div class="ml-4">
                                            <p class="font-semibold text-gray-900">Paiement à la livraison</p>
                                            <p class="text-sm text-gray-600 mt-1">Payez en espèces lors de la réception de votre commande</p>
                                        </div>
                                    </label>

                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors hover:border-indigo-500" :class="paymentMethod === 'credit_card' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                        <input
                                            v-model="paymentMethod"
                                            type="radio"
                                            value="credit_card"
                                            class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <div class="ml-4">
                                            <p class="font-semibold text-gray-900">Carte bancaire</p>
                                            <p class="text-sm text-gray-600 mt-1">Paiement sécurisé par carte de crédit</p>
                                            <p class="text-xs text-gray-500 mt-1">(Bientôt disponible)</p>
                                        </div>
                                    </label>
                                </div>

                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Notes (optionnel)
                                    </label>
                                    <textarea
                                        v-model="notes"
                                        rows="3"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Instructions de livraison, remarques..."
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Step 3: Review Order -->
                            <div v-if="currentStep === 3">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">Confirmation de la commande</h2>
                                
                                <!-- Shipping Address Review -->
                                <div class="mb-6">
                                    <h3 class="font-semibold text-gray-900 mb-2">Adresse de livraison</h3>
                                    <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                        <p class="font-medium">{{ shippingAddress.name }}</p>
                                        <p>{{ shippingAddress.address }}</p>
                                        <p>{{ shippingAddress.postal_code }} {{ shippingAddress.city }}</p>
                                        <p>Tél: {{ shippingAddress.phone }}</p>
                                    </div>
                                </div>

                                <!-- Payment Method Review -->
                                <div class="mb-6">
                                    <h3 class="font-semibold text-gray-900 mb-2">Mode de paiement</h3>
                                    <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                        <p v-if="paymentMethod === 'cash_on_delivery'">Paiement à la livraison</p>
                                        <p v-else-if="paymentMethod === 'credit_card'">Carte bancaire</p>
                                        <p v-else-if="paymentMethod === 'paypal'">PayPal</p>
                                    </div>
                                </div>

                                <!-- Order Items Review -->
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">Articles commandés</h3>
                                    <div class="space-y-3">
                                        <div
                                            v-for="item in cart.items"
                                            :key="item.id"
                                            class="flex items-center space-x-4 bg-gray-50 p-3 rounded-lg"
                                        >
                                            <img
                                                :src="item.image_path || 'https://via.placeholder.com/80'"
                                                :alt="item.name"
                                                class="w-16 h-16 object-cover rounded"
                                            />
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ item.name }}</p>
                                                <p class="text-sm text-gray-600">Quantité: {{ item.quantity }}</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">
                                                {{ (parseFloat(item.price) * item.quantity).toFixed(2) }} DT
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="mt-8 flex justify-between">
                                <button
                                    v-if="currentStep > 1"
                                    @click="prevStep"
                                    class="px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
                                >
                                    ← Retour
                                </button>
                                <div v-else></div>

                                <button
                                    v-if="currentStep < 3"
                                    @click="nextStep"
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition"
                                >
                                    Continuer →
                                </button>
                                <button
                                    v-else
                                    @click="submitOrder"
                                    :disabled="isSubmitting"
                                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="isSubmitting">Traitement...</span>
                                    <span v-else>Confirmer la commande</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="lg:col-span-1 mt-8 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Récapitulatif</h2>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Sous-total</span>
                                    <span class="font-semibold">{{ cart.subtotal.toFixed(2) }} DT</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>TVA (10%)</span>
                                    <span class="font-semibold">{{ cart.tax.toFixed(2) }} DT</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Livraison</span>
                                    <span class="font-semibold" :class="cart.shipping_cost === 0 ? 'text-green-600' : ''">
                                        {{ cart.shipping_cost === 0 ? 'Gratuite' : cart.shipping_cost.toFixed(2) + ' DT' }}
                                    </span>
                                </div>
                                <div v-if="cart.shipping_cost === 0" class="text-xs text-green-600">
                                    🎉 Livraison gratuite pour les commandes > 100 DT
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-xl font-bold text-gray-900">
                                        <span>Total</span>
                                        <span class="text-indigo-600">{{ cart.total.toFixed(2) }} DT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-6 space-y-3">
                                <div class="flex items-center space-x-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Paiement sécurisé</span>
                                </div>
                                <div class="flex items-center space-x-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Livraison rapide</span>
                                </div>
                                <div class="flex items-center space-x-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Service client 24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
