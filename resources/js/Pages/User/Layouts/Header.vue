<template>
  <!-- Header -->
  <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div
      class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
    >
      <Link href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img
          src="https://flowbite.com/docs/images/logo.svg"
          class="h-8"
          alt="Flowbite Logo"
        />
        <span
          class="self-center text-2xl font-bold whitespace-nowrap dark:text-white"
          >LOGO</span
        >
      </Link>
      <div
        v-if="canLogin"
        class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-5"
      >
        <a
          :href="route('cart.index')"
          class="relative inline-flex items-center p-2 text-xs hover:bg-gray-200 font-medium text-center rounded-lg"
        >
          <svg
            id="Shopping_Cart_32"
            width="32"
            height="32"
            viewBox="0 0 32 32"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
          >
            <rect
              width="32"
              height="32"
              stroke="none"
              fill="#000000"
              opacity="0"
            />

            <g transform="matrix(0.58 0 0 0.58 16 16)">
              <path
                style="
                  stroke: none;
                  stroke-width: 1;
                  stroke-dasharray: none;
                  stroke-linecap: butt;
                  stroke-dashoffset: 0;
                  stroke-linejoin: miter;
                  stroke-miterlimit: 4;
                  fill: rgb(0, 0, 0);
                  fill-rule: nonzero;
                  opacity: 1;
                "
                transform=" translate(-24, -26)"
                d="M 1.8125 2 C 1.78125 2.007813 1.75 2.019531 1.71875 2.03125 C 0.746094 2.167969 0 2.992188 0 4 C 0 5.105469 0.894531 6 2 6 C 3.105469 6 4 5.105469 4 4 L 8.65625 4 C 9.902344 4 10.550781 4.257813 11.03125 4.6875 C 11.503906 5.113281 11.886719 5.832031 12.21875 6.9375 L 20.21875 39.21875 C 20.519531 40.363281 20.820313 41.542969 21.65625 42.5 C 22.003906 42.898438 22.441406 43.222656 22.96875 43.46875 C 22.382813 44.164063 22 45.027344 22 46 C 22 48.199219 23.800781 50 26 50 C 28.199219 50 30 48.199219 30 46 C 30 45.265625 29.785156 44.59375 29.4375 44 L 35.5625 44 C 35.214844 44.59375 35 45.265625 35 46 C 35 48.199219 36.800781 50 39 50 C 41.199219 50 43 48.199219 43 46 C 43 44.972656 42.582031 44.054688 41.9375 43.34375 C 42.050781 43.039063 42.003906 42.695313 41.820313 42.429688 C 41.632813 42.160156 41.328125 42 41 42 L 25.71875 42 C 24.175781 42 23.546875 41.671875 23.125 41.1875 C 22.707031 40.707031 22.453125 39.867188 22.15625 38.75 L 22.15625 38.71875 L 21.46875 36 L 39.8125 36 C 40.230469 36 40.609375 35.738281 40.75 35.34375 L 47.9375 16.34375 C 48.054688 16.039063 48.011719 15.695313 47.824219 15.425781 C 47.636719 15.15625 47.328125 14.996094 47 15 L 16.28125 15 L 14.15625 6.46875 C 14.15625 6.449219 14.15625 6.425781 14.15625 6.40625 C 13.773438 5.117188 13.277344 4 12.375 3.1875 C 11.472656 2.375 10.203125 2 8.65625 2 L 2 2 C 1.96875 2 1.9375 2 1.90625 2 C 1.875 2 1.84375 2 1.8125 2 Z M 16.78125 17 L 45.5625 17 L 39.125 34 L 21 34 Z M 26 44 C 27.117188 44 28 44.882813 28 46 C 28 47.117188 27.117188 48 26 48 C 24.882813 48 24 47.117188 24 46 C 24 44.882813 24.882813 44 26 44 Z M 39 44 C 40.117188 44 41 44.882813 41 46 C 41 47.117188 40.117188 48 39 48 C 37.882813 48 37 47.117188 37 46 C 37 44.882813 37.882813 44 39 44 Z"
                stroke-linecap="round"
              />
            </g>
          </svg>
          <!-- <span class="sr-only">Notifications</span> -->
          <div
            class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -end-1 dark:border-gray-900"
          >
            {{ cart.data.count }}
          </div>
        </a>

        <button
          v-if="auth.user"
          type="button"
          class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 gap-5"
          id="user-menu-button"
          aria-expanded="false"
          data-dropdown-toggle="user-dropdown"
          data-dropdown-placement="bottom"
        >
          <span class="sr-only">Open user menu</span>

          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-7"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
            />
          </svg>
        </button>
        <div v-else>
          <Link
            :href="route('register')"
            v-if="canRegister"
            class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800"
          >
            <span
              class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0"
            >
              Register
            </span>
          </Link>
          <Link
            :href="route('login')"
            type="button"
            class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-3 text-center me-2 mb-2"
          >
            Login
          </Link>
        </div>
        <!-- Dropdown menu -->
        <div
          v-if="auth.user"
          class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
          id="user-dropdown"
        >
          <div class="px-4 py-3">
            <span class="block text-sm text-gray-900 dark:text-white">{{
              auth.user.name
            }}</span>
            <span
              class="block text-sm text-gray-500 truncate dark:text-gray-400"
              >{{ auth.user.email }}</span
            >
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
            <li>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Dashboard</a
              >
            </li>
            <li>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Settings</a
              >
            </li>
            <li>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Earnings</a
              >
            </li>
            <li>
              <Link
                :href="route('logout')"
                method="POST"
                as="button"
                class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                >Sign out</Link
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const canLogin = computed(() => usePage().props.canLogin);
const canRegister = computed(() => usePage().props.canRegister);
const auth = computed(() => usePage().props.auth);
const cart = computed(() => usePage().props.cart);
</script>
