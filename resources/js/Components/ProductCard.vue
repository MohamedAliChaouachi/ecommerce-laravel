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
        <!-- Header with badges -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-xs font-semibold text-gray-700">
                    {{ product.category?.name }}
                </span>
                <span v-if="product.stock === 0" class="text-xs font-bold text-red-700">
                    Out of Stock
                </span>
                <span v-else-if="product.stock < 10 && product.stock > 0" class="text-xs font-bold text-amber-700">
                    Limited Stock
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
                    <span v-if="product.stock > 10" class="text-xs font-medium text-green-600">
                        In Stock
                    </span>
                </div>
            </div>
        </div>
    </Link>
</template>
