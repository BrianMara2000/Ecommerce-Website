<template>
  <!-- component -->
  <section
    class="relative mt-8 max-w-screen-xl flex flex-wrap justify-between mx-auto p-4 mb-32"
  >
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-0">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mx-auto max-md:px-2">
        <div class="img">
          <div class="img-box max-lg:mx-auto">
            <img
              :src="product.image"
              alt="Yellow Tropical Printed Shirt image"
              class="mx-auto w-9/12 object-contain"
            />
          </div>
        </div>
        <div
          class="data w-full pr-0 xl:justify-start justify-center flex items-center max-lg:pb-10 xl:my-2 lg:my-5 my-0"
        >
          <div class="data w-full max-w-xl">
            <!-- <p class="text-lg font-medium leading-8 text-indigo-600 mb-4">
                Clothing&nbsp; /&nbsp; Menswear
              </p> -->
            <h2
              class="font-manrope font-bold text-3xl leading-10 text-gray-900 mb-2 capitalize"
            >
              {{ product.title }}
            </h2>
            <div class="flex flex-col sm:flex-row sm:items-center mb-6">
              <h6
                class="font-manrope font-semibold text-2xl leading-9 text-gray-900 pr-5 sm:border-r border-gray-200 mr-5"
              >
                ${{ product.price }}
              </h6>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_12029_1640)">
                      <path
                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                        fill="#FBBF24"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_12029_1640">
                        <rect width="20" height="20" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_12029_1640)">
                      <path
                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                        fill="#FBBF24"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_12029_1640">
                        <rect width="20" height="20" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_12029_1640)">
                      <path
                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                        fill="#FBBF24"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_12029_1640">
                        <rect width="20" height="20" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_12029_1640)">
                      <path
                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                        fill="#FBBF24"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_12029_1640">
                        <rect width="20" height="20" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g clip-path="url(#clip0_8480_66029)">
                      <path
                        d="M9.10326 2.31699C9.47008 1.57374 10.5299 1.57374 10.8967 2.31699L12.7063 5.98347C12.8519 6.27862 13.1335 6.48319 13.4592 6.53051L17.5054 7.11846C18.3256 7.23765 18.6531 8.24562 18.0596 8.82416L15.1318 11.6781C14.8961 11.9079 14.7885 12.2389 14.8442 12.5632L15.5353 16.5931C15.6754 17.41 14.818 18.033 14.0844 17.6473L10.4653 15.7446C10.174 15.5915 9.82598 15.5915 9.53466 15.7446L5.91562 17.6473C5.18199 18.033 4.32456 17.41 4.46467 16.5931L5.15585 12.5632C5.21148 12.2389 5.10393 11.9079 4.86825 11.6781L1.94038 8.82416C1.34687 8.24562 1.67438 7.23765 2.4946 7.11846L6.54081 6.53051C6.86652 6.48319 7.14808 6.27862 7.29374 5.98347L9.10326 2.31699Z"
                        fill="#F3F4F6"
                      />
                    </g>
                    <defs>
                      <clipPath id="clip0_8480_66029">
                        <rect width="20" height="20" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>
                </div>
                <span class="pl-2 font-normal leading-7 text-gray-500 text-sm"
                  >1624 review</span
                >
              </div>
            </div>
            <p class="text-gray-500 text-base font-normal mb-5">
              <span v-html="truncatedDescription(product)"></span>
              <a
                v-if="product.description.length > maxLength"
                @click.prevent="toggleShowMore"
                class="text-indigo-600 cursor-pointer"
              >
                {{ isExpanded ? "Show Less" : "More..." }}
              </a>
            </p>

            <p v-if="product.stock <= 5" class="text-red-600 font-semibold">
              Only {{ product.stock }} left in stock!
            </p>

            <div
              class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-8 items-center"
            >
              <div class="flex sm:items-center sm:justify-center w-full">
                <button
                  @click="decrement"
                  :disabled="quantity <= 1"
                  class="group py-4 px-6 border border-gray-400 rounded-l-full bg-white transition-all duration-300 hover:bg-gray-100 hover:shadow-sm hover:shadow-gray-300"
                >
                  <MinusIcon
                    class="w-[22px] h-[22px] font-bold"
                    :class="
                      quantity <= 1 ? 'text-gray-600 cursor-not-allowed' : ''
                    "
                  />
                </button>
                <input
                  inputmode="numeric"
                  :placeholder="quantity"
                  :value="quantity"
                  disabled
                  required
                  class="font-semibold text-gray-900 cursor-pointer text-lg py-[13px] px-6 w-full sm:max-w-[118px] outline-0 border-y border-gray-400 bg-transparent placeholder:text-gray-900 text-center hover:bg-gray-50"
                />
                <button
                  @click="increment"
                  :disabled="quantity >= product.stock || product.stock === 0"
                  :class="
                    quantity >= product.stock
                      ? 'text-gray-600 cursor-not-allowed'
                      : ''
                  "
                  class="group py-4 px-6 border border-gray-400 rounded-r-full bg-white transition-all duration-300 hover:bg-gray-100 hover:shadow-sm hover:shadow-gray-300"
                >
                  <PlusIcon class="w-[22px] h-[22px] font-bold" />
                </button>
              </div>
              <button
                v-if="product.stock > 0"
                :disabled="product.stock === 0"
                @click="addToCart(product, quantity)"
                class="group py-4 px-5 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-lg w-full flex items-center justify-center gap-3 transition-all duration-500 hover:bg-indigo-100"
              >
                <ShoppingCartIcon class="w-[22px] h-[22px] font-bold" /> Add to
                cart
              </button>
              <span v-else class="flex-1 text-red-600 font-bold text-center"
                >Out of Stock</span
              >
            </div>
            <div class="flex items-center justify-between gap-3">
              <button
                disabled
                class="group transition-all duration-500 p-4 rounded-full bg-indigo-50 hover:bg-indigo-100 hover:shadow-sm hover:shadow-indigo-300 gap-4 cursor-not-allowed"
              >
                <HeartIcon
                  class="w-[22px] h-[22px] font-bold text-indigo-600"
                />
              </button>
              <button
                :disabled="product.stock === 0"
                :class="
                  product.stock === 0 ? 'cursor-not-allowed' : 'cursor-pointer'
                "
                class="text-center w-full px-5 py-4 rounded-[100px] bg-indigo-600 flex items-center justify-center font-semibold text-lg text-white shadow-sm transition-all duration-500 hover:bg-indigo-700 hover:shadow-indigo-400"
              >
                Buy Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import UserLayout from "../Layouts/UserLayout.vue";
import { useCart } from "@/utils/utils";
import {
  HeartIcon,
  MinusIcon,
  PlusIcon,
  ShoppingCartIcon,
} from "@heroicons/vue/24/outline";

defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const maxLength = 150; // Maximum characters before truncation
const isExpanded = ref(false);
const quantity = ref(1);

const truncatedDescription = computed(() => {
  return (product) =>
    isExpanded.value
      ? product.description
      : product.description.length > maxLength
      ? product.description.substring(0, maxLength) + "..."
      : product.description;
});

const toggleShowMore = () => {
  isExpanded.value = !isExpanded.value;
};

const { addToCart } = useCart();

const increment = () => {
  quantity.value++;
};

const decrement = () => {
  quantity.value--;
};

defineOptions({ layout: UserLayout });
</script>
