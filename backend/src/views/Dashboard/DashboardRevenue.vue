<template>
  <div
    class="bg-white flex flex-col col-span-2 justify-center shadow-md rounded-lg p-6 space-x-4"
  >
    <h2 class="font-bold mb-5 text-left">Revenue Chart</h2>
    <div class="w-full max-w-6xl mx-auto p-4">
      <div class="flex justify-between">
        <!-- Period Selection Buttons -->
        <div>
          <button
            v-for="option in ['Daily', 'Weekly', 'Monthly']"
            :key="option"
            @click="setTimeFrame(option)"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap transition-all"
            :class="
              selectedPeriod === option.toLowerCase()
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            "
          >
            {{ option }}
          </button>
        </div>

        <!-- Date Pickers -->
        <div class="flex items-center justify-end space-x-4 mb-4">
          <label class="text-sm font-medium">Select Date Range:</label>

          <!-- Daily (Date Range Picker) -->
          <Datepicker
            v-if="selectedPeriod === 'daily'"
            v-model:value="dateRange"
            range
            format="YYYY-MM-DD"
            @change="fetchRevenueData"
          />

          <!-- Weekly (Month Picker) -->
          <Datepicker
            v-if="selectedPeriod === 'weekly'"
            v-model:value="selectedMonth"
            type="month"
            format="YYYY-MM"
            @change="fetchRevenueData()"
          />

          <!-- Monthly (Year Picker) -->
          <Datepicker
            v-if="selectedPeriod === 'monthly'"
            v-model:value="selectedYear"
            type="year"
            format="YYYY"
            @change="fetchRevenueData()"
          />
        </div>
      </div>

      <!-- Chart Component -->
      <BarChart :chartData="chartData" :options="chartOptions" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  CategoryScale,
  LinearScale,
  LineController,
  PointElement,
  BarController,
} from "chart.js";
import { BarChart } from "vue-chart-3";
import axiosClient from "../../axios";
import Datepicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";
import dayjs from "dayjs";
import weekOfYear from "dayjs/plugin/weekOfYear";
import isoWeek from "dayjs/plugin/isoWeek";

// Register Chart.js components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  CategoryScale,
  LinearScale,
  LineController,
  PointElement,
  BarController
);
dayjs.extend(weekOfYear);
dayjs.extend(isoWeek);

// State Variables
const selectedPeriod = ref("daily");
const dateRange = ref([getStartDate("daily"), getEndDate()]);
const selectedMonth = ref(dayjs().format("YYYY-MM"));
const selectedYear = ref(dayjs().format("YYYY"));
const chartData = ref({ labels: [], datasets: [] });
const isLoading = ref(true);
const fixDateFormat = (date) => dayjs(date).format("YYYY-MM-DD");

// Computed Property for Chart Options
const chartOptions = computed(() => ({
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    x: {
      display: true,
      title: {
        display: true,
        text: getChartLabel(),
        font: { size: 20, weight: "bold" },
      },
      type: "category",
    },
    y: { beginAtZero: true },
  },
}));

// Helper Functions
function getStartDate(period) {
  if (period === "daily") {
    return dayjs().subtract(20, "days").format("YYYY-MM-DD");
  }
  return dayjs().format("YYYY-MM-DD");
}

function getEndDate() {
  return dayjs().format("YYYY-MM-DD");
}

function getChartLabel() {
  if (selectedPeriod.value === "daily" && dateRange.value.length === 2) {
    return `${dayjs(dateRange.value[0]).format("MMM DD")} - ${dayjs(
      dateRange.value[1]
    ).format("MMM DD")}`;
  }
  if (selectedPeriod.value === "weekly") {
    return dayjs(selectedMonth.value).format("MMMM YYYY");
  }
  if (selectedPeriod.value === "monthly") {
    return dayjs(selectedYear.value).format("YYYY");
  }
  return "";
}

// Fetch Revenue Data
const fetchRevenueData = () => {
  isLoading.value = true;

  let startDate, endDate;
  if (selectedPeriod.value === "daily") {
    startDate = dateRange.value[0];
    endDate = dateRange.value[1];
  } else if (selectedPeriod.value === "weekly") {
    startDate = `${selectedMonth.value}-01`; // Start of the selected month
    endDate = dayjs(selectedMonth.value).endOf("month").format("YYYY-MM-DD"); // End of the selected month
  } else if (selectedPeriod.value === "monthly") {
    const year =
      typeof selectedYear.value === "object"
        ? dayjs(selectedYear.value).format("YYYY")
        : selectedYear.value;

    startDate = `${year}-01-01`;
    endDate = `${year}-12-31`;
  }

  axiosClient
    .get("dashboard/revenues", {
      params: {
        startDate: fixDateFormat(startDate),
        endDate: fixDateFormat(endDate),
        period: selectedPeriod.value,
      },
    })
    .then((response) => {
      const data = response.data;
      if (!data.length) {
        console.warn("No data available for selected period.");
      }

      // Process Data for Chart
      let labels = [];
      let revenueData = [];

      if (selectedPeriod.value === "daily") {
        labels = data.map((item) => {
          const date = new Date(item.date);
          return date.toLocaleDateString("en-US", {
            day: "numeric",
          });
        });
        revenueData = data.map((item) => item.total_revenue);
      } else if (selectedPeriod.value === "weekly") {
        let weeklyGroups = {};

        data.forEach((item) => {
          // Format the week data
          let weekLabel = item.week;
          if (!weeklyGroups[weekLabel]) {
            weeklyGroups[weekLabel] = 0;
          }
          weeklyGroups[weekLabel] += parseFloat(item.total_revenue); // Ensure it's a number
        });

        labels = Object.keys(weeklyGroups);
        revenueData = Object.values(weeklyGroups);
      } else if (selectedPeriod.value === "monthly") {
        let monthlyGroups = {};

        data.forEach((item) => {
          let monthLabel = dayjs(item.month).format("MMMM");

          if (!monthlyGroups[monthLabel]) {
            monthlyGroups[monthLabel] = 0;
          }
          monthlyGroups[monthLabel] += item.total_revenue;
        });

        labels = Object.keys(monthlyGroups);
        revenueData = Object.values(monthlyGroups);
      }

      chartData.value = {
        labels,
        datasets: [
          {
            label: "Revenue",
            data: revenueData,
            borderColor: "blue",
            backgroundColor: "rgba(0, 0, 255, 0.5)",
            type: "bar",
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

// Change Time Frame (Daily, Weekly, Monthly)
const setTimeFrame = (period) => {
  selectedPeriod.value = period.toLowerCase();

  if (period === "Daily") {
    dateRange.value = [getStartDate("daily"), getEndDate()];
  } else if (period === "Weekly") {
    selectedMonth.value = dayjs().format("YYYY-MM");
  } else if (period === "Monthly") {
    selectedYear.value = dayjs().format("YYYY");
  }
  fetchRevenueData();
};

// Initial Data Fetch
onMounted(fetchRevenueData);
</script>
