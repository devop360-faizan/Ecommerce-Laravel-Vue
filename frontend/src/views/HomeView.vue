<template>
  <div>
    <!-- Hero Banner -->
    <div class="relative bg-indigo-900 overflow-hidden">
      <div class="max-w-7xl mx-auto">
        <div
          class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 bg-indigo-900"
        >
          <main
            class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28"
          >
            <div class="sm:text-center lg:text-left pt-12">
              <h1
                class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl"
              >
                <span class="block xl:inline">Premium Electronics</span>
                <span class="block text-indigo-400">For Your Lifestyle</span>
              </h1>
              <p
                class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0"
              >
                Discover the latest gadgets, smartphones, and accessories.
                Unbeatable prices and top-notch quality.
              </p>
              <div
                class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
              >
                <div class="rounded-md shadow">
                  <router-link
                    to="/shop"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-900 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition-colors"
                  >
                    Shop Now
                  </router-link>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>

    <!-- Featured Products Section (Placeholder for now) -->
    <div class="bg-white">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-extrabold text-gray-900">
            Featured Products
          </h2>
          <p class="mt-4 text-lg text-gray-500">
            Check out our most popular items.
          </p>
        </div>

        <div class="mt-12 text-center text-gray-500">
          Loading products...
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>
