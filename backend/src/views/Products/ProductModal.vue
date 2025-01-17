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
                    product.id
                      ? `Update Product: "${props.product.title}" `
                      : "Create new product"
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
                  <div class="mb-2">
                    <CustomInput
                      class="mb-2"
                      v-model="product.title"
                      label="Product Title"
                    />
                    <InputError
                      v-if="errors.title"
                      v-for="(error, index) in errors.title"
                      :key="index"
                      class="mt-2"
                      :message="error"
                    />
                  </div>

                  <div class="mb-2">
                    <CustomInput
                      type="file"
                      class="mb-2"
                      label="Product Image"
                      @change="(file) => (product.image = file)"
                    />
                    <InputError
                      v-if="errors.file"
                      v-for="(error, index) in errors.file"
                      :key="index"
                      class="mt-2"
                      :message="error"
                    />
                  </div>
                  <div class="mb-2">
                    <CustomInput
                      type="textarea"
                      class="mb-2"
                      v-model="product.description"
                      label="Description"
                    />
                    <InputError
                      v-if="errors.description"
                      v-for="(error, index) in errors.description"
                      :key="index"
                      class="mt-2"
                      :message="error"
                    />
                  </div>
                  <div class="mb-2">
                    <CustomInput
                      type="number"
                      class="mb-2"
                      v-model="product.price"
                      label="Price"
                      prepend="$"
                    />
                    <InputError
                      v-if="errors.price"
                      v-for="(error, index) in errors.price"
                      :key="index"
                      class="mt-2"
                      :message="error"
                    />
                  </div>
                </div>
                <footer
                  class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                >
                  <button
                    type="submit"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    @click="closeModal"
                    ref="cancelButtonRef"
                  >
                    Cancel
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
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";
import CustomInput from "../../components/core/CustomInput.vue";
import InputError from "../../components/InputError.vue";

const loading = ref(false);
const errors = ref({});

const props = defineProps({
  modelValue: Boolean,
  product: {
    required: true,
    type: Object,
  },
});

const product = ref({
  id: props.product.id,
  title: props.product.title,
  image: props.product.image,
  description: props.product.description,
  price: props.product.price,
});

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

onUpdated(() => {
  product.value = {
    id: props.product.id,
    title: props.product.title,
    image: props.product.image,
    description: props.product.description,
    price: props.product.price,
  };
});

const onSubmit = () => {
  loading.value = true;

  const action = product.value.id
    ? store.dispatch("updateProduct", product.value)
    : store.dispatch("createProduct", product.value);

  action
    .then(() => {
      store.dispatch("getProducts");
      closeModal();
    })
    .catch((error) => {
      if (error.response.status === 422) {
        errors.value = error.response.data.errors;
      }
    })
    .finally(() => {
      loading.value = false; // Always stop loading
    });
};
</script>
