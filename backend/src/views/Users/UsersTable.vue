<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Per page</span>
        <select
          @change="getUsers(null)"
          v-model="perPage"
          class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{ users.total }} users</span>
      </div>
      <div>
        <input
          type="text"
          v-model="search"
          @change="getUsers(null)"
          class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Type to search users"
        />
      </div>
    </div>

    <table class="table-auto w-full">
      <thead>
        <tr class="animate-fade-in-down text-center">
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left w-[8%]"
            field="id"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >User ID</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left w-[10%]"
            field="name"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Name</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left w-[15%]"
            field="email"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Email</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left w-[15%]"
            field="phone"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Phone</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left"
            field="created_at"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Date added</TableHeaderCell
          >
          <TableHeaderCell
            @click="sortUsers"
            class="border-b-2 p-2 text-left"
            field="isAdmin"
            :sort-field="sortField"
            :sort-direction="sortDirection"
            >Role</TableHeaderCell
          >
          <TableHeaderCell field="actions" class="text-center w-[20%]"
            >Actions</TableHeaderCell
          >
        </tr>
      </thead>
      <tbody v-if="users.loading">
        <tr>
          <td colspan="7">
            <Spinner v-if="users.loading" class="mt-3" />
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr
          v-for="(user, index) of users.data"
          class="hover:hover:bg-purple-100 transition-all"
        >
          <!-- class="animate-fade-in-down"
          :style="{ 'animation-delay': `${index * 0.05}s`, 'z-index': '0' }" -->
          <td class="border-b pl-6 p-2">{{ user.id }}</td>
          <td class="border-b p-2">{{ user.name }}</td>
          <td class="border-b p-2">{{ user.email }}</td>
          <td class="border-b p-2">{{ user.phone }}</td>
          <td class="border-b p-2">{{ formatDate(user.created_at) }}</td>
          <td class="border-b p-2">{{ user.is_admin ? "Admin" : "User" }}</td>
          <td class="border-b p-2 flex items-center justify-center">
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
                        @click="editUser(user)"
                      >
                        <PencilIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-violet-400"
                          aria-hidden="true"
                        />
                        Edit
                      </button>
                    </MenuItem>
                  </div>

                  <div class="px-1 py-1">
                    <MenuItem v-slot="{ active }">
                      <button
                        :class="[
                          active ? 'bg-violet-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]"
                        @click="deleteUser(user)"
                      >
                        <TrashIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-violet-400"
                          aria-hidden="true"
                        />
                        Delete
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
      v-if="!users.loading"
      :class="!users.data.length ? 'justify-center' : 'justify-between'"
      class="flex items-center mt-5"
    >
      <span class="text-center" v-if="!users.data.length">No users found</span>
      <span v-else> Showing from {{ users.from }} to {{ users.to }} </span>
      <nav
        v-if="users.total > users.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <a
          v-for="(link, i) of users.links"
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
            i === 0
              ? !users.prev_page_url
                ? ' cursor-not-allowed'
                : 'rounded-r-md '
              : '',
            ,
            i === users.links.length - 1
              ? !users.next_page_url
                ? ' cursor-not-allowed'
                : 'rounded-r-md '
              : '',
            !link.url ? ' ' : 'bg-gray-100 text-gray-700 cursor-pointer',
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
import { USERS_PER_PAGE } from "../../constants";
import TableHeaderCell from "../../components/core/table/TableHeaderCell.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  EllipsisVerticalIcon,
  PencilIcon,
  TrashIcon,
} from "@heroicons/vue/24/outline";
import Swal from "sweetalert2";

const perPage = ref(USERS_PER_PAGE);
const search = ref("");
const users = computed(() => store.state.users);

const sortField = ref("updated_at");
const sortDirection = ref("desc");

const formatDate = (isoDate) => {
  const date = new Date(isoDate);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleString(undefined, options); // Adjusts to the user's locale
};

const emit = defineEmits(["clickEdit"]);

onMounted(() => {
  getUsers();
});

const getUsers = (url = null) => {
  store.dispatch("getUsers", {
    url,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
    search: search.value,
    perPage: perPage.value,
  });
};

const editUser = (user) => {
  emit("clickEdit", user);
};

const deleteUser = (user) => {
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
        .dispatch("deleteUser", user.id)
        .then(() => {
          Swal.fire("Deleted!", "User has been deleted.", "success");
          getUsers();
        })
        .catch((error) => {
          Swal.fire("Error!", "There was an error deleting the user.", "error");
        });
    }
  });
};

const getForPage = (e, link) => {
  if (!link.url || link.active) {
    return;
  }
  getUsers(link.url);
};

const sortUsers = (field) => {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === "desc" ? "asc" : "desc";
  } else {
    sortField.value = field;
    sortDirection.value = "desc";
  }

  getUsers();
};
</script>
