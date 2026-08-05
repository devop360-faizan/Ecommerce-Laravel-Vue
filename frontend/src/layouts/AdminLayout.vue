<template>
  <div class="h-screen flex overflow-hidden bg-gray-100 font-sans antialiased">
    
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0">
      <div class="flex flex-col w-64">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex flex-col h-0 flex-1 bg-gray-900 shadow-xl">
          <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4 mb-6">
              <span class="text-2xl font-extrabold text-white tracking-tight">ElectroShop <span class="text-indigo-500">Admin</span></span>
            </div>
            <nav class="mt-5 flex-1 px-2 space-y-2">
              <router-link to="/admin" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
              </router-link>
              <router-link to="/admin/orders" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Orders
              </router-link>
              <router-link to="/admin/products" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Products
              </router-link>
              <router-link to="/admin/categories" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Categories
              </router-link>
              <router-link to="/admin/brands" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Brands
              </router-link>
              <router-link to="/admin/users" exact-active-class="bg-gray-800 text-white" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-3 text-sm font-medium rounded-lg transition-colors">
                <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Customers
              </router-link>
            </nav>
          </div>
          <div class="flex-shrink-0 flex bg-gray-800 p-4">
            <router-link to="/" class="flex-shrink-0 w-full group block text-gray-300 hover:text-white">
              <div class="flex items-center">
                <svg class="inline-block h-6 w-6 rounded-full" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm-1-9a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1zm0 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                  <p class="text-sm font-medium">Exit Admin Panel</p>
                  <p class="text-xs font-medium text-gray-400 group-hover:text-gray-300">Return to Shop</p>
                </div>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      
      <!-- Top header -->
      <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm border-b border-gray-200 lg:hidden">
        <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
          <span class="sr-only">Open sidebar</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </button>
        <div class="flex-1 px-4 flex justify-between">
          <div class="flex-1 flex items-center">
            <span class="text-xl font-bold text-gray-900">Admin</span>
          </div>
        </div>
      </div>

      <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-gray-50">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ route.meta.title || 'Dashboard' }}</h1>
          </div>
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mt-6">
            <router-view></router-view>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
const route = useRoute();
</script>
