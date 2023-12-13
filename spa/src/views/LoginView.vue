<script setup lang="ts">
import GuestLayout from "@/layouts/GuestLayout.vue";
import { reactive } from "vue";
import { useAuthStore } from "@/stores/auth";
import SpinnerPlaceholder from "@/components/SpinnerPlaceholder.vue";

const authStore = useAuthStore();

const credentials = reactive({
  email: "",
  password: ""
});

const formState = reactive({
  invalidEmail: false,
  invalidPassword: false,
  error: "",
  isSubmiting: false
});

async function handleLoginAttempt() {
  try {
    formState.isSubmiting = true;

    const { status } = await authStore.attemptLogin(credentials);

    if (!status) {
      formState.error = "These credentials do not match our records.";
      formState.invalidEmail = true;
      formState.invalidPassword = true;
    }
  } finally {
    formState.isSubmiting = false;
  }
}

function clearState() {
  formState.error = "";
  formState.invalidEmail = false;
  formState.invalidPassword = false;
}
</script>

<template>
  <GuestLayout>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-3 px-6 md:px-0" method="POST" @submit.prevent="handleLoginAttempt">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
            Email address
          </label>
          <div class="mt-1">
            <input
              @input="clearState"
              v-model="credentials.email"
              id="email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              :class="{ 'ring-2 ring-red-400 focus:ring-red-400': formState.invalidEmail }"
            />
          </div>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">
            Password
          </label>
          <div class="mt-1">
            <input
              @input="clearState"
              v-model="credentials.password"
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              :class="{ 'ring-2 ring-red-400 focus:ring-red-400': formState.invalidPassword }"
            />
          </div>
        </div>
        <div v-if="formState.error">
          <p class="text-red-500 text-sm font-bold">These credentials do not match our records</p>
        </div>
        <div>
          <button
            type="submit"
            class="flex items-center w-full h-10 justify-center gap-2 rounded-md bg-yellow-400 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
          >
            <SpinnerPlaceholder v-if="formState.isSubmiting" text-size="text-xl" color="text-white" />
            <span v-else>Sign in</span>
          </button>
        </div>
      </form>
      <p class="mt-10 text-center text-sm text-gray-500">
        Not a member?
        <router-link
          to="register"
          class="font-semibold leading-6 text-amber-500 hover:text-amber-600"
        >
          Sign up
        </router-link>
      </p>
    </div>
  </GuestLayout>
</template>
