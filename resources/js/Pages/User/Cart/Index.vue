<template>
  <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
      Shopping Cart
    </h2>

    <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
      <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
        <ul class="space-y-6">
          <li
            v-for="product in products"
            :key="product.id"
            class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6"
          >
            <div
              v-if="product.deleted_at"
              class="bg-red-100 border border-red-400 text-sm font text-red-700 px-4 py-2 mb-5 rounded relative"
              role="alert"
            >
              <strong class="font-bold mr-2">⚠ Warning!</strong>
              <span class="block sm:inline"
                >This product is out of stock or has been deleted by the admin.
                You cannot check it out.</span
              >
            </div>

            <div
              class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0"
            >
              <input
                type="checkbox"
                class="rounded"
                :class="
                  product.deleted_at !== null ||
                  product.stock <= 0 ||
                  (cartItems[itemId(product.id)]?.quantity ?? 0) > product.stock
                    ? 'cursor-not-allowed'
                    : ''
                "
                :value="product.id"
                v-model="checkoutItems"
                :disabled="
                  product.deleted_at !== null ||
                  product.stock <= 0 ||
                  (cartItems[itemId(product.id)]?.quantity ?? 0) > product.stock
                "
              />

              <a href="#" class="shrink-0 md:order-1">
                <img
                  class="h-20 w-20 dark:hidden"
                  :src="product.image"
                  :alt="product.image"
                />
                <img
                  class="hidden h-20 w-20 dark:block"
                  :src="product.image"
                  :alt="product.image"
                />
              </a>

              <label for="counter-input" class="sr-only"
                >Choose quantity:</label
              >
              <div
                class="flex items-center justify-between md:order-3 md:justify-end"
              >
                <div class="flex items-center">
                  <p
                    v-if="product.stock === 0"
                    class="text-red-600 mr-5 text-sm"
                  >
                    Out of stock!
                  </p>
                  <p
                    v-else-if="product.stock <= 5"
                    class="text-orange-600 mr-5 text-sm"
                  >
                    Only {{ product.stock }} left in stock!
                  </p>
                  <p v-else class="text-green-600 mr-5 text-sm">
                    In stock: {{ product.stock }}
                  </p>
                  <button
                    @click.prevent="
                      update(product, getCartQuantity(product) - 1)
                    "
                    type="button"
                    id="decrement-button"
                    :disabled="
                      (cartItems[itemId(product.id)]?.quantity ?? 0) <= 1 ||
                      !!product.deleted_at
                    "
                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                    :class="[
                      cartItems[itemId(product.id)]?.quantity > 1 &&
                      !product.deleted_at
                        ? ''
                        : 'cursor-not-allowed text-gray-300 dark:text-gray-500',
                    ]"
                  >
                    <MinusIcon
                      class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                    />
                  </button>
                  <input
                    type="text"
                    id="counter-input"
                    data-input-counter
                    class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                    placeholder=""
                    v-model="cartItems[itemId(product.id)].quantity"
                    required
                    disabled
                  />
                  <button
                    @click.prevent="
                      update(product, getCartQuantity(product) + 1)
                    "
                    type="button"
                    id="increment-button"
                    :disabled="
                      !!product.deleted_at ||
                      product.stock <= 0 ||
                      (cartItems[itemId(product.id)]?.quantity ?? 0) >=
                        product.stock
                    "
                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                    :class="[
                      !!product.deleted_at ||
                      product.stock <= 0 ||
                      (cartItems[itemId(product.id)]?.quantity ?? 0) >=
                        product.stock
                        ? 'cursor-not-allowed text-gray-300 dark:text-gray-500'
                        : '',
                    ]"
                  >
                    <PlusIcon
                      class="h-2.5 w-2.5 text-gray-900 dark:text-white"
                    />
                  </button>
                </div>
                <div class="text-end md:order-4 md:w-32">
                  <p class="text-base font-bold text-gray-900 dark:text-white">
                    ${{ product.price }}
                  </p>
                </div>
              </div>

              <div
                class="w-full min-w-0 flex-1 space-y-4 md:order-2 md:max-w-md"
              >
                <a
                  :href="route('product.view', product.slug)"
                  class="text-base font-medium text-gray-900 hover:underline dark:text-white"
                  >{{ product.title }}</a
                >

                <div class="flex items-center gap-4">
                  <button
                    disabled
                    type="button"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 hover:underline dark:text-gray-400 dark:hover:text-white cursor-not-allowed"
                  >
                    <HeartIcon
                      class="me-1.5 h-5 w-5 text-gray-500 dark:text-gray-400"
                    />
                    Add to Favorites
                  </button>

                  <button
                    @click="removeProduct(product)"
                    class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500"
                  >
                    <XMarkIcon
                      class="me-1.5 h-5 w-5 text-red-600 dark:text-red-500"
                    />
                    Remove
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>

      <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
        <div
          class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6"
        >
          <p class="text-xl font-semibold text-gray-900 dark:text-white">
            Order summary
          </p>

          <div class="space-y-4">
            <div class="space-y-2">
              <dl class="flex items-center justify-between gap-4">
                <dt
                  class="text-base font-normal text-gray-500 dark:text-gray-400"
                >
                  Original price
                </dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">
                  ${{ totalPrice }}
                </dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt
                  class="text-base font-normal text-gray-500 dark:text-gray-400"
                >
                  Savings
                </dt>
                <dd class="text-base font-medium text-green-600">0</dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt
                  class="text-base font-normal text-gray-500 dark:text-gray-400"
                >
                  Store Pickup
                </dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">
                  0
                </dd>
              </dl>

              <dl class="flex items-center justify-between gap-4">
                <dt
                  class="text-base font-normal text-gray-500 dark:text-gray-400"
                >
                  Tax
                </dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">
                  0
                </dd>
              </dl>
            </div>

            <dl
              class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700"
            >
              <dt class="text-base font-bold text-gray-900 dark:text-white">
                Total
              </dt>
              <dd class="text-base font-bold text-gray-900 dark:text-white">
                ${{ totalPrice }}
              </dd>
            </dl>
          </div>

          <form @submit.prevent="checkout">
            <PrimaryButton
              type="submit"
              class="w-full"
              button-text="Checkout"
            />
          </form>

          <div class="flex items-center justify-center gap-2">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
              or
            </span>
            <a
              :href="route('user.home')"
              title=""
              class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500"
            >
              Continue Shopping
              <svg
                class="h-5 w-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 12H5m14 0-4 4m4-4-4-4"
                />
              </svg>
            </a>
          </div>
        </div>

        <div
          class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6"
        >
          <form class="space-y-4">
            <div>
              <label
                for="voucher"
                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
              >
                Do you have a voucher or gift card?
              </label>
              <input
                type="text"
                id="voucher"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                placeholder=""
                required
              />
            </div>
            <button
              type="submit"
              class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
            >
              Apply Code
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import UserLayout from "../Layouts/UserLayout.vue";
import { usePage, router } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {
  PlusIcon,
  MinusIcon,
  XMarkIcon,
  HeartIcon,
} from "@heroicons/vue/24/outline";

