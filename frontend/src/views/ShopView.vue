<template>
  <div class="bg-gray-50 min-h-screen pt-8 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 flex-shrink-0 space-y-8">
          
          <!-- Search Box -->
          <div class="bg-white p-5 shadow-sm rounded-xl border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Search</h3>
            <div class="relative">
              <input 
                type="text" 
                v-model="filters.q" 
                @input="debouncedSearch"
                placeholder="Search products..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Categories Filter -->
          <div class="bg-white p-5 shadow-sm rounded-xl border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
            <div class="space-y-3">
              <div class="flex items-center">
                <input type="radio" id="cat-all" value="" v-model="filters.category_id" @change="fetchProducts" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" />
                <label for="cat-all" class="ml-3 text-sm text-gray-700">All Categories</label>
              </div>
              <div v-for="category in categories" :key="category.id" class="flex items-center">
                <input type="radio" :id="`cat-${category.id}`" :value="category.id" v-model="filters.category_id" @change="fetchProducts" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" />
                <label :for="`cat-${category.id}`" class="ml-3 text-sm text-gray-700">{{ category.name }}</label>
              </div>
            </div>
          </div>

          <!-- Brands Filter -->
          <div class="bg-white p-5 shadow-sm rounded-xl border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Brands</h3>
            <div class="space-y-3">
              <div class="flex items-center">
                <input type="radio" id="brand-all" value="" v-model="filters.brand_id" @change="fetchProducts" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" />
                <label for="brand-all" class="ml-3 text-sm text-gray-700">All Brands</label>
              </div>
              <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                <input type="radio" :id="`brand-${brand.id}`" :value="brand.id" v-model="filters.brand_id" @change="fetchProducts" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" />
                <label :for="`brand-${brand.id}`" class="ml-3 text-sm text-gray-700">{{ brand.name }}</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-gray-900">Shop Products</h1>
            <span class="text-sm text-gray-500">{{ products.length }} results</span>
          </div>

          <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="i in 6" :key="i" class="bg-white rounded-2xl shadow-sm h-80 animate-pulse"></div>
          </div>
          
          <div v-else-if="error" class="bg-red-50 text-red-600 p-4 rounded-xl text-center">
            {{ error }}
          </div>
          
          <div v-else-if="products.length === 0" class="bg-white p-12 text-center rounded-xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filters.</p>
            <div class="mt-6">
              <button @click="resetFilters" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Clear Filters
              </button>
            </div>
          </div>
          
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <ProductCard 
              v-for="product in products" 
              :key="product.id" 
              :product="product" 
            />
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import api from '../services/api';
import ProductCard from '../components/ProductCard.vue';

const products = ref<any[]>([]);
const categories = ref<any[]>([]);
const brands = ref<any[]>([]);

const loading = ref(true);
const error = ref('');

const filters = reactive({
  q: '',
  category_id: '',
  brand_id: ''
});

let searchTimeout: any = null;

const debouncedSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchProducts();
  }, 500);
};

const fetchCategories = async () => {
  try {
    const response = await api.get('/shop/categories');
    categories.value = response.data.data;
  } catch (err) {
    console.error(err);
  }
};

const fetchBrands = async () => {
  try {
    const response = await api.get('/shop/brands');
    brands.value = response.data.data;
  } catch (err) {
    console.error(err);
  }
};

const fetchProducts = async () => {
  loading.value = true;
  try {
    let url = '/shop/search?';
    if (filters.q) url += `q=${encodeURIComponent(filters.q)}&`;
    if (filters.category_id) url += `category_id=${filters.category_id}&`;
    if (filters.brand_id) url += `brand_id=${filters.brand_id}&`;
    
    const response = await api.get(url);
    products.value = response.data.data.data; // Pagination layer
    error.value = '';
  } catch (err) {
    error.value = 'Failed to load products. Please try again later.';
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.q = '';
  filters.category_id = '';
  filters.brand_id = '';
  fetchProducts();
};

onMounted(() => {
  fetchCategories();
  fetchBrands();
  fetchProducts();
});
</script>
