<script setup lang="ts">
import { Icon } from "@iconify/vue";
import type { Package } from "@/types";
import { computed } from "vue";
import PackageStatusIndicator from "@/components/PackageStatusIndicator.vue";
import PackageStatusLabel from "@/components/PackageStatusLabel.vue";
import { dayjs } from "@/services/dayjs";

const props = defineProps<{
  package: Package;
}>();

const lastTrackedAtForHumans = computed(() => {
  return dayjs(props.package.lastTrackedAt).fromNow();
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
            <PackageStatusLabel :status="package.status" />
            <p class="mt-1 text-xs leading-5 text-gray-500">
              Last tracked {{ lastTrackedAtForHumans }}
            </p>
          </div>
          <Icon icon="ic:baseline-keyboard-arrow-right" class="h-5 w-5 flex-none text-gray-400" />
        </div>
      </div>
    </div>
  </li>
</template>
