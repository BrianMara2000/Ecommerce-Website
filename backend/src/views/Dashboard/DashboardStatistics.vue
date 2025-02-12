<!-- components/dashboard/DashboardStatistics.vue -->
<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Dashboard</h2>
    <StatisticsGrid :statistics="statisticsData" />
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

const fetchStatistics = () => {
  axiosClient
    .get("dashboard/statistics")
    .then((response) => {
      const { customers, products, orders, income } = response.data;
      statisticsData.value = [
        { title: "Total Customers", value: customers, icon: UserGroupIcon },
        { title: "Active Products", value: products, icon: ShoppingBagIcon },
        { title: "Paid Orders", value: orders, icon: ShoppingCartIcon },
        {
          title: "Total Income",
          value: `${new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
          }).format(income)}`,
          icon: BanknotesIcon,
        },
      ];
    })
    .catch((error) => {
      console.error("Failed to fetch statistics:", error);
    });
};

onMounted(fetchStatistics);
</script>

dot-flashing
