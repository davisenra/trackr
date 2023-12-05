<script setup lang="ts">
import { PackageStatus } from "@/types";
import { Icon } from "@iconify/vue";
import { computed } from "vue";

const props = defineProps<{
  status: PackageStatus;
}>();

const indicatorIcon = computed(() => {
  switch (props.status) {
    case PackageStatus.InTransit:
      return "mdi:road-variant";
    case PackageStatus.OutForDelivery:
      return "mdi:truck";
    case PackageStatus.Delivered:
      return "mdi:check-bold";
    default:
      return "mdi:tilde";
  }
});
</script>

<template>
  <div class="absolute -right-1 -top-1">
    <span
      v-if="status === PackageStatus.OutForDelivery"
      class="absolute inline-flex h-5 w-5 animate-ping rounded-full bg-red-500 opacity-75"
    ></span>
    <span
      class="relative flex h-5 w-5 items-center justify-center rounded-full p-1"
      :class="{
        'bg-gray-200': status === PackageStatus.Posted,
        'bg-amber-200': status === PackageStatus.InTransit,
        'bg-red-200': status === PackageStatus.OutForDelivery,
        'bg-green-200': status === PackageStatus.Delivered
      }"
    >
      <Icon :icon="indicatorIcon" class="opacity-40" />
    </span>
  </div>
</template>
