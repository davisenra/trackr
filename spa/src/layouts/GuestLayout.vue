<script setup lang="ts">
import { Icon } from "@iconify/vue";
</script>

<template>
  <div class="flex h-screen flex-col items-center justify-center px-6 py-12 lg:px-8">
    <div class="w-full max-w-xl bg-white py-12 shadow">
      <div class="flex justify-center">
        <div class="inline-flex items-center gap-1 text-3xl">
          <Icon icon="ic:baseline-track-changes" />
          <h1 class="font-bold">Trackr</h1>
        </div>
      </div>
      <slot />
    </div>
  </div>
</template>
