<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg bg-white">
          <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Products List</h3>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
              Add Product
            </button>
          </div>
          
          <div v-if="loading" class="p-4 text-center text-gray-500">Loading products...</div>
          <div v-else-if="error" class="p-4 text-center text-red-500">{{ error }}</div>
          
          <table v-else class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Product
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Stock
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="product in products" :key="product.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <img class="h-10 w-10 rounded-full object-cover" :src="product.images?.length > 0 ? product.images[0].url : 'https://via.placeholder.com/150'" alt="" />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ product.name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ product.slug }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ${{ product.price }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ product.stock_quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full']">
                    {{ product.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../services/api';

const products = ref<any[]>([]);
const loading = ref(true);
const error = ref('');

const fetchProducts = async () => {
  try {
    const response = await api.get('/admin/products');
    products.value = response.data.data.data; // Pagination structure
  } catch (err) {
    error.value = 'Failed to load products.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProducts();
});
</script>
