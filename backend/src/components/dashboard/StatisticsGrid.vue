<template>
  <TransitionGroup
    name="fade"
    tag="div"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
  >
    <StatisticsCard
      v-for="(stat, index) in statistics"
      :key="stat.title"
      :title="stat.title"
      :value="stat.value"
      :icon="stat.icon"
      :style="{ '--delay': `${index * 0.1}s` }"
      class="fade-item"
    />
  </TransitionGroup>
</template>

<script setup>
import StatisticsCard from "./StatisticsCard.vue";

defineProps({
  statistics: Array, // Array of statistic objects
});
</script>

<style scoped>
.fade-item {
  transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
  transition-delay: var(--delay, 0s);
}
.fade-enter-from {
  opacity: 0;
  transform: translateY(-20px);
}
.fade-enter-to {
  opacity: 1;
  transform: translateY(0);
}
</style>
