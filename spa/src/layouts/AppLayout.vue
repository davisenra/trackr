<script setup lang="ts">
import { Icon } from "@iconify/vue";
import { reactive } from "vue";

const menu = reactive({
  mobileMenuOpen: false
});

function closeMobileMenuWhenClickedOutside() {
  setTimeout(() => (menu.mobileMenuOpen = false), 50);
}
</script>

<template>
  <main>
    <header class="bg-yellow-300 px-2">
      <div class="mx-auto flex h-16 max-w-7xl justify-between">
        <div class="inline-flex items-center gap-1 text-2xl">
          <Icon icon="ic:baseline-track-changes" />
          <h1 class="font-bold">Trackr</h1>
        </div>
        <div class="hidden space-x-8 md:flex">
          <a
            href="#"
            class="inline-flex items-center border-b-2 border-yellow-600 bg-opacity-30 px-1 pt-1 font-medium"
          >
            Dashboard
          </a>
          <a
            href="#"
            class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 font-medium"
          >
            Sign out
          </a>
        </div>
        <div class="relative md:hidden">
          <div class="flex h-full items-center">
            <button
              @click="menu.mobileMenuOpen = !menu.mobileMenuOpen"
              @blur="closeMobileMenuWhenClickedOutside"
              :class="{ 'bg-yellow-600': menu.mobileMenuOpen }"
              class="flex items-center rounded-md bg-opacity-30 p-2 text-2xl transition-all hover:bg-yellow-600 hover:bg-opacity-30"
            >
              <Icon icon="ic:baseline-menu" />
            </button>
          </div>
          <div
            v-if="menu.mobileMenuOpen"
            class="absolute right-0 z-10 -mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="user-menu-button"
            tabindex="-1"
          >
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Dashboard</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Sign out</a>
          </div>
        </div>
      </div>
    </header>
    <div class="bg-white shadow">
      <div class="mx-auto flex max-w-7xl items-center px-3 py-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
      </div>
    </div>
    <slot />
  </main>
</template>
