<template>
  <div class="flex min-h-full bg-gray-200">
    <Sidebar />
    <div class="flex-1">
      <Navbar />
      <!-- Content -->
      <main class="p-6">
        <div class="p-4 rounded bg-white">
          <router-view></router-view>
        </div>
      </main>
      <!-- Content -->
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});
</script>
