<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    auth: Object,
    product: Object,
    categories: Array,
});

const form = useForm({
    name: props.product.name,
    slug: props.product.slug,
    description: props.product.description || '',
    price: props.product.price,
    stock: props.product.stock,
    category_id: props.product.category_id,
    image: null,
    _method: 'PUT',
});

const imagePreview = ref(null);

const generateSlug = () => {
    form.slug = form.name
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
};

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
};

const submit = () => {
    form.post(route('admin.products.update', props.product.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout :auth="auth">
        <Head title="Edit Product" />

        <template #header>
            Products
        </template>

        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <Link :href="route('admin.products.index')" class="hover:text-gray-700">
                    Products
                </Link>
                <span>/</span>
                <span class="text-gray-900">Edit Product</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-6">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Product Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            @blur="generateSlug"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.name }"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Slug -->
                    <div class="sm:col-span-2">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                            Slug <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="slug"
                            v-model="form.slug"
                            type="text"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.slug }"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            URL-friendly version of the name. Auto-generated from product name.
                        </p>
                        <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">
                            {{ form.errors.slug }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.description }"
                        />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.category_id }"
                            required
                        >
                            <option value="">Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                            {{ form.errors.category_id }}
                        </p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Price (DT) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                id="price"
                                v-model="form.price"
                                type="number"
                                step="0.01"
                                min="0"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12"
                                :class="{ 'border-red-500': form.errors.price }"
                                required
                            />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">DT</span>
                            </div>
                        </div>
                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">
                            {{ form.errors.price }}
                        </p>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="stock"
                            v-model="form.stock"
                            type="number"
                            min="0"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-500': form.errors.stock }"
                            required
                        />
                        <p v-if="form.errors.stock" class="mt-1 text-sm text-red-600">
                            {{ form.errors.stock }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Update Image (optional)
                    </label>

                    <div v-if="!imagePreview" class="mt-1">
                        <label
                            for="image"
                            class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-indigo-400 transition-colors"
                        >
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <span class="relative font-medium text-indigo-600 hover:text-indigo-500">
                                        Upload a new image
                                    </span>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </label>
                        <input
                            id="image"
                            type="file"
                            accept="image/*"
                            @change="handleImageChange"
                            class="sr-only"
                        />
                    </div>

                    <!-- Image Preview -->
                    <div v-else class="mt-2 relative">
                        <img :src="imagePreview" alt="Preview" class="max-w-xs rounded-lg border border-gray-300" />
                        <button
                            @click="removeImage"
                            type="button"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                        {{ form.errors.image }}
                    </p>
                    <p class="mt-2 text-xs text-gray-500">
                        Leave empty to keep the current image
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <Link
                    :href="route('admin.products.index')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Updating...' : 'Update Product' }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
