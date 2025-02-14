<!-- components/dashboard/DashboardStatistics.vue -->
<template>
  <div>
    <StatisticsGrid :statistics="statisticsData" :isLoading="isLoading" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import StatisticsGrid from "../../components/dashboard/StatisticsGrid.vue";

import {
  UserGroupIcon,
  ShoppingBagIcon,
  ShoppingCartIcon,
  BanknotesIcon,
} from "@heroicons/vue/24/outline";
import axiosClient from "../../axios";

const statisticsData = ref([]);
const isLoading = ref(false);

const fetchStatistics = () => {
  isLoading.value = true;
  axiosClient
    .get("dashboard/statistics")
    .then((response) => {
      const { customers, products, orders, revenue } = response.data;
      statisticsData.value = [
        { title: "Total Customers", value: customers, icon: UserGroupIcon },
        { title: "Active Products", value: products, icon: ShoppingBagIcon },
        { title: "Paid Orders", value: orders, icon: ShoppingCartIcon },
        {
          title: "Total Revenue",
          value: `${new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
          }).format(revenue)}`,
          icon: BanknotesIcon,
        },
      ];
    })
    .catch((error) => {
      console.error("Failed to fetch statistics:", error);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

onMounted(fetchStatistics);
</script>
