<template>
  <div class="bg-white">
    <div class="pt-6">
      
      <div v-if="loading" class="mt-12 text-center text-gray-500">
        Loading product details...
      </div>
      
      <div v-else-if="error" class="mt-12 text-center text-red-500">
        {{ error }}
      </div>

      <!-- Product UI -->
      <div v-else-if="product" class="max-w-2xl mx-auto pt-10 pb-16 px-4 sm:px-6 lg:max-w-7xl lg:pt-16 lg:pb-24 lg:px-8 lg:grid lg:grid-cols-2 lg:gap-x-8">
        
        <!-- Image gallery -->
        <div class="mt-4 lg:mt-0 lg:row-span-3">
          <h2 class="sr-only">Images</h2>
          <div class="aspect-w-3 aspect-h-4 rounded-lg overflow-hidden lg:block">
            <img 
              :src="product.images && product.images.length > 0 ? product.images[0].url : 'https://via.placeholder.com/600?text=No+Image'" 
              :alt="product.name" 
              class="w-full h-full object-center object-cover" 
            />
          </div>
        </div>

        <!-- Product info -->
        <div class="mt-10 px-4 sm:px-0 lg:mt-0">
          <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ product.name }}</h1>
          
          <div class="mt-3">
            <h2 class="sr-only">Product information</h2>
            <p class="text-3xl text-gray-900">${{ product.price }}</p>
          </div>

          <div class="mt-6">
            <h3 class="sr-only">Description</h3>
            <div class="text-base text-gray-700 space-y-6" v-html="product.description"></div>
          </div>

          <!-- Add to Cart Form -->
          <div class="mt-10 flex sm:flex-col1">
            <button 
              @click="cartStore.addToCart(product)"
              type="button" 
              class="max-w-xs flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-full"
            >
              Add to bag
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useCartStore } from '../stores/cart';
import api from '../services/api';

const route = useRoute();
const cartStore = useCartStore();
const product = ref<any>(null);
const loading = ref(true);
const error = ref('');

const fetchProductDetails = async () => {
  try {
    const response = await api.get(`/shop/products/${route.params.slug}`);
    product.value = response.data.data;
  } catch (err) {
    error.value = 'Failed to load product details.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProductDetails();
});
</script>
