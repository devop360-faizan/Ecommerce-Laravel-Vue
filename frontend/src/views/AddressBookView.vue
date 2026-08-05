<template>
  <div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-4 md:gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="md:col-span-1 mb-8 md:mb-0">
          <nav class="space-y-2">
            <router-link to="/profile" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Profile Details
            </router-link>
            <router-link to="/wishlist" class="text-gray-900 hover:bg-gray-50 hover:text-gray-900 group border-l-4 border-transparent px-3 py-2 flex items-center text-sm font-medium">
              Wishlist
            </router-link>
            <a href="#" class="bg-white text-indigo-700 hover:bg-gray-50 group border-l-4 border-indigo-700 px-3 py-2 flex items-center text-sm font-medium">
              Address Book
            </a>
          </nav>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-3">
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Saved Addresses</h3>
              <button @click="showForm = !showForm" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                Add New Address
              </button>
            </div>
            
            <!-- Add Address Form -->
            <div v-if="showForm" class="px-4 py-5 sm:p-6 border-b border-gray-200 bg-gray-50">
              <form @submit.prevent="saveAddress" class="space-y-4">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                  <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700">Address Line 1</label>
                    <div class="mt-1">
                      <input type="text" v-model="form.address_line_1" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" />
                    </div>
                  </div>
                  <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <div class="mt-1">
                      <input type="text" v-model="form.city" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" />
                    </div>
                  </div>
                  <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">State / Province</label>
                    <div class="mt-1">
                      <input type="text" v-model="form.state" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" />
                    </div>
                  </div>
                  <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">ZIP / Postal Code</label>
                    <div class="mt-1">
                      <input type="text" v-model="form.postal_code" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" />
                    </div>
                  </div>
                  <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Country</label>
                    <div class="mt-1">
                      <input type="text" v-model="form.country" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3 border" />
                    </div>
                  </div>
                </div>
                <div class="flex items-center">
                  <input id="default" type="checkbox" v-model="form.is_default" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                  <label for="default" class="ml-2 block text-sm text-gray-900">Set as default address</label>
                </div>
                <div class="flex justify-end space-x-3">
                  <button type="button" @click="showForm = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">Cancel</button>
                  <button type="submit" :disabled="saving" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">Save Address</button>
                </div>
              </form>
            </div>

            <!-- Address List -->
            <div v-if="loading" class="p-6 text-center text-gray-500">Loading addresses...</div>
            <div v-else-if="addresses.length === 0" class="p-6 text-center text-gray-500">You haven't saved any addresses yet.</div>
            <ul v-else role="list" class="divide-y divide-gray-200">
              <li v-for="address in addresses" :key="address.id" class="p-4 sm:p-6 flex justify-between items-center hover:bg-gray-50 transition-colors">
                <div>
                  <div class="flex items-center">
                    <p class="text-sm font-medium text-gray-900">{{ address.address_line_1 }}</p>
                    <span v-if="address.is_default" class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">Default</span>
                  </div>
                  <p class="text-sm text-gray-500 mt-1">{{ address.city }}, {{ address.state }} {{ address.postal_code }}</p>
                  <p class="text-sm text-gray-500">{{ address.country }}</p>
                </div>
                <button @click="deleteAddress(address.id)" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import api from '../services/api';

const addresses = ref<any[]>([]);
const loading = ref(true);
const showForm = ref(false);
const saving = ref(false);

const form = reactive({
  address_line_1: '',
  city: '',
  state: '',
  postal_code: '',
  country: '',
  is_default: false
});

const fetchAddresses = async () => {
  try {
    const response = await api.get('/addresses');
    addresses.value = response.data.data;
  } catch (error) {
    console.error('Failed to load addresses', error);
  } finally {
    loading.value = false;
  }
};

const saveAddress = async () => {
  saving.value = true;
  try {
    await api.post('/addresses', form);
    showForm.value = false;
    form.address_line_1 = ''; form.city = ''; form.state = ''; form.postal_code = ''; form.country = ''; form.is_default = false;
    fetchAddresses();
  } catch (error) {
    console.error('Failed to save address', error);
  } finally {
    saving.value = false;
  }
};

const deleteAddress = async (id: number) => {
  if (!confirm('Are you sure you want to delete this address?')) return;
  try {
    await api.delete(`/addresses/${id}`);
    fetchAddresses();
  } catch (error) {
    console.error('Failed to delete address', error);
  }
};

onMounted(() => {
  fetchAddresses();
});
</script>
