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
        class="relative flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse gap-5"
      >
        <Link
          :href="route('cart.index')"
          class="relative inline-flex items-center p-2 text-xs hover:bg-gray-200 font-medium text-center rounded-lg"
        >
          <ShoppingCartIcon class="w-7 h-7" />
          <div
            class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -end-1 dark:border-gray-900"
          >
            {{ cartCount }}
          </div>
        </Link>

        <div v-if="auth.user === null">
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

        <button
          v-else
          type="button"
          class="flex text-sm rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 gap-5"
          id="user-menu-button"
          aria-expanded="false"
          @click="toggleDropdown"
        >
          <span class="sr-only">Open user menu</span>

          <UserCircleIcon class="w-7 h-7" />
        </button>
        <!-- Dropdown menu -->
        <div
          ref="dropdownRef"
          v-if="auth.user && dropdownOpen"
          class="absolute z-50 right-0 top-12 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
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
              <Link
                :href="route('profile.edit')"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                @click="closeDropdown"
                >Profile</Link
              >
            </li>

            <li>
              <Link
                :href="route('my-order.index')"
                as="button"
                class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                @click="closeDropdown"
                >My Orders</Link
              >
            </li>
            <li>
              <Link
                :href="route('logout')"
                method="POST"
                as="button"
                class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                @click="closeDropdown"
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
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ShoppingCartIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
import { onClickOutside } from "@vueuse/core";

const canLogin = computed(() => usePage().props.canLogin);
const canRegister = computed(() => usePage().props.canRegister);
const auth = computed(() => usePage().props.auth);
const cartCount = computed(() => usePage().props.cartCount);

const dropdownOpen = ref(false);

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

const closeDropdown = () => {
  dropdownOpen.value = false;
};

const dropdownRef = ref(null);
onClickOutside(dropdownRef, () => {
  dropdownOpen.value = false;
});
</script>
