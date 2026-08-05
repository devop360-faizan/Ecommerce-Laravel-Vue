<template>
  <footer class="bg-gray-900 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand Info -->
        <div class="col-span-1 md:col-span-2">
          <span class="text-2xl font-bold text-white tracking-tight"
            >ElectroShop</span
          >
          <p class="mt-4 text-gray-400 text-sm max-w-sm">
            Aapki pasandeeda jagah jahan se aap behtareen electronics khareed
            sakte hain. Quality aur bharosa hamari pehchan hai.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3
            class="text-sm font-semibold text-gray-200 tracking-wider uppercase mb-4"
          >
            Quick Links
          </h3>
          <ul class="space-y-3">
            <li>
              <router-link
                to="/"
                class="text-base text-gray-400 hover:text-white transition-colors"
                >Home</router-link
              >
            </li>
            <li>
              <router-link
                to="/shop"
                class="text-base text-gray-400 hover:text-white transition-colors"
                >Shop</router-link
              >
            </li>
            <li>
              <router-link
                to="/about"
                class="text-base text-gray-400 hover:text-white transition-colors"
                >About Us</router-link
              >
            </li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div>
          <h3
            class="text-sm font-semibold text-gray-200 tracking-wider uppercase mb-4"
          >
            Customer Service
          </h3>
          <ul class="space-y-3">
            <li>
              <router-link
                to="/contact"
                class="text-base text-gray-400 hover:text-white transition-colors"
                >Contact Us</router-link
              >
            </li>
            <li>
              <router-link
                to="/privacy"
                class="text-base text-gray-400 hover:text-white transition-colors"
                >Privacy Policy</router-link
              >
            </li>
          </ul>
        </div>
      </div>

      <!-- Copyright Bottom -->
      <div
        class="mt-12 border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center"
      >
        <p class="text-base text-gray-400">
          &copy; {{ new Date().getFullYear() }} ElectroShop. All rights
          reserved.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts"></script>
