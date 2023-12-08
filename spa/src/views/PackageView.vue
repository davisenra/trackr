<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { useRoute } from "vue-router";
import { onMounted, ref, computed } from "vue";
import { PackageStatus as PackageStatusEnum, type PackageWithEvents } from "@/types";
import { dayjs } from "@/services/dayjs";
import PackageStatusLabel from "@/components/PackageStatusLabel.vue";
import SpinnerPlaceholder from "@/components/SpinnerPlaceholder.vue";
import PackageEventsTimeline from "@/components/PackageEventsTimeline.vue";
import { Icon } from "@iconify/vue";

const packageId = useRoute().params.id;
const packg = ref<PackageWithEvents>();

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
      events: [
        {
          id: 1,
          packageId: 4,
          status: "Package posted",
          statusHash: "123456789",
          location: "São Paulo",
          destination: "Rio de Janeiro",
          eventedAt: "2023-12-01T02:37:47.000000Z",
          createdAt: "2023-12-01T02:37:47.000000Z",
          updatedAt: "2023-12-01T02:37:47.000000Z"
        },
        {
          id: 2,
          packageId: 4,
          status: "Package in transit",
          statusHash: "123456789",
          location: "São Paulo",
          destination: "Rio de Janeiro",
          eventedAt: "2023-12-01T02:37:47.000000Z",
          createdAt: "2023-12-01T02:37:47.000000Z",
          updatedAt: "2023-12-01T02:37:47.000000Z"
        },
        {
          id: 3,
          packageId: 4,
          status: "Package out for delivery",
          statusHash: "123456789",
          location: "São Paulo",
          destination: "Rio de Janeiro",
          eventedAt: "2023-12-01T02:37:47.000000Z",
          createdAt: "2023-12-01T02:37:47.000000Z",
          updatedAt: "2023-12-01T02:37:47.000000Z"
        },
        {
          id: 4,
          packageId: 4,
          status: "Package delivered",
          statusHash: "123456789",
          location: "São Paulo",
          destination: "Rio de Janeiro",
          eventedAt: "2023-12-01T02:37:47.000000Z",
          createdAt: "2023-12-01T02:37:47.000000Z",
          updatedAt: "2023-12-01T02:37:47.000000Z"
        }
      ],
      lastTrackedAt: "2023-12-01T02:37:47.000000Z",
      createdAt: "2023-12-01T02:37:47.000000Z",
      updatedAt: "2023-12-01T02:37:47.000000Z"
    };
  }, 250);
});
</script>

<template>
  <AppLayout>
    <div class="mx-3 my-3 flex justify-center md:mx-6 md:my-6">
      <Transition>
        <div
          v-if="packg"
          class="flex w-full max-w-7xl flex-col justify-center gap-3 space-y-2 rounded-md bg-white p-6 shadow"
        >
          <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">
              {{ packg.name }}
            </h1>
            <PackageStatusLabel :status="packg.status" />
          </div>
          <div class="text-gray-700">
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
          <div class="space-x-3">
            <button
              class="inline-flex w-max items-center gap-1 rounded-md border border-green-500 px-2 py-1 text-green-500 hover:bg-green-500 hover:text-white"
            >
              <Icon icon="mdi:refresh" />
              Track
            </button>
            <button
              class="inline-flex w-max items-center gap-1 rounded-md border border-red-400 px-2 py-1 text-red-400 hover:bg-red-400 hover:text-white"
            >
              <Icon icon="mdi:delete" />
              Remove
            </button>
          </div>
          <PackageEventsTimeline :events="packg.events" />
        </div>
      </Transition>
      <div v-if="!packg" class="flex items-center justify-center">
        <SpinnerPlaceholder />
      </div>
    </div>
  </AppLayout>
</template>
