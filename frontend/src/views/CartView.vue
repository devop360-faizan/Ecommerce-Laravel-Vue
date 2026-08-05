<template>
  <div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
        Shopping Cart
      </h1>

      <div v-if="cartStore.items.length === 0" class="mt-12 text-center">
        <p class="text-xl text-gray-500">Your cart is empty.</p>
        <router-link
          to="/shop"
          class="mt-4 inline-block text-indigo-600 hover:text-indigo-500 font-medium"
        >
          Go back to shopping &rarr;
        </router-link>
      </div>

      <div
        v-else
        class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start"
      >
        <!-- Cart Items List -->
        <section class="lg:col-span-7">
          <ul
            role="list"
            class="border-t border-b border-gray-200 divide-y divide-gray-200"
          >
            <li
              v-for="item in cartStore.items"
              :key="item.id"
              class="flex py-6 sm:py-10"
            >
              <div class="flex-shrink-0">
                <img
                  :src="item.image_url"
                  :alt="item.name"
                  class="w-24 h-24 rounded-md object-center object-cover sm:w-48 sm:h-48"
                />
              </div>

              <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                <div
                  class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0"
                >
                  <div>
                    <div class="flex justify-between">
                      <h3 class="text-sm">
                        <a
                          href="#"
                          class="font-medium text-gray-700 hover:text-gray-800"
                        >
                          {{ item.name }}
                        </a>
                      </h3>
                    </div>
                    <p class="mt-1 text-sm font-medium text-gray-900">
                      ${{ item.price }}
                    </p>
                  </div>

                  <div class="mt-4 sm:mt-0 sm:pr-9">
                    <label :for="`quantity-${item.id}`" class="sr-only"
                      >Quantity, {{ item.name }}</label
                    >
                    <div class="flex items-center text-sm text-gray-500">
                      Qty: {{ item.quantity }}
                    </div>

                    <div class="absolute top-0 right-0">
                      <button
                        @click="cartStore.removeFromCart(item.id)"
                        type="button"
                        class="-m-2 p-2 inline-flex text-gray-400 hover:text-red-500"
                      >
                        <span class="sr-only">Remove</span>
                        <svg
                          class="h-5 w-5"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </section>

        <!-- Order Summary -->
        <section
          class="mt-16 bg-gray-50 rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5"
        >
          <h2 id="summary-heading" class="text-lg font-medium text-gray-900">
            Order summary
          </h2>

          <dl class="mt-6 space-y-4">
            <div
              class="flex items-center justify-between border-t border-gray-200 pt-4"
            >
              <dt class="text-base font-medium text-gray-900">Order total</dt>
              <dd class="text-base font-medium text-gray-900">
                ${{ cartStore.totalPrice.toFixed(2) }}
              </dd>
            </div>
          </dl>

          <div class="mt-6">
            <button
              @click="proceedToCheckout"
              :disabled="loading"
              class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-50 disabled:opacity-50"
            >
              <span v-if="loading">Processing...</span>
              <span v-else>Checkout</span>
            </button>
            <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useCartStore } from "../stores/cart";
import { useAuthStore } from "../stores/auth";
import api from "../services/api";

const cartStore = useCartStore();
const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const error = ref("");

const proceedToCheckout = async () => {
  error.value = "";

  // Agar user login nahi hai, toh pehle usay login page par bhej do
  if (!authStore.isAuthenticated) {
    router.push("/login");
    return;
  }

  loading.value = true;

  try {
    // 1. Sync local cart items to backend cart
    await api.delete('/cart'); // clear existing
    for (const item of cartStore.items) {
      await api.post('/cart', { product_id: item.id, quantity: item.quantity });
    }

    // 2. Create a dummy address for checkout since we don't have an address UI yet
    const addressRes = await api.post('/addresses', {
      address_line_1: '123 Main St',
      city: 'New York',
      state: 'NY',
      postal_code: '10001',
      country: 'USA',
      is_default: true
    });
    const address_id = addressRes.data.data.id;

    // 3. Place the order
    const response = await api.post('/orders', { 
      address_id: address_id,
      payment_method: 'stripe'
    });
    
    // Redirect to Stripe Checkout
    if (response.data.data.payment_url) {
      window.location.href = response.data.data.payment_url;
    } else {
      error.value = "Checkout link not found.";
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || "Failed to process checkout.";
  } finally {
    loading.value = false;
  }
};
</script>
