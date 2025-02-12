<template>
  <div class="bg-white flex flex-col shadow-md rounded-lg p-6 space-x-4">
    <h2 class="font-bold mb-5 text-center">Orders by Country</h2>
    <DoughnutChart :chartData="chartData" :options="chartOptions" />
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  ArcElement,
  DoughnutController,
} from "chart.js";
import { DoughnutChart } from "vue-chart-3";
import axiosClient from "../../axios";

// Register Chart.js components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  ArcElement,
  DoughnutController
);

const chartData = ref({ labels: [], datasets: [] });
const isLoading = ref(true);

const chartOptions = computed(() => ({
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
      },
      title: {
        display: true,
        text: "Chart.js Doughnut Chart",
      },
    },
  },
}));

const fetchCountries = () => {
  isLoading.value = true;

  axiosClient
    .get("dashboard/country-customers")
    .then((response) => {
      const data = response.data;

      chartData.value = {
        labels: data.map((item) => item.name),
        datasets: [
          {
            backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
            data: data.map((item) => item.total_orders_by_country),
          },
        ],
      };
    })
    .catch((error) => {
      console.error("Error fetching revenue data:", error);
    })
    .finally(() => {
      isLoading.value = false;
    });
};

onMounted(fetchCountries);
</script>
