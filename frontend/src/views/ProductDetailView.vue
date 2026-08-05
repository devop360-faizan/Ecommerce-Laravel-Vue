<template>
  <div class="bg-gray-50 min-h-screen">
    <div class="pt-6">
      
      <div v-if="loading" class="mt-12 text-center text-gray-500">
        <div class="animate-pulse flex flex-col items-center">
          <div class="h-64 w-64 bg-gray-200 rounded-lg mb-4"></div>
          <div class="h-8 w-48 bg-gray-200 rounded mb-2"></div>
          <div class="h-4 w-32 bg-gray-200 rounded"></div>
        </div>
      </div>
      
      <div v-else-if="error" class="mt-12 text-center text-red-500 bg-red-50 p-4 rounded-md max-w-2xl mx-auto">
        {{ error }}
      </div>

      <!-- Product UI -->
      <div v-else-if="product" class="max-w-7xl mx-auto pt-10 pb-16 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-x-8 p-8">
          
          <!-- Image gallery -->
          <div class="lg:row-span-3">
            <div class="aspect-w-3 aspect-h-4 rounded-xl overflow-hidden bg-gray-100">
              <img 
                :src="product.images && product.images.length > 0 ? product.images[0].url : 'https://via.placeholder.com/600?text=No+Image'" 
                :alt="product.name" 
                class="w-full h-full object-center object-cover hover:scale-105 transition-transform duration-500" 
              />
            </div>
          </div>

          <!-- Product info -->
          <div class="mt-10 px-4 sm:px-0 lg:mt-0 flex flex-col">
            <div class="flex justify-between items-start">
              <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">{{ product.name }}</h1>
              <button @click="toggleWishlist" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                <svg class="h-8 w-8" :class="{'text-red-500 fill-current': inWishlist}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </button>
            </div>
            
            <div class="mt-4">
              <p class="text-3xl font-bold text-indigo-600">${{ product.price }}</p>
            </div>

            <div class="mt-6 flex-1">
              <div class="text-base text-gray-700 space-y-6 leading-relaxed" v-html="product.description"></div>
            </div>

            <div class="mt-10 border-t border-gray-200 pt-8">
              <button 
                @click="cartStore.addToCart(product)"
                type="button" 
                class="w-full bg-indigo-600 border border-transparent rounded-xl py-4 px-8 flex items-center justify-center text-lg font-bold text-white hover:bg-indigo-700 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-16 bg-white shadow-lg rounded-2xl p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-8">Customer Reviews</h2>
          
          <div v-if="authStore.isAuthenticated" class="mb-10 bg-gray-50 p-6 rounded-xl border border-gray-100">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Write a review</h3>
            <form @submit.prevent="submitReview">
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select v-model="reviewForm.rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                  <option value="5">5 Stars - Excellent</option>
                  <option value="4">4 Stars - Good</option>
                  <option value="3">3 Stars - Average</option>
                  <option value="2">2 Stars - Poor</option>
                  <option value="1">1 Star - Terrible</option>
                </select>
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                <textarea v-model="reviewForm.comment" rows="3" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md p-2"></textarea>
              </div>
              <button type="submit" :disabled="submittingReview" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-medium hover:bg-indigo-700 transition-colors">Submit Review</button>
            </form>
          </div>
          
          <div v-if="loadingReviews" class="text-gray-500">Loading reviews...</div>
          <div v-else-if="reviews.length === 0" class="text-gray-500 italic">No reviews yet. Be the first to review!</div>
          
          <div v-else class="space-y-8">
            <div v-for="review in reviews" :key="review.id" class="border-b border-gray-200 pb-8 last:border-0 last:pb-0">
              <div class="flex items-center mb-2">
                <div class="flex items-center">
                  <svg v-for="i in 5" :key="i" class="h-5 w-5 flex-shrink-0" :class="[review.rating >= i ? 'text-yellow-400' : 'text-gray-300']" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <p class="ml-3 text-sm text-gray-700 font-medium">{{ review.user?.name || 'Anonymous' }}</p>
                <span class="mx-2 text-gray-300">&middot;</span>
                <p class="text-sm text-gray-500">{{ new Date(review.created_at).toLocaleDateString() }}</p>
              </div>
              <div class="mt-4 text-base text-gray-700 italic">
                "{{ review.comment }}"
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useCartStore } from '../stores/cart';
import { useAuthStore } from '../stores/auth';
import api from '../services/api';

const route = useRoute();
const cartStore = useCartStore();
const authStore = useAuthStore();

const product = ref<any>(null);
const loading = ref(true);
const error = ref('');

const reviews = ref<any[]>([]);
const loadingReviews = ref(true);
const reviewForm = reactive({ rating: 5, comment: '' });
const submittingReview = ref(false);

const inWishlist = ref(false);

const fetchProductDetails = async () => {
  try {
    const response = await api.get(`/shop/products/${route.params.slug}`);
    product.value = response.data.data;
    
    // After getting product, get reviews and check wishlist status
    if (product.value) {
      fetchReviews(product.value.id);
      if (authStore.isAuthenticated) {
        checkWishlist(product.value.id);
      }
    }
  } catch (err) {
    error.value = 'Failed to load product details.';
  } finally {
    loading.value = false;
  }
};

const fetchReviews = async (productId: number) => {
  try {
    const response = await api.get(`/reviews?product_id=${productId}`);
    reviews.value = response.data.data;
  } catch (err) {
    console.error('Failed to load reviews', err);
  } finally {
    loadingReviews.value = false;
  }
};

const submitReview = async () => {
  if (!product.value) return;
  submittingReview.value = true;
  try {
    await api.post('/reviews', {
      product_id: product.value.id,
      rating: reviewForm.rating,
      comment: reviewForm.comment
    });
    reviewForm.comment = '';
    reviewForm.rating = 5;
    fetchReviews(product.value.id);
  } catch (err) {
    console.error('Failed to submit review', err);
    alert('Failed to submit review');
  } finally {
    submittingReview.value = false;
  }
};

const checkWishlist = async (productId: number) => {
  try {
    const response = await api.get('/wishlists');
    const items = response.data.data;
    inWishlist.value = items.some((item: any) => item.product_id === productId);
  } catch (err) {
    console.error('Failed to check wishlist', err);
  }
};

const toggleWishlist = async () => {
  if (!authStore.isAuthenticated) {
    alert("Please login to add to wishlist");
    return;
  }
  if (!product.value) return;
  
  try {
    if (inWishlist.value) {
      await api.delete(`/wishlists/${product.value.id}`);
      inWishlist.value = false;
    } else {
      await api.post('/wishlists', { product_id: product.value.id });
      inWishlist.value = true;
    }
  } catch (err) {
    console.error('Failed to toggle wishlist', err);
  }
};

onMounted(() => {
  fetchProductDetails();
});
</script>
