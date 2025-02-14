<template>
  <TransitionGroup
    name="fade"
    tag="div"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
  >
    <StatisticsCard
      v-if="isLoading"
      v-for="index in 4"
      :key="'loading-' + index"
      :loading="true"
    />

    <StatisticsCard
      v-else
      v-for="(stat, index) in statistics"
      :key="stat.title"
      :title="stat.title"
      :value="stat.value"
      :icon="stat.icon"
      :loading="isLoading"
    />
  </TransitionGroup>
</template>

<script setup>
import StatisticsCard from "./StatisticsCard.vue";

defineProps({
  statistics: Array,
  isLoading: Boolean,
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
