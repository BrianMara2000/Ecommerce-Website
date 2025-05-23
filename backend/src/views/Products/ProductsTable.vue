<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Per page</span>
        <select
          @change="getProducts(null)"
          v-model="perPage"
          class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{ products.total }} products</span>
      </div>
      <div>
        <input
          type="text"
          v-model="search"
          @change="getProducts(null)"
          class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Type to search products"
        />
      </div>
    </div>

    <div class="flex items-center space-x-4 border-b mb-6">
      <button
        @click="getProducts()"
        :class="
          !props.isArchived
            ? 'border-b-2 border-purple-500 text-purple-500'
            : 'text-gray-500 hover:text-gray-700'
        "
        class="py-2 font-semibold"
      >
        Products
      </button>
      <button
        @click="getArchivedProducts()"
        :class="
          props.isArchived
            ? 'border-b-2 border-purple-500 text-purple-500'
            : 'text-gray-500 hover:text-gray-700'
        "
        class="py-2 font-semibold"
      >
        Archive Products
      </button>
    </div>

    <table class="table-auto w-full">
      <thead>
        <tr class="animate-fade-in-down">
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[8%]"
            field="id"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >ID</TableHeaderCell
          >
          <TableHeaderCell
            class="border-b-2 p-2 text-left w-[10%]"
            field=""
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Image</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left"
            field="title"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Title</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left"
            field="price"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Price</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-right"
            field="stock"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Stock</TableHeaderCell
          >
          <TableHeaderCell
            class="border-b-2 p-2 w-[20%]"
            field="status"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Status</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortProducts"
            class="border-b-2 p-2 text-left w-[20%]"
            field="updated_at"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Last Updated</TableHeaderCell
          >
          <TableHeaderCell field="actions">Actions</TableHeaderCell>
        </tr>
      </thead>
      <tbody v-if="products.loading">
        <tr>
          <td colspan="7">
            <Spinner v-if="products.loading" class="mt-3" />
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr
          v-for="(product, index) of products.data"
          class="hover:hover:bg-purple-100 transition-all"
        >
          <!-- class="animate-fade-in-down"
          :style="{ 'animation-delay': `${index * 0.05}s`, 'z-index': '0' }" -->
          <td class="border-b p-2">{{ product.id }}</td>
          <td class="border-b p-2">
            <img class="w-16" :src="product.image_url" :alt="product.title" />
          </td>
          <td
            class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis"
          >
            {{ product.title }}
          </td>
          <td class="border-b p-2">$ {{ product.price }}</td>
          <td class="border-b p-2 text-center">{{ product.stock }}</td>
          <td class="border-b p-2">
            <ProductStatus :product="product.status" />
          </td>
          <td class="border-b p-2">{{ product.updated_at }}</td>
          <td class="border-b p-2">
            <Menu as="div" class="relative text-left flex justify-center">
              <div>
                <MenuButton
                  class="inline-flex items-center justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                >
                  <EllipsisVerticalIcon
                    class="h-5 w-5 text-indigo-500"
                    aria-hidden="true"
                  />
                </MenuButton>
              </div>
              <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <MenuItems
                  class="absolute z-10 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                >
                  <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="editProduct(product)"
                      >
                        <PencilIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-violet-400 group-hover:text-white"
                          aria-hidden="true"
                        />
                        Edit
                      </button>
                    </MenuItem>
                  </div>

                  <div v-if="product.status !== 'archive'" class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="archiveProduct(product)"
                      >
                        <ArchiveBoxArrowDownIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-violet-400 group-hover:text-white"
                          aria-hidden="true"
                        />
                        Move to Archive
                      </button>
                    </MenuItem>
                  </div>
                  <div v-if="product.status === 'archive'" class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="deleteProduct(product)"
                      >
                        <TrashIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-violet-400 group-hover:text-white"
                          aria-hidden="true"
                        />
                        Permanent Delete
                      </button>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-if="!products.loading"
      :class="!products.data.length ? 'justify-center' : 'justify-between'"
      class="flex items-center mt-5"
    >
      <span class="text-center" v-if="!products.data.length"
        >No products found</span
      >
      <span v-else>
        Showing from {{ products.from }} to {{ products.to }}
      </span>
      <nav
        v-if="products.total > products.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <a
          v-for="(link, i) of products.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click.prevent="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            i === 0 ? 'rounded-l-md' : '',
            i === products.links.length - 1 ? 'rounded-r-md' : '',
            !link.url ? 'bg-gray-100 text-gray-700' : '',
          ]"
          v-html="link.label"
        ></a>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";
import axiosClient from "../../axios";
import { PRODUCTS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/table/TableHeaderCell.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  EllipsisVerticalIcon,
  PencilIcon,
  ArchiveBoxArrowDownIcon,
  TrashIcon,
} from "@heroicons/vue/24/outline";
import Swal from "sweetalert2";
import ProductStatus from "./ProductStatus.vue";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const products = computed(() => store.state.products);
const sortField = ref("updated_at");
const sortDirection = ref("desc");
const props = defineProps(["isArchived"]);

const emit = defineEmits(["clickEdit", "update-is-archived"]);

onMounted(() => {
  getProducts();
});

const getProducts = (url = null) => {
  emit("update-is-archived", false);
  store.dispatch("getProducts", {
    url,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    search: search.value,
    perPage: perPage.value,
  });
};

const getArchivedProducts = (url = null) => {
  emit("update-is-archived", true);
  store.dispatch("getArchivedProducts", {
    url,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    search: search.value,
    perPage: perPage.value,
  });
};

const getForPage = (e, link) => {
  if (!link.url || link.active) {
    return;
  }
  getProducts(link.url);
};

const sortProducts = (field) => {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
  } else {
    sortField.value = field;
    sortDirection.value = "desc";
  }

  getProducts();
};

function editProduct(product) {
  emit("clickEdit", product);
}

function deleteProduct(product) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      store
        .dispatch("deleteProduct", product.id)
        .then(() => {
          Swal.fire("Deleted!", "Product has been deleted.", "success");
          getProducts();
        })
        .catch((error) => {
          Swal.fire(
            "Error!",
            "There was an error deleting the product.",
            "error"
          );
        });
    }
  });
}

function archiveProduct(product) {
  axiosClient
    .patch(`/products/${product.id}/archive`)
    .then(() => {
      Swal.fire("Archived!", "Product has been archived.", "success");
      getProducts();
    })
    .catch(() => {
      Swal.fire("Error!", "Something went wrong.", "error");
    });
}
</script>
