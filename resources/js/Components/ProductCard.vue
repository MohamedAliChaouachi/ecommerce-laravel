<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="route('products.show', product.slug)"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-2xl hover:border-indigo-200 transition-all duration-300"
    >
        <!-- Image Container with Badge -->
        <div class="relative aspect-square w-full overflow-hidden bg-gray-100">
            <img
                :src="product.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80'"
                :alt="product.name"
                class="h-full w-full object-cover object-center group-hover:scale-110 transition-transform duration-500"
            />
            
            <!-- Stock Badge - Top Right -->
            <div v-if="product.stock === 0" class="absolute top-3 right-3">
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 shadow-lg">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Rupture
                </span>
            </div>
            <div v-else-if="product.stock < 10 && product.stock > 0" class="absolute top-3 right-3">
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 shadow-lg">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Stock limité
                </span>
            </div>
            
            <!-- Category Badge - Top Left -->
            <div class="absolute top-3 left-3">
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-sm">
                    {{ product.category?.name }}
                </span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex flex-col flex-grow p-5">
            <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                {{ product.name }}
            </h3>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-grow">
                {{ product.description }}
            </p>
            
            <!-- Price and Status -->
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                <div class="flex flex-col">
                    <span class="text-2xl font-bold text-indigo-600">
                        {{ product.formatted_price || `${parseFloat(product.price).toFixed(2)} DT` }}
                    </span>
                </div>
                
                <div class="flex items-center">
                    <span v-if="product.stock > 10" class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        En stock
                    </span>
                </div>
            </div>
            
            <!-- Hover Arrow -->
            <div class="absolute bottom-5 right-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-indigo-600 text-white p-2 rounded-full shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
            </div>
        </div>
    </Link>
</template>
