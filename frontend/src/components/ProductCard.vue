<template>
  <div class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none sm:h-64">
      <img 
        :src="product.images && product.images.length > 0 ? product.images[0].url : 'https://via.placeholder.com/400?text=No+Image'" 
        :alt="product.name" 
        class="w-full h-full object-center object-cover sm:w-full sm:h-full" 
      />
    </div>
    <div class="flex-1 p-4 space-y-2 flex flex-col">
      <h3 class="text-sm font-medium text-gray-900">
        <router-link :to="`/product/${product.slug}`">
          <span aria-hidden="true" class="absolute inset-0"></span>
          {{ product.name }}
        </router-link>
      </h3>
      <p class="text-sm text-gray-500">{{ product.brand?.name || 'Generic' }}</p>
      <div class="flex-1 flex flex-col justify-end">
        <p class="text-base font-medium text-gray-900">${{ product.price }}</p>
        <button 
          @click.prevent="cartStore.addToCart(product)"
          class="mt-4 w-full bg-indigo-600 border border-transparent rounded-md py-2 px-4 flex items-center justify-center text-sm font-medium text-white hover:bg-indigo-700 relative z-10"
        >
          Add to bag
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '../stores/cart';

const cartStore = useCartStore();

defineProps<{
  product: any
}>()
</script>
