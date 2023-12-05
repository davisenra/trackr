<script setup lang="ts">
import { ref } from "vue";
import PackageListItem from "./PackageListItem.vue";
import { PackageStatus, type Package } from "@/types";

const packages = ref<Package[]>([
  {
    id: 4,
    name: "Disturb Hoodie",
    description: "A nice sidebag for your daily needs",
    trackingCode: "AB123456789BR",
    status: PackageStatus.OutForDelivery,
    lastTrackedAt: "2023-12-01T02:37:47.000000Z",
    createdAt: "2023-12-01T02:37:47.000000Z",
    updatedAt: "2023-12-01T02:37:47.000000Z"
  },
  {
    id: 1,
    name: "Bolovo Sidebag",
    description: "A nice sidebag for your daily needs",
    trackingCode: "NE123456789BR",
    status: PackageStatus.InTransit,
    lastTrackedAt: "2023-12-01T02:37:47.000000Z",
    createdAt: "2023-12-01T02:37:47.000000Z",
    updatedAt: "2023-12-01T02:37:47.000000Z"
  },
  {
    id: 2,
    name: "Bolovo T-Shirt",
    description: "A nice sidebag for your daily needs",
    trackingCode: "TE123456789BR",
    status: PackageStatus.Posted,
    lastTrackedAt: "2023-12-01T02:37:47.000000Z",
    createdAt: "2023-12-01T02:37:47.000000Z",
    updatedAt: "2023-12-01T02:37:47.000000Z"
  },
  {
    id: 3,
    name: "High Tee T-Shirt",
    description: "A nice sidebag for your daily needs",
    trackingCode: "CX987654321BR",
    status: PackageStatus.Delivered,
    lastTrackedAt: "2023-12-01T02:37:47.000000Z",
    createdAt: "2023-12-01T02:37:47.000000Z",
    updatedAt: "2023-12-01T02:37:47.000000Z"
  }
]);
</script>

<template>
  <ul role="list" class="divide-y divide-gray-100">
    <!-- `package` is a reserved word in Javascript so we use packg instead -->
    <PackageListItem v-for="packg in packages" :key="packg.id" :package="packg" />
  </ul>
</template>
