<script setup lang="ts">
import type { PackageEvent } from "@/types";
import { Icon } from "@iconify/vue";
import { dayjs } from "@/services/dayjs";

defineProps<{
  events: PackageEvent[];
}>();

const eventedAt = (eventedAt: string) => {
  return dayjs(eventedAt).format("H:m YYYY-MM-DD");
};
</script>

<template>
  <div class="mb-3">
    <h1 class="text-xl font-bold">Timeline</h1>
  </div>
  <div
    class="relative m-auto max-w-4xl space-y-8 before:absolute before:inset-0 before:ml-5 before:h-full before:w-0.5 before:-translate-x-px before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent md:before:mx-auto md:before:translate-x-0"
  >
    <div
      v-for="event in events"
      :key="event.id"
      class="group relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse"
    >
      <div
        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white shadow md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2"
      >
        <Icon icon="mdi:check-bold" />
      </div>
      <div class="w-[calc(100%-4rem)] rounded border border-slate-200 p-4 md:w-[calc(50%-2.5rem)]">
        <div class="mb-1 flex items-center justify-between space-x-2">
          <div class="font-bold text-slate-900">{{ event.status }}</div>
          <time class="font-caveat font-medium text-gray-400">{{
            eventedAt(event.eventedAt)
          }}</time>
        </div>
        <div class="space-y-1 text-slate-500">
          <div v-if="event.location" class="text-sm font-medium">
            Location: {{ event.location }}
          </div>
          <div v-if="event.destination" class="text-sm font-medium">
            Destination: {{ event.destination }}
          </div>
          <div>
            <div class="text-xs">Hash: {{ event.statusHash }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
