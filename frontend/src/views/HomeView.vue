<template>
  <div>
    <!-- Hero Banner Carousel -->
    <div class="relative bg-gray-900 overflow-hidden h-[70vh]">
      <div v-if="loadingBanners" class="flex items-center justify-center h-full text-white">
        Loading amazing deals...
      </div>
      <div v-else-if="banners.length > 0" class="relative h-full w-full">
        <transition-group name="fade" tag="div" class="h-full w-full relative">
          <div 
            v-for="(banner, index) in banners" 
            :key="banner.id"
            v-show="currentBannerIndex === index"
            class="absolute inset-0 w-full h-full"
          >
            <img :src="banner.image_url" :alt="banner.title" class="w-full h-full object-cover opacity-50 transition-transform duration-[10000ms] scale-105 hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
              <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight drop-shadow-2xl animate-fade-in-up">
                {{ banner.title }}
              </h1>
              <p class="mt-4 text-xl md:text-2xl text-gray-200 max-w-3xl drop-shadow-md animate-fade-in-up delay-100">
                {{ banner.subtitle || 'Discover premium electronics curated for you.' }}
              </p>
              <div class="mt-8 animate-fade-in-up delay-200">
                <router-link
                  :to="banner.link_url || '/shop'"
                  class="inline-block px-8 py-4 border border-transparent text-lg font-medium rounded-full text-gray-900 bg-white hover:bg-gray-100 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all"
                >
                  Shop Now
                </router-link>
              </div>
            </div>
          </div>
        </transition-group>
        
        <!-- Controls -->
        <button @click="prevBanner" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full p-3 text-white transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button @click="nextBanner" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full p-3 text-white transition-all">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
      </div>
      <div v-else class="flex flex-col items-center justify-center h-full px-4 text-center">
        <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight">Premium Electronics</h1>
        <p class="mt-4 text-xl text-gray-300 max-w-2xl">Upgrade your lifestyle today.</p>
        <router-link to="/shop" class="mt-8 px-8 py-4 bg-white text-gray-900 rounded-full font-bold hover:bg-gray-100 transition-all">Shop Now</router-link>
      </div>
    </div>

    <!-- Featured Products Section -->
    <div class="bg-gray-50 py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Featured Products</h2>
          <div class="mt-2 w-24 h-1 bg-indigo-600 mx-auto rounded-full"></div>
        </div>

        <div v-if="loadingProducts" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="i in 4" :key="i" class="bg-white rounded-2xl shadow-sm h-80 animate-pulse"></div>
        </div>
        
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <ProductCard 
            v-for="product in featuredProducts" 
            :key="product.id" 
            :product="product" 
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import api from '../services/api';
import ProductCard from '../components/ProductCard.vue';

const banners = ref<any[]>([]);
const loadingBanners = ref(true);
const currentBannerIndex = ref(0);
let bannerInterval: any = null;

const featuredProducts = ref<any[]>([]);
const loadingProducts = ref(true);

const fetchBanners = async () => {
  try {
    const response = await api.get('/cms/banners');
    banners.value = response.data.data;
  } catch (err) {
    console.error('Failed to load banners', err);
  } finally {
    loadingBanners.value = false;
  }
};

const fetchFeaturedProducts = async () => {
  try {
    const response = await api.get('/shop/products?featured=1&limit=4');
    featuredProducts.value = response.data.data.data.slice(0, 4); // Fallback slice just in case
  } catch (err) {
    console.error('Failed to load featured products', err);
  } finally {
    loadingProducts.value = false;
  }
};

const nextBanner = () => {
  if (banners.value.length === 0) return;
  currentBannerIndex.value = (currentBannerIndex.value + 1) % banners.value.length;
};

const prevBanner = () => {
  if (banners.value.length === 0) return;
  currentBannerIndex.value = currentBannerIndex.value === 0 ? banners.value.length - 1 : currentBannerIndex.value - 1;
};

onMounted(() => {
  fetchBanners();
  fetchFeaturedProducts();
  bannerInterval = setInterval(nextBanner, 5000);
});

onUnmounted(() => {
  if (bannerInterval) clearInterval(bannerInterval);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
