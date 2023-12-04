<script setup lang="ts">
import { Icon } from "@iconify/vue";
import AppLayout from "@/layouts/AppLayout.vue";
</script>

<template>
  <AppLayout>
    <div class="mx-3 mt-3 flex justify-center md:mx-6 md:mt-6">
      <div class="max-w-7xl flex-1 rounded-md bg-white p-6 shadow">
        <h1 class="text-xl font-bold">Packages</h1>
        <ul role="list" class="mt-6 divide-y divide-gray-100">
          <li class="relative py-5 hover:bg-gray-50">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="mx-auto flex justify-between gap-x-6">
                <div class="flex min-w-0 gap-x-4">
                  <div
                    class="flex items-center justify-center rounded-full bg-amber-200 p-3 text-2xl"
                  >
                    <Icon
                      icon="streamline:shipping-box-2-box-package-label-delivery-shipment-shipping-3d"
                    />
                  </div>
                  <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">
                      <a href="#">
                        <span class="absolute inset-x-0 -top-px bottom-0"></span>
                        Bolovo Sidebag
                      </a>
                    </p>
                    <p class="mt-1 flex text-xs leading-5 text-gray-500">NE123456789BR</p>
                  </div>
                </div>
                <div class="flex shrink-0 items-center gap-x-4">
                  <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p class="rounded-full bg-amber-200 px-3 text-sm leading-6 text-gray-900">
                      In transit
                    </p>
                    <p class="mt-1 text-xs leading-5 text-gray-500">
                      Last tracked <time datetime="2023-01-23T13:23Z">7 minutes ago</time>
                    </p>
                  </div>
                  <Icon
                    icon="ic:baseline-keyboard-arrow-right"
                    class="h-5 w-5 flex-none text-gray-400"
                  />
                </div>
              </div>
            </div>
          </li>
          <li class="relative py-5 hover:bg-gray-50">
            <div class="px-4 sm:px-6 lg:px-8">
              <div class="mx-auto flex justify-between gap-x-6">
                <div class="flex min-w-0 gap-x-4">
                  <div
                    class="flex items-center justify-center rounded-full bg-green-200 p-3 text-2xl"
                  >
                    <Icon
                      icon="streamline:shipping-box-2-box-package-label-delivery-shipment-shipping-3d"
                    />
                  </div>
                  <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 text-gray-900">
                      <a href="#">
                        <span class="absolute inset-x-0 -top-px bottom-0"></span>
                        AKG K361
                      </a>
                    </p>
                    <p class="mt-1 flex text-xs leading-5 text-gray-500">NE123456789BR</p>
                  </div>
                </div>
                <div class="flex shrink-0 items-center gap-x-4">
                  <div class="hidden sm:flex sm:flex-col sm:items-end">
                    <p class="rounded-full bg-green-200 px-3 text-sm leading-6 text-gray-900">
                      Delivered
                    </p>
                    <p class="mt-1 text-xs leading-5 text-gray-500">
                      Last event <time datetime="2023-01-23T13:23Z">3 months ago</time>
                    </p>
                  </div>
                  <Icon
                    icon="ic:baseline-keyboard-arrow-right"
                    class="h-5 w-5 flex-none text-gray-400"
                  />
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>
