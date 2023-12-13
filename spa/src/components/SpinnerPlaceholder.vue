<script setup lang="ts">
import { Icon } from "@iconify/vue";

defineProps<{
  textSize?: string;
  color?: string;
}>();
</script>

<template>
  <Icon icon="mdi:loading" class="animate-spin text-5xl text-gray-300" :class="[ textSize ?? '', color ?? '' ]" />
</template>
