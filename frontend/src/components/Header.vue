<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <router-link to="/" class="text-2xl font-bold text-indigo-600">
            ElectroShop
          </router-link>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden sm:flex sm:space-x-8">
          <router-link
            to="/"
            class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-indigo-500 text-sm font-medium transition-colors"
          >
            Home
          </router-link>
          <router-link
            to="/shop"
            class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-indigo-500 text-sm font-medium transition-colors"
            >Shop
          </router-link>
        </div>

        <!-- Right Side Icons (Cart & Account) -->
        <div class="flex items-center space-x-4">
          <!-- Cart Icon -->
          <router-link
            to="/cart"
            class="text-gray-500 hover:text-indigo-600 relative p-2 transition-colors"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
              />
            </svg>
            <span
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            >
              {{ cartStore.totalItems }}
            </span>
          </router-link>

          <!-- Auth Links -->
          <template v-if="authStore.isAuthenticated">
            <router-link
              v-if="authStore.isAdmin"
              to="/admin"
              class="text-sm font-medium text-indigo-700 bg-indigo-50 px-3 py-1 rounded-md hover:bg-indigo-100 transition-colors"
            >
              Admin Panel
            </router-link>
            <span class="text-sm font-medium text-gray-700">
              Hi, {{ authStore.user?.first_name }}
            </span>
            <button
              @click="handleLogout"
              class="text-sm font-medium text-gray-500 hover:text-red-600 transition-colors"
            >
              Logout
            </button>
          </template>
          <template v-else>
            <router-link
              to="/login"
              class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors"
            >
              Login
            </router-link>
          </template>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { useCartStore } from '../stores/cart';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const cartStore = useCartStore();
const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};
</script>
