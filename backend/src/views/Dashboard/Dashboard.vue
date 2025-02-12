<!-- views/DashboardView.vue -->
<template>
  <div class="p-6">
    <DashboardStatistics class="mb-5" />
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <DashboardRevenue />
      <DashboardLatestCustomers />
      <DashboardLatestOrders />
      <DashboardCountry />
    </div>
  </div>
</template>

<script setup>
import DashboardStatistics from "./DashboardStatistics.vue";
import DashboardRevenue from "./DashboardRevenue.vue";
import DashboardCountry from "./DashboardCountry.vue";
import DashboardLatestCustomers from "./DashboardLatestCustomers.vue";
import DashboardLatestOrders from "./DashboardLatestOrders.vue";
</script>
