<template>
  <section class="w-full h-full flex justify-center items-center px-4 2xl:px-0">
    <div
      class="max-w-md w-full rounded-lg border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800 text-center"
    >
      <!-- Animated Failed Icon -->
      <div class="relative flex justify-center items-center mb-6">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 100 100"
          class="w-20 h-20"
          borders="#dc310d"
        >
          <!-- Circle animation -->
          <circle
            cx="50"
            cy="50"
            r="45"
            fill="none"
            stroke="#dc310d"
            stroke-width="5"
            stroke-dasharray="282.6"
            stroke-dashoffset="282.6"
            class="circle-animation"
          />
          <!-- Wrong icon animation -->
          <path
            d="M30 30 L70 70"
            fill="none"
            stroke="#dc310d"
            stroke-width="5"
            stroke-linecap="round"
            stroke-dasharray="70"
            stroke-dashoffset="70"
            class="line1-animation"
          />
          <path
            d="M70 30 L30 70"
            fill="none"
            stroke="#dc310d"
            stroke-width="5"
            stroke-linecap="round"
            stroke-dasharray="70"
            stroke-dashoffset="70"
            class="line2-animation"
          />
        </svg>
      </div>

      <!-- Payment failed message -->
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
        Payment Failed!
      </h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">
        You're transaction has failed due to {{ message }}. Please try again.
      </p>
      <!-- Buttons -->
      <div class="flex justify-center gap-4 mt-6">
        <Link
          :href="route('user.home')"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:ring focus:ring-gray-200"
        >
          Go to Homepage
        </Link>
        <PrimaryButton> Go to Cart </PrimaryButton>
      </div>
    </div>
  </section>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UserLayout from "../../Layouts/UserLayout.vue";
import { usePage, Link } from "@inertiajs/vue3";
import { computed } from "vue";

defineOptions({ layout: UserLayout });

const customer = computed(() => usePage().props.customer.name);
const message = computed(() => usePage().props.message);
</script>

<style scoped>
/* Circle animation */
.circle-animation {
  animation: draw-circle 1s ease-in-out forwards;
}

/* Wrong Icon animation */
.line1-animation {
  animation: draw-check 0.5s ease-in-out forwards;
  animation-delay: 0.5s; /* Starts after the circle animation */
}
.line2-animation {
  animation: draw-check 0.5s ease-in-out forwards;
  animation-delay: 1s; /* Starts after the circle animation */
}

/* Circle keyframes */
@keyframes draw-circle {
  from {
    stroke-dashoffset: 282.6; /* Full offset (invisible) */
  }
  to {
    stroke-dashoffset: 0; /* Fully drawn */
  }
}

/* Check keyframes */
@keyframes draw-check {
  from {
    stroke-dashoffset: 70; /* Full offset (invisible) */
  }
  to {
    stroke-dashoffset: 0; /* Fully drawn */
  }
}
</style>
