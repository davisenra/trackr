<script setup lang="ts">
import { useAuthStore } from "@/stores/auth";
import { Icon } from "@iconify/vue";
import { reactive, onMounted, onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const authStore = useAuthStore();
const header = reactive({
  mobileMenuOpen: false
});

async function handleLogout() {
  await authStore.logout();
  router.push("/login");
}

function handleClickOutside(event: MouseEvent) {
  const target = event.target;
  const menu = document.getElementById("mobile-menu-button");

  if (menu && !menu.contains(target as Node)) {
    header.mobileMenuOpen = false;
    return;
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <header class="bg-yellow-300 px-2">
    <div class="mx-auto flex h-16 max-w-7xl justify-between">
      <div class="inline-flex items-center gap-1 text-2xl">
        <Icon icon="ic:baseline-track-changes" />
        <h1 class="font-bold">Trackr</h1>
      </div>
      <div class="hidden space-x-2 md:flex">
        <router-link
          to="/"
          active-class="border-b border-yellow-700"
          class="inline-flex items-center px-2 font-medium transition-all hover:border-b hover:border-yellow-700"
        >
          Dashboard
        </router-link>
        <router-link
          to="/packages/track"
          active-class="border-b border-yellow-700"
          class="inline-flex items-center px-2 font-medium transition-all hover:border-b hover:border-yellow-700"
        >
          Track
        </router-link>
        <button
          @click="handleLogout"
          class="inline-flex items-center px-2 font-medium transition-all hover:border-b hover:border-yellow-700"
        >
          Sign out
        </button>
      </div>
      <div class="relative md:hidden">
        <div class="flex h-full items-center">
          <button
            id="mobile-menu-button"
            @click="header.mobileMenuOpen = !header.mobileMenuOpen"
            :class="{ 'bg-yellow-600': header.mobileMenuOpen }"
            class="flex items-center rounded-md bg-opacity-30 p-2 text-2xl transition-all hover:bg-yellow-600 hover:bg-opacity-30"
          >
            <Icon icon="ic:baseline-menu" />
          </button>
        </div>
        <div
          v-if="header.mobileMenuOpen"
          class="absolute right-0 z-20 -mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
          role="menu"
          tabindex="-1"
        >
          <router-link to="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            Dashboard
          </router-link>
          <router-link
            to="/packages/track"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
          >
            Track
          </router-link>
          <button
            @click="handleLogout"
            class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
          >
            Sign out
          </button>
        </div>
      </div>
    </div>
  </header>
</template>
