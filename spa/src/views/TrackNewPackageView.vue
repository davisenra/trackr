<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Icon } from "@iconify/vue";
import { computed, ref } from "vue";

const trackNewPackagePayload = ref({
  name: "",
  trackingCode: "",
  description: ""
});

const allowSubmit = computed(() => {
  return (
    trackNewPackagePayload.value.name.length > 0 &&
    trackNewPackagePayload.value.trackingCode.length === 13
  );
});
</script>

<template>
  <AppLayout>
    <div class="mx-3 my-3 flex justify-center md:mx-6 md:my-6">
      <div
        class="flex w-full max-w-7xl flex-col justify-center gap-3 space-y-2 rounded-md bg-white p-6 shadow"
      >
        <form method="POST" class="max-w-xl space-y-3">
          <div>
            <label for="package" class="block text-sm font-medium leading-6 text-gray-900">
              Package*
            </label>
            <div class="mt-1">
              <input
                v-model="trackNewPackagePayload.name"
                id="package"
                name="package"
                type="text"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              />
            </div>
          </div>
          <div>
            <label for="tracking-code" class="block text-sm font-medium leading-6 text-gray-900">
              Tracking code*
            </label>
            <div class="mt-1">
              <input
                v-model="trackNewPackagePayload.trackingCode"
                id="tracking-code"
                name="tracking-code"
                type="text"
                required
                minlength="13"
                maxlength="13"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              />
            </div>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">
              Description
            </label>
            <div class="mt-1">
              <textarea
                v-model="trackNewPackagePayload.description"
                id="description"
                name="description"
                type="text"
                maxlength="255"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              ></textarea>
            </div>
          </div>
          <p class="text-xs text-gray-400">* Required fields</p>
          <button
            type="submit"
            class="inline-flex w-max items-center gap-1 rounded-md border border-green-500 px-3 py-1 text-lg text-green-500 transition-all enabled:hover:bg-green-500 enabled:hover:text-white disabled:cursor-not-allowed disabled:border-gray-600 disabled:text-gray-600"
            :disabled="!allowSubmit"
          >
            <Icon icon="mdi:check" />
            Track
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
