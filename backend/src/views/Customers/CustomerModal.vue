<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/50" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-2 text-left align-middle shadow-xl transition-all"
            >
              <Spinner
                v-if="loading"
                class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"
              />
              <header class="py-3 px-4 flex justify-between items-center">
                <DialogTitle
                  as="h3"
                  class="text-lg leading-6 font-medium text-gray-900"
                >
                  {{
                    `${props.customer.first_name || ""} ${
                      props.customer.last_name || ""
                    }`
                  }}
                </DialogTitle>
                <button
                  @click="closeModal()"
                  class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18 18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </header>
              <form @submit.prevent="onSubmit">
                <div v-if="!loading" class="bg-white px-4 pt-5 pb-4">
                  <div class="flex justify-between">
                    <div class="mb-2">
                      <label for="First Name" class="text-sm">First Name</label>
                      <CustomInput class="mb-2" v-model="customer.first_name" />
                      <InputError
                        v-if="errors.first_name"
                        v-for="(error, index) in errors.first_name"
                        :key="index"
                        class="mt-2"
                        :message="error"
                      />
                    </div>
                    <div class="mb-2">
                      <label for="Last Name" class="text-sm">Last Name</label>
                      <CustomInput class="mb-2" v-model="customer.last_name" />
                      <InputError
                        v-if="errors.last_name"
                        v-for="(error, index) in errors.last_name"
                        :key="index"
                        class="mt-2"
                        :message="error"
                      />
                    </div>
                  </div>

                  <div class="flex justify-between">
                    <div class="mb-2">
                      <label for="User Email" class="text-sm">Email</label>

                      <CustomInput
                        type="email"
                        class="mb-2"
                        v-model="customer.email"
                      />

                      <InputError
                        v-if="errors.email"
                        v-for="(error, index) in errors.email"
                        :key="index"
                        class="mt-2"
                        :message="error"
                      />
                    </div>
                    <div class="mb-2">
                      <label for="Phone Number" class="text-sm">Phone</label>

                      <CustomInput class="mb-2" v-model="customer.phone" />

                      <InputError
                        v-if="errors.phone"
                        v-for="(error, index) in errors.phone"
                        :key="index"
                        class="mt-2"
                        :message="error"
                      />
                    </div>
                  </div>

                  <h2 class="my-3">Billing Address</h2>
                  <AddressFields
                    :address="billing_address || {}"
                    :errors="errors.billing || {}"
                  />

                  <header
                    class="my-3 flex-col sm:flex sm:flex-row sm:justify-between"
                  >
                    <h3>Shipping Address</h3>
                    <div class="flex items-center gap-2 mt-5 sm:mt-0">
                      <input
                        type="checkbox"
                        @change="
                          $event.target.checked
                            ? (customer.shipping = { ...billing_address })
                            : (customer.shipping = null)
                        "
                        id="sameAsBillingAddress"
                        class="border-gray-300 rounded-md shadow-sm"
                      />
                      <p>Same as billing address</p>
                    </div>
                  </header>
                  <AddressFields
                    :address="shipping_address || {}"
                    :errors="errors.shipping || {}"
                  />
                </div>

                <footer
                  class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-between"
                >
                  <div class="flex flex-row-reverse">
                    <button
                      type="submit"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                      Save changes
                    </button>
                    <button
                      type="button"
                      class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                      @click="closeModal"
                      ref="cancelButtonRef"
                    >
                      Cancel
                    </button>
                  </div>
                  <button
                    type="button"
                    v-if="customer.id"
                    @click="deleteCustomer(customer)"
                    class="mt-3 w-full inline-flex justify-center text-red-500 rounded-md py-2 text-base font-medium sm:mt-0 sm:w-auto sm:text-sm"
                  >
                    <TrashIcon class="mr-2 h-5 w-5" />
                    <span>Delete</span>
                  </button>
                </footer>
              </form>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { computed, onUpdated, ref } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import { TrashIcon } from "@heroicons/vue/24/outline";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";
import CustomInput from "../../components/core/CustomInput.vue";
import InputError from "../../components/InputError.vue";
import AddressFields from "../../components/core/AddressFields.vue";
import Swal from "sweetalert2";

const loading = ref(false);

const errors = ref({
  billing: {},
  shipping: {},
});

const props = defineProps({
  modelValue: Boolean,
  customer: {
    required: true,
    type: Object,
  },
});

const customer = ref({});

const emit = defineEmits(["update:modelValue", "close"]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

function closeModal() {
  show.value = false;
  if (errors.value) {
    errors.value = {};
  }
  emit("close");
}

const billing_address = computed(() => customer.value.billing);
const shipping_address = computed(() => customer.value.shipping);

onUpdated(() => {
  customer.value = {
    id: props.customer.id,
    first_name: props.customer.first_name,
    last_name: props.customer.last_name,
    email: props.customer.email,
    phone: props.customer.phone,
    status: props.customer.status,
    billing: {
      address1: props.customer.billing_address?.address1 || "",
      address2: props.customer.billing_address?.address2 || "",
      city: props.customer.billing_address?.city || "",
      zipcode: props.customer.billing_address?.zipcode || "",
      country_code: props.customer.billing_address?.country.code || "",
      state: props.customer.billing_address?.state || "",
    },
    shipping: {
      address1: props.customer.shipping_address?.address1 || "",
      address2: props.customer.shipping_address?.address2 || "",
      city: props.customer.shipping_address?.city || "",
      zipcode: props.customer.shipping_address?.zipcode || "",
      country_code: props.customer.shipping_address?.country.code || "",
      state: props.customer.shipping_address?.state || "",
    },
  };
});

const onSubmit = () => {
  // console.log(customer.value);
  loading.value = true;
  const action = store.dispatch("updateCustomer", customer.value);
  action
    .then(() => {
      store.dispatch("getCustomers");
      closeModal();
    })
    .catch((error) => {
      if (error.response.status === 422) {
        const responseErrors = error.response.data.errors;
        errors.value = {
          billing: {},
          shipping: {},
        };
        // Map errors to billing and shipping fields
        for (const [key, value] of Object.entries(responseErrors)) {
          if (key.startsWith("billing.")) {
            errors.value.billing[key.replace("billing.", "")] = value;
          } else if (key.startsWith("shipping.")) {
            errors.value.shipping[key.replace("shipping.", "")] = value;
          } else {
            errors.value[key] = value;
          }
        }
      }
    })
    .finally(() => {
      loading.value = false; // Always stop loading
    });
};

const deleteCustomer = (customer) => {
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
        .dispatch("deleteUser", customer.id)
        .then(() => {
          Swal.fire("Deleted!", "Customer has been deleted.", "success");
          closeModal();
          store.dispatch("getCustomers");
        })
        .catch((error) => {
          closeModal();
          Swal.fire(
            "Error!",
            "There was an error deleting the customer.",
            "error"
          );
        });
    }
  });
};
</script>
