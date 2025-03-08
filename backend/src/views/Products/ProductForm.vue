<template>
  <div v-if="isLoading" class="min-h-full flex items-center justify-center">
    <Spinner />
  </div>

  <form
    v-else
    @submit.prevent="onSubmit"
    class="grid grid-cols-1 md:grid-cols-4 gap-10"
  >
    <!-- General Information (Left Side, Takes 2 Columns) -->
    <div class="md:col-span-3 flex flex-col">
      <Accordion title="General">
        <div class="flex flex-col gap-4">
          <CustomInput v-model="product.title" label="Product Title" />
          <InputError
            v-if="errors.title"
            v-for="(error, index) in errors.title"
            :key="index"
            :message="error"
          />
          <CustomInput
            type="richtext"
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
          <CustomInput type="number" v-model="product.price" label="Price" />
          <InputError
            v-if="errors.price"
            v-for="(error, index) in errors.price"
            :key="index"
            class="mt-2"
            :message="error"
          />

          <!-- File Upload with File Name Display -->
          <div class="flex items-center gap-4">
            <div
              class="relative border-2 border-dashed border-gray-300 rounded-lg p-5 flex flex-col items-center justify-center cursor-pointer hover:border-indigo-500"
            >
              <CustomInput
                type="file"
                label="Product Image"
                @change="handleFileChange"
              />
              <CameraIcon class="w-10 h-10 text-teal-600" />
              <p class="text-sm text-gray-500 mt-2">Click or drag to upload</p>
            </div>
            <img
              v-if="product.image_url"
              :src="product.image_url"
              alt="Preview"
              class="mt-2 w-32 h-32 object-cover border rounded"
            />

            <span
              v-if="product.image_url"
              class="text-gray-700 text-sm flex items-center gap-2"
            >
              {{ product.image?.name ?? extractFilename(product.image_url) }}
              <button
                @click="removeImage"
                class="text-red-500 text-sm font-bold"
              >
                ✖
              </button>
            </span>
          </div>
          <InputError
            v-if="errors.file"
            v-for="(error, index) in errors.file"
            :key="index"
            class="mt-2"
            :message="error"
          />
        </div>
      </Accordion>
    </div>

    <!-- Right Side Column (Product Status & Category) -->
    <div class="flex flex-col gap-6">
      <!-- Product Status -->
      <Accordion title="Product Status">
        <div class="flex flex-col space-y-2 text-gray-700 font-semibold">
          <div
            v-for="(status, index) in productStatuses"
            :key="index"
            class="flex items-center gap-2"
          >
            <input
              type="radio"
              :id="'status-' + index"
              :value="status"
              v-model="product.status"
              class="cursor-pointer"
            />
            <label :for="'status-' + index" class="cursor-pointer">
              {{ capitalizedStatus(status) }}
            </label>
          </div>
          <InputError
            v-if="errors.status"
            v-for="(error, index) in errors.status"
            :key="index"
            class="mt-2"
            :message="error"
          />
        </div>
      </Accordion>

      <!-- Product Category -->
      <Accordion title="Product Category">
        <div
          class="flex flex-col items-center space-y-2 text-gray-700 font-semibold"
        >
          <h1>To be added</h1>
        </div>
      </Accordion>
    </div>

    <!-- Submit Button (Full Width) -->
    <div class="col-span-4 flex justify-end gap-2">
      <button
        type="button"
        @click="cancelChanges"
        class="bg-gray-500 text-white px-4 py-2 rounded"
      >
        Cancel
      </button>
      <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
        {{ product.id ? "Update" : "Create" }}
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import store from "../../store";
import axiosClient from "../../axios";
import CustomInput from "../../components/core/CustomInput.vue";
import InputError from "../../components/InputError.vue";
import Accordion from "../../components/core/Accordion.vue";
import Spinner from "../../components/core/Spinner.vue";
import { useCapitalizedFirstLetter } from "../../utils/utils";
import Swal from "sweetalert2";
import { CameraIcon } from "@heroicons/vue/24/outline";

const router = useRouter();
const props = defineProps({ productId: String });

const product = ref({
  id: "",
  title: "",
  image: null,
  image_url: "",
  description: "",
  price: "",
  status: "",
  category: "",
});
const productStatuses = ref([]);
const errors = ref({});
const isLoading = ref(false);

onMounted(async () => {
  isLoading.value = true; // ✅ Set loading before request

  try {
    const { data } = await axiosClient.get("products/statuses");
    productStatuses.value = data;

    if (props.productId) {
      const response = await store.dispatch("getProduct", props.productId);
      product.value = response.data;
    }
  } catch (error) {
    console.error("Error fetching product:", error);
  } finally {
    isLoading.value = false; // ✅ Ensure loading is set to false
  }
});

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    product.value.image = file;
    product.value.image_url = URL.createObjectURL(file);
  }
};

const removeImage = () => {
  product.value.image_url = "";
};

const onSubmit = async () => {
  isLoading.value = true; // ✅ Set loading state before submitting

  try {
    const action = product.value.id
      ? store.dispatch("updateProduct", product.value)
      : store.dispatch("createProduct", product.value);

    await action;
    Swal.fire({
      icon: "success",
      title: "Success!",
      text: product.value.id
        ? "Product updated successfully."
        : "Product created successfully.",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => {
      product.value.status === "archived"
        ? store.dispatch("getArchivedProducts")
        : store.dispatch("getProducts");
      router.push({ name: "app.products" });
    });
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors;
    }
    Swal.fire({
      icon: "error",
      title: "Error!",
      text: product.value.id
        ? "Product update failed."
        : "Product creation failed.",
      timer: 2000,
      showConfirmButton: false,
    });
  } finally {
    isLoading.value = false; // ✅ Ensure loading is set to false
  }
};

const cancelChanges = () => {
  router.push({ name: "app.products" });
};

const capitalizedStatus = (status) => {
  return status ? useCapitalizedFirstLetter(status) : "";
};
const extractFilename = (url) => {
  return url.split("/").pop(); // Gets the last part of the URL
};
</script>
