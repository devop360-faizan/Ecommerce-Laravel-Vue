<template>
  <div class="h-screen flex overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    <div class="flex flex-col w-64 bg-gray-900 border-r border-gray-800">
      <div class="flex items-center justify-center h-16 bg-gray-900 border-b border-gray-800">
        <span class="text-white font-bold text-xl tracking-wider">ElectroShop Admin</span>
      </div>
      <div class="overflow-y-auto overflow-x-hidden flex-grow">
        <ul class="flex flex-col py-4 space-y-1">
          <li>
            <router-link to="/admin" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6 pl-4 transition-colors">
              <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
            </router-link>
          </li>
          <li>
            <router-link to="/admin/products" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6 pl-4 transition-colors">
              <span class="ml-2 text-sm tracking-wide truncate">Products</span>
            </router-link>
          </li>
          <li>
            <router-link to="/admin/orders" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-300 hover:text-white border-l-4 border-transparent hover:border-indigo-500 pr-6 pl-4 transition-colors">
              <span class="ml-2 text-sm tracking-wide truncate">Orders</span>
            </router-link>
          </li>
          <li>
            <router-link to="/" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-900 text-gray-300 hover:text-white border-l-4 border-transparent hover:border-red-500 pr-6 pl-4 mt-8 transition-colors">
              <span class="ml-2 text-sm tracking-wide truncate">Exit Admin</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <!-- Top header -->
      <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
        <h2 class="text-xl font-semibold text-gray-800">{{ currentRouteName }}</h2>
        <div class="flex items-center">
          <span class="text-sm font-medium text-gray-600 mr-2">Admin User</span>
          <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
            A
          </div>
        </div>
      </header>

      <!-- Main content area -->
      <main class="flex-1 overflow-auto bg-gray-50 p-6">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const currentRouteName = computed(() => route.meta.title || route.name || 'Admin');
</script>
