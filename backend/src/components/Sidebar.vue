<template>
  <!-- Sidebar -->
  <div class="w-[200px] bg-indigo-700 rounded-l-xl text-white py-4 px-2">
    <router-link
      :to="{ name: 'app.dashboard' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <HomeIcon class="w-4" />
      </span>
      <span class="text-base">Dashboard</span>
    </router-link>
    <router-link
      :to="{ name: 'app.dashboard' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <Bars3Icon class="w-5" />
      </span>
      <span class="text-base">Products</span>
    </router-link>
    <router-link
      :to="{ name: 'app.dashboard' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5" />
      </span>
      <span class="text-base">Users</span>
    </router-link>
    <router-link
      :to="{ name: 'app.dashboard' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <ChartBarIcon class="w-5" />
      </span>
      <span class="text-base">Reports</span>
    </router-link>
  </div>
</template>

<script setup>
import {
  HomeIcon,
  UsersIcon,
  Bars3Icon,
  ChartBarIcon,
} from "@heroicons/vue/24/outline";
</script>

<style lang="scss" scoped></style>
