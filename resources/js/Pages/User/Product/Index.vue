<template>
  <UserLayout>
    <div class="bg-white">
      <div
        class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
      >
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
          Latest Product List
        </h2>

        <div
          class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
        >
          <a
            v-for="product in products.data"
            :key="product.id"
            class="group relative cursor-pointer"
            :href="route('product.view', product.slug)"
          >
            <div
              class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 lg:h-80 cursor-pointer"
            >
              <img
                :src="product.image"
                class="h-full w-full object-cover object-center lg:h-full lg:w-full"
              />
            </div>
            <div class="mt-4 flex justify-between">
              <div>
                <h3 class="text-sm text-gray-700">
                  <a :href="product.href">
                    <span aria-hidden="true" class="absolute inset-0" />
                    {{ product.title }}
                  </a>
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ product.color }}</p>
              </div>
              <p class="text-sm font-medium text-gray-900">
                ${{ product.price }}
              </p>
            </div>
          </a>
        </div>
        <Pagination :products="products" />
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import { ref } from "vue";
import UserLayout from "../Layouts/UserLayout.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
  products: {
    type: Object,
    required: true,
  },
});
</script>
