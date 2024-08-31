<template>
  <div v-if="currentUser.id" class="flex min-h-full bg-gray-200">
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
  <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
    <Spinner />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import store from "../store";
import Spinner from "./core/Spinner.vue";

const props = defineProps({});

const sidebarOpened = ref(true);
const currentUser = computed(() => store.state.user.data);

const toggleSidebar = () => {
  sidebarOpened.value = !sidebarOpened.value;
};

onMounted(() => {
  store.dispatch("getUser");
  handleSidebarOpened();
  window.addEventListener("resize", handleSidebarOpened);
});

onUnmounted(() => {
  window.removeEventListener("resize", handleSidebarOpened);
});

const handleSidebarOpened = () => {
  sidebarOpened.value = window.outerWidth > 768;
};
</script>
