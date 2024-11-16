<script setup>
import UserLayout from "../User/Layouts/UserLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({ layout: UserLayout });

defineProps({
  mustVerifyEmail: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: "",
  },
});
</script>

<template>
  <div
    class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
  >
    <Head title="Profile" />

    <header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
    </header>

    <div class="py-12 flex flex-col lg:flex-row lg:justify-between">
      <div class="w-full lg:w-1/2 mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow-md sm:rounded-lg">
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </div>
      </div>
      <div class="w-full lg:w-1/2 mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow-md sm:rounded-lg">
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-md sm:rounded-lg">
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>
    </div>
  </div>
</template>
