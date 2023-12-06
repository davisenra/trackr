<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { useRoute } from "vue-router";
import { onMounted, ref, computed } from "vue";
import { PackageStatus as PackageStatusEnum, type Package } from "@/types";
import { dayjs } from "@/services/dayjs";
import PackageStatusLabel from "@/components/PackageStatusLabel.vue";
import SpinnerPlaceholder from "@/components/SpinnerPlaceholder.vue";

const packageId = useRoute().params.id;
const packg = ref<Package>();

const datesForHumans = computed(() => {
  return {
    lastTrackedAt: dayjs(packg?.value?.lastTrackedAt).fromNow() ?? "Yet to be tracked",
    createdAt: dayjs(packg?.value?.createdAt).format("MMMM D, YYYY") ?? "Unknown"
  };
});

onMounted(() => {
  setTimeout(() => {
    packg.value = {
      id: 4,
      name: "Disturb Hoodie",
      description: "A nice sidebag for your daily needs",
      trackingCode: "AB123456789BR",
      status: PackageStatusEnum.OutForDelivery,
      lastTrackedAt: "2023-12-01T02:37:47.000000Z",
      createdAt: "2023-12-01T02:37:47.000000Z",
      updatedAt: "2023-12-01T02:37:47.000000Z"
    };
  }, 250);
});
</script>

<template>
  <AppLayout>
    <div class="mx-3 mt-3 flex flex-col justify-center md:mx-6 md:mt-6">
      <Transition>
        <div v-if="packg" class="max-w-7xl flex-1 gap-3 rounded-md bg-white p-6 shadow">
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">
              {{ packg.name }}
            </h1>
            <PackageStatusLabel :status="packg.status" />
          </div>
          <div class="my-3 space-y-1 text-gray-700">
            <p v-if="packg.description">
              {{ packg.description }}
            </p>
            <p><span class="font-bold">Id:</span> {{ packg.id }}</p>
            <p><span class="font-bold">Tracking code:</span> {{ packg.trackingCode }}</p>
            <p>
              <span class="font-bold">Last tracked at:</span> {{ datesForHumans.lastTrackedAt }}
            </p>
            <p><span class="font-bold">Tracked since:</span> {{ datesForHumans.createdAt }}</p>
          </div>
        </div>
      </Transition>
      <div v-if="!packg" class="flex items-center justify-center">
        <SpinnerPlaceholder />
      </div>
    </div>
  </AppLayout>
</template>
