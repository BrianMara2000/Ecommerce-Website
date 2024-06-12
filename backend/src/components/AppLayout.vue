<template>
  <div class="flex min-h-full bg-gray-200">
    <Sidebar
      :class="{
        '-ml-[200px] transition-all': !sidebarOpened,
      }"
    />
    <div class="flex-1">
      <Navbar @toggle-sidebar="toggleSidebar" />
      <!-- Content -->
      <main class="p-4">
        <div class="p-4 rounded bg-white">
          <router-view></router-view>
        </div>
      </main>
      <!-- Content -->
    </div>
  </div>
</template>

<script setup>
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";

const props = defineProps({
  // title: {
  //   type: String,
  //   required: true,
  // },
  // toggle: {
  //   type: Boolean,
  // },
});

const sidebarOpened = ref(true);

const toggleSidebar = () => {
  sidebarOpened.value = !sidebarOpened.value;
  console.log("toggleSidebar");
};
</script>
