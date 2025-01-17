<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Users</h1>
    <button
      @click="showUserModal"
      type="button"
      class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer"
    >
      Add new admin
    </button>
  </div>

  <UserModal v-model="showModal" :user="userModel" @close="onModalClose" />

  <UsersTable @clickEdit="editUser" />
</template>

<script setup>
import { ref } from "vue";
import UserModal from "./UserModal.vue";
import UsersTable from "./UsersTable.vue";
import store from "../../store";

const DEFAULT_EMPTY_OBJECT = {
  id: "",
  name: "",
  email: "",
  phone: "",
  is_admin: null,
};

const showModal = ref(false);
const userModel = ref({ ...DEFAULT_EMPTY_OBJECT });

const showUserModal = () => {
  showModal.value = true;
};

const onModalClose = () => {
  userModel.value = { ...DEFAULT_EMPTY_OBJECT };
};

const editUser = (user) => {
  store.dispatch("getUserById", user.id).then(({ data }) => {
    userModel.value = data;
    showUserModal();
  });
};
</script>
