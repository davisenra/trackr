<script setup lang="ts">
import GuestLayout from "@/layouts/GuestLayout.vue";
</script>

<template>
  <GuestLayout>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-3 px-6 md:px-0" action="#" method="POST">
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
          <div class="mt-1">
            <input
              id="name"
              name="name"
              type="text"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
            />
          </div>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
            Email address
          </label>
          <div class="mt-1">
            <input
              id="email"
              name="email"
              type="email"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
            />
          </div>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">
            Password
          </label>
          <div class="mt-1">
            <input
              id="password"
              name="password"
              type="password"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
            />
          </div>
        </div>
        <div>
          <label
            for="password_confirmation"
            class="block text-sm font-medium leading-6 text-gray-900"
          >
            Confirm password
          </label>
          <div class="mt-1">
            <input
              id="password_confirmation"
              name="password_confirmation"
              type="password"
              required
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="flex w-full justify-center rounded-md bg-yellow-400 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600"
          >
            Sign up
          </button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Already a member?
        <router-link to="login" class="font-semibold leading-6 text-amber-500 hover:text-amber-600">
          Sign in
        </router-link>
      </p>
    </div>
  </GuestLayout>
</template>
