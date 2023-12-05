<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import PackageList from "@/components/PackageList.vue";
</script>

<template>
  <AppLayout>
    <div class="mx-3 mt-3 flex justify-center md:mx-6 md:mt-6">
      <div class="max-w-7xl flex-1 gap-3 rounded-md bg-white p-6 shadow">
        <h1 class="text-xl font-bold">Packages</h1>
        <PackageList />
      </div>
    </div>
  </AppLayout>
</template>
