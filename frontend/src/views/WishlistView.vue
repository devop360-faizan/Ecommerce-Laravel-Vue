<template>
  <div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-4 md:gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="md:col-span-1 mb-8 md:mb-0">
          <nav class="space-y-2">
            <router-link to="/profile" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Profile Details
            </router-link>
            <a href="#" class="bg-white text-indigo-700 hover:bg-gray-50 group border-l-4 border-indigo-700 px-3 py-2 flex items-center text-sm font-medium">
              Wishlist
            </a>
            <router-link to="/addresses" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Address Book
            </router-link>
          </nav>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-3">
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">My Wishlist</h3>
            </div>
            
            <div v-if="loading" class="p-6 text-center text-gray-500">Loading wishlist...</div>
            <div v-else-if="wishlists.length === 0" class="p-6 text-center text-gray-500">Your wishlist is empty.</div>
            
            <div v-else class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="item in wishlists" :key="item.id" class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 h-48">
                  <img :src="item.product.images?.length > 0 ? item.product.images[0].url : 'https://via.placeholder.com/400'" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1 p-4 space-y-2 flex flex-col">
                  <h3 class="text-sm font-medium text-gray-900 truncate">
                    <router-link :to="`/product/${item.product.slug}`">{{ item.product.name }}</router-link>
                  </h3>
                  <div class="flex-1 flex flex-col justify-end">
                    <p class="text-base font-medium text-gray-900">${{ item.product.price }}</p>
                    <div class="mt-4 flex space-x-2">
                      <button @click="cartStore.addToCart(item.product)" class="flex-1 bg-indigo-600 rounded-md py-2 px-2 text-xs font-medium text-white hover:bg-indigo-700 text-center">Add to Cart</button>
                      <button @click="removeWishlist(item.product_id)" class="bg-red-50 text-red-600 rounded-md py-2 px-3 hover:bg-red-100 flex items-center justify-center">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../services/api';
import { useCartStore } from '../stores/cart';

const wishlists = ref<any[]>([]);
const loading = ref(true);
const cartStore = useCartStore();

const fetchWishlists = async () => {
  try {
    const response = await api.get('/wishlists');
    wishlists.value = response.data.data;
  } catch (error) {
    console.error('Failed to load wishlist', error);
  } finally {
    loading.value = false;
  }
};

const removeWishlist = async (productId: number) => {
  try {
    await api.delete(`/wishlists/${productId}`);
    fetchWishlists();
  } catch (error) {
    console.error('Failed to remove from wishlist', error);
  }
};

onMounted(() => {
  fetchWishlists();
});
</script>
