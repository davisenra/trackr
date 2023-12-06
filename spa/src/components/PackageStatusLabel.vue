<script setup lang="ts">
import { PackageStatus } from "@/types";
import { computed } from "vue";

const props = defineProps<{
  status: PackageStatus;
}>();

const packageLabel = computed(() => {
  switch (props.status) {
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
  <p
    class="rounded-full px-3 text-sm leading-6 text-gray-900"
    :class="{
      'bg-gray-200': status === PackageStatus.Posted,
      'bg-amber-200': status === PackageStatus.InTransit,
      'bg-red-300': status === PackageStatus.OutForDelivery,
      'bg-green-200': status === PackageStatus.Delivered
    }"
  >
    {{ packageLabel }}
  </p>
</template>
