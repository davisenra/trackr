<script setup lang="ts">
import { useRouter } from "vue-router";
import TheNavbar from "@/components/TheNavbar.vue";
import { computed } from "vue";
const router = useRouter();

const currentRouteName = computed(() => {
  const routeName = router.currentRoute.value.name?.toString();
  if (!routeName) {
    return "";
  }
  return routeName[0].toUpperCase() + routeName.slice(1);
});
</script>

<template>
  <main>
    <TheNavbar />
    <div class="bg-white shadow">
      <div class="mx-auto flex max-w-7xl items-center px-3 py-6">
        <h1 class="text-2xl font-bold">{{ currentRouteName }}</h1>
      </div>
    </div>
    <slot />
  </main>
</template>
