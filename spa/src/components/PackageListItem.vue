<script setup lang="ts">
import { Icon } from "@iconify/vue";
import { PackageStatus, type Package } from "@/types";
import { computed } from "vue";
import PackageStatusIndicator from "./PackageStatusIndicator.vue";

const props = defineProps<{
  package: Package;
}>();

const packageLabel = computed(() => {
  switch (props.package.status) {
    case PackageStatus.Posted:
      return "Posted";
    case PackageStatus.InTransit:
      return "In transit";
    case PackageStatus.OutForDelivery:
      return "Out for delivery";
    case PackageStatus.Delivered:
      return "Delivered";
    default:
      return "Unknown";
  }
});
</script>

<template>
  <li class="relative py-5 hover:bg-gray-50">
    <div class="px-4 sm:px-6 lg:px-8">
      <div class="mx-auto flex justify-between gap-x-6">
        <div class="flex min-w-0 gap-x-4">
          <div
            class="relative flex items-center justify-center rounded-full bg-gray-100 p-3 text-2xl"
          >
            <Icon
              icon="streamline:shipping-box-2-box-package-label-delivery-shipment-shipping-3d"
            />
            <PackageStatusIndicator :status="package.status" />
          </div>
          <div class="min-w-0 flex-auto">
            <p class="text-sm font-semibold leading-6 text-gray-900">
              <router-link :to="'/packages/' + package.id">
                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                {{ package.name }}
              </router-link>
            </p>
            <p class="mt-1 flex text-xs leading-5 text-gray-500">
              {{ package.trackingCode }}
            </p>
          </div>
        </div>
        <div class="flex shrink-0 items-center gap-x-4">
          <div class="hidden sm:flex sm:flex-col sm:items-end">
            <p
              class="rounded-full px-3 text-sm leading-6 text-gray-900"
              :class="{
                'bg-gray-200': package.status === PackageStatus.Posted,
                'bg-amber-200': package.status === PackageStatus.InTransit,
                'bg-red-300': package.status === PackageStatus.OutForDelivery,
                'bg-green-200': package.status === PackageStatus.Delivered
              }"
            >
              {{ packageLabel }}
            </p>
            <p class="mt-1 text-xs leading-5 text-gray-500">
              Last tracked <time datetime="2023-01-23T13:23Z">7 minutes ago</time>
            </p>
          </div>
          <Icon icon="ic:baseline-keyboard-arrow-right" class="h-5 w-5 flex-none text-gray-400" />
        </div>
      </div>
    </div>
  </li>
</template>
