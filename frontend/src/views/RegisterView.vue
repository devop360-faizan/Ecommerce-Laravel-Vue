<template>
  <div
    class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
      <div>
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
          Create a New Account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Or if you already have an account
          <router-link
            to="/login"
            class="font-medium text-indigo-600 hover:text-indigo-500"
          >
            Sign in
          </router-link>
        </p>
      </div>

      <!-- Register Form -->
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <!-- Error Message -->
        <div
          v-if="error"
          class="bg-red-50 text-red-500 p-3 rounded text-sm text-center"
        >
          {{ error }}
        </div>

        <div class="rounded-md shadow-sm space-y-4">
          <!-- First Name -->
          <div>
            <label for="first_name" class="sr-only">First Name</label>
            <input
              id="first_name"
              type="text"
              required
              v-model="form.first_name"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="First Name"
            />
          </div>

          <!-- Last Name -->
          <div>
            <label for="last_name" class="sr-only">Last Name</label>
            <input
              id="last_name"
              type="text"
              required
              v-model="form.last_name"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Last Name"
            />
          </div>

          <!-- Email -->
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input
              id="email-address"
              type="email"
              required
              v-model="form.email"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Email address"
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              type="password"
              required
              v-model="form.password"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Password"
            />
          </div>

          <!-- Password Confirmation -->
          <div>
            <label for="password_confirmation" class="sr-only"
              >Confirm Password</label
            >
            <input
              id="password_confirmation"
              type="password"
              required
              v-model="form.password_confirmation"
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Confirm Password"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <span v-if="loading">Loading...</span>
            <span v-else>Register</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import api from "../services/api";

const router = useRouter();

const form = reactive({
  first_name: "",
  last_name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const error = ref("");
const loading = ref(false);

const handleRegister = async () => {
  if (form.password !== form.password_confirmation) {
    error.value = "Passwords do not match!";
    return;
  }

  error.value = "";
  loading.value = true;

  try {
    const payload = {
      name: `${form.first_name} ${form.last_name}`.trim(),
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation
    };
    await api.post("/auth/register", payload);
    router.push("/login");
  } catch (err: any) {
    error.value =
      err.response?.data?.message ||
      "Registration failed. Please try again.";
  } finally {
    loading.value = false;
  }
};
</script>
