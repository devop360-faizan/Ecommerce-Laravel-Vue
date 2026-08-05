<template>
  <div>
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="text-lg text-gray-500">Loading dashboard stats...</div>
    </div>
    
    <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
      <div class="text-sm text-red-700">{{ error }}</div>
    </div>

    <div v-else>
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Total Orders Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                  <dd class="text-2xl font-semibold text-gray-900">{{ stats.total_orders }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                  <dd class="text-2xl font-semibold text-gray-900">${{ stats.total_revenue }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Products Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                  <dd class="text-2xl font-semibold text-gray-900">{{ stats.total_products }}</dd>
                </dl>
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

const stats = ref<any>({});
const loading = ref(true);
const error = ref('');

const fetchStats = async () => {
  try {
    const response = await api.get('/admin/dashboard/stats');
    stats.value = response.data.data || { total_orders: 0, total_revenue: 0, total_products: 0 };
  } catch (err) {
    error.value = 'Failed to load dashboard statistics.';
    stats.value = { total_orders: 0, total_revenue: 0, total_products: 0 };
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchStats();
});
</script>
