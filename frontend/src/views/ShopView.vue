<template>
  <div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
        Our Products
      </h1>
      <p class="mt-4 max-w-xl text-sm text-gray-700">
        Explore our latest collection of premium electronics.
      </p>

      <div v-if="loading" class="mt-12 text-center text-gray-500">
        Loading products...
      </div>

      <div v-else-if="error" class="mt-12 text-center text-red-500">
        {{ error }}
      </div>

      <div
        v-else
        class="mt-12 grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8"
      >
        <!-- Loop through products and render a ProductCard for each -->
        <ProductCard
          v-for="product in products"
          :key="product.id"
          :product="product"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import api from "../services/api";
import ProductCard from "../components/ProductCard.vue";

const products = ref<any[]>([]);
const loading = ref(true);
const error = ref("");

const fetchProducts = async () => {
  try {
    // Calling our Laravel backend shop API
    const response = await api.get("/shop/products");
    products.value = response.data.data.data; // .data (axios) -> .data (Resource) -> .data (Pagination)
  } catch (err) {
    error.value =
      "Failed to load products. Please check if your backend is running.";
  } finally {
    loading.value = false;
  }
};

// When the component loads, fetch the products
onMounted(() => {
  fetchProducts();
});
</script>
