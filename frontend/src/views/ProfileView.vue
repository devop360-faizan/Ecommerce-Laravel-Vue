<template>
  <div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-4 md:gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="md:col-span-1 mb-8 md:mb-0">
          <nav class="space-y-2">
            <a href="#" class="bg-white text-indigo-700 hover:bg-gray-50 group border-l-4 border-indigo-700 px-3 py-2 flex items-center text-sm font-medium">
              Profile Details
            </a>
            <router-link to="/wishlist" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Wishlist
            </router-link>
            <router-link to="/addresses" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Address Book
            </router-link>
          </nav>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-3">
          <!-- Profile Information Card -->
          <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h3>
            </div>
            <div class="px-4 py-5 sm:p-6" v-if="authStore.user">
              <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ authStore.user.first_name }} {{ authStore.user.last_name }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Email address</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ authStore.user.email }}</dd>
                </div>
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ authStore.user.phone || 'Not provided' }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Order History -->
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Order History</h3>
            </div>
            
            <div v-if="loadingOrders" class="p-6 text-center text-gray-500">Loading orders...</div>
            <div v-else-if="orders.length === 0" class="p-6 text-center text-gray-500">No orders found.</div>
            
            <ul v-else role="list" class="divide-y divide-gray-200">
              <li v-for="order in orders" :key="order.id" class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="text-sm font-medium text-indigo-600 truncate">Order #{{ order.order_number }}</div>
                  <div class="ml-2 flex-shrink-0 flex">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                      {{ order.status }}
                    </span>
                  </div>
                </div>
                <div class="mt-2 sm:flex sm:justify-between">
                  <div class="sm:flex">
                    <p class="flex items-center text-sm text-gray-500">
                      Total: ${{ order.total_amount }}
                    </p>
                  </div>
                  <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                    <p>Placed on <time :datetime="order.created_at">{{ new Date(order.created_at).toLocaleDateString() }}</time></p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../services/api';

const authStore = useAuthStore();
const orders = ref<any[]>([]);
const loadingOrders = ref(true);

const fetchOrders = async () => {
  try {
    const response = await api.get('/orders');
    orders.value = response.data.data.data;
  } catch (error) {
    console.error('Failed to load orders', error);
  } finally {
    loadingOrders.value = false;
  }
};

onMounted(() => {
  if (authStore.isAuthenticated) {
    fetchOrders();
  }
});
</script>
