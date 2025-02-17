<template>
  <!-- Sidebar -->
  <div
    class="w-[200px] transition-all bg-indigo-700 rounded-l-xl text-white py-4 px-2"
  >
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
      :to="{ name: 'app.products' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <ShoppingBagIcon class="w-5" />
      </span>
      <span class="text-base">Products</span>
    </router-link>
    <router-link
      :to="{ name: 'app.users' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5" />
      </span>
      <span class="text-base">Users</span>
    </router-link>
    <router-link
      :to="{ name: 'app.customers' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <UserGroupIcon class="w-5" />
      </span>
      <span class="text-base">Customers</span>
    </router-link>
    <router-link
      :to="{ name: 'app.reports' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <ChartBarIcon class="w-5" />
      </span>
      <span class="text-base">Reports</span>
    </router-link>
    <router-link
      :to="{ name: 'app.orders' }"
      class="flex items-center hover:bg-black/30 transition-colors p-2 rounded mb-2 outline-none"
    >
      <span class="mr-2 text-gray-300">
        <ShoppingCartIcon class="w-5" />
      </span>
      <span class="text-base">Orders</span>
    </router-link>
  </div>
</template>

<script setup>
import {
  HomeIcon,
  UsersIcon,
  ShoppingBagIcon,
  ChartBarIcon,
  ShoppingCartIcon,
  UserGroupIcon,
} from "@heroicons/vue/24/outline";
</script>

<style lang="scss" scoped></style>
