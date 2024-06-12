<template>
  <div class="flex min-h-full">
    <Sidebar />
    <div class="flex-1">
      <header class="h-8 shadow bg-white">Header</header>
      <!-- Content -->
      <main>
        <router-view></router-view>
      </main>
      <!-- Content -->
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
import Sidebar from "./Sidebar.vue";

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
});
</script>