defineOptions({ layout: UserLayout });

const products = computed(() => usePage().props.products);
const cartItems = computed(() => usePage().props.cartItems);
const itemId = (id) => (cartItems.value[id] ? id : -1);
const checkoutItems = ref([]);
const totalPrice = computed(() => {
  return (
    Object.values(cartItems.value)
      ?.filter((item) => checkoutItems.value.includes(item.product_id)) // Only selected items
      .reduce((sum, item) => {
        const product = products.value.find((p) => p.id === item.product_id); // Find matching product
        return sum + (product ? product.price * item.quantity : 0);
      }, 0) ?? null
  );
});

const getCartQuantity = computed(() => {
  return (product) => cartItems.value[itemId(product.id)]?.quantity ?? 1;
});

const update = (product, quantity) => {
  router.patch(route("cart.updateQuantity", product), {
    quantity,
  });
};

const removeProduct = (product) => {
  router.visit(route("cart.remove", product), {
    method: "delete",
    onSuccess: (page) => {
      if (page.props.flash.success) {
        toast.success("Item removed from the cart successfully");
      }
    },
  });
};

const checkout = () => {
  router.visit(route("checkout.store"), {
    method: "post",
    data: { checkoutItems: checkoutItems.value },
    onSuccess: () => {
      if (usePage().props.flash.error) {
        toast.error(usePage().props.flash.error);
      }
    },
  });
};
</script>
