<template>
  <div>
    <div class="mb-2">
      <label class="text-sm">Address1</label>
      <CustomInput class="mb-2" v-model="address.address1" />
      <InputError
        v-if="errors['address1']"
        v-for="(error, index) in errors['address1']"
        :key="index"
        class="mt-2"
        :message="error"
      />
    </div>
    <div class="mb-2">
      <label class="text-sm">Address2</label>
      <CustomInput class="mb-2" v-model="address.address2" />
      <InputError
        v-if="errors['address2']"
        v-for="(error, index) in errors['address2']"
        :key="index"
        class="mt-2"
        :message="error"
      />
    </div>
    <div class="flex justify-between">
      <div class="mb-2">
        <label class="text-sm">City</label>
        <CustomInput class="mb-2" v-model="address.city" />
        <InputError
          v-if="errors['city']"
          v-for="(error, index) in errors['city']"
          :key="index"
          class="mt-2"
          :message="error"
        />
      </div>
      <div class="mb-2">
        <label class="text-sm">Zip code</label>
        <CustomInput class="mb-2" v-model="address.zipcode" />
        <InputError
          v-if="errors['zipcode']"
          v-for="(error, index) in errors['zipcode']"
          :key="index"
          class="mt-2"
          :message="error"
        />
      </div>
    </div>
    <div class="grid sm:grid-cols-2 gap-8">
      <div class="mb-2">
        <label class="text-sm">Country</label>

        <select
          v-model="address.country_code"
          class="block w-full mt-1 px-3 py-2 pr-8 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm rounded-md"
        >
          <option disabled value="">Select a country</option>
          <option
            v-for="country in countries"
            :key="country.code"
            :value="country.code"
          >
            {{ country.name }}
          </option>
        </select>

        <InputError
          v-if="errors['country']"
          v-for="(error, index) in errors['country']"
          :key="index"
          class="mt-2"
          :message="error"
        />
      </div>
      <div class="mb-2">
        <label class="text-sm">State</label>
        <select
          v-if="getCountryStates"
          v-model="address.state"
          class="block w-full mt-1 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm rounded-md"
        >
          <option disabled value="">Select a state</option>
          <option
            v-for="[code, state] in Object.entries(getCountryStates)"
            :key="code"
            :value="state"
          >
            {{ state }}
          </option>
        </select>
        <CustomInput v-else class="mb-2" v-model="address.state" />
        <InputError
          v-if="errors['state']"
          v-for="(error, index) in errors['state']"
          :key="index"
          class="mt-2"
          :message="error"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, inject } from "vue";
import CustomInput from "./CustomInput.vue";
import InputError from "../InputError.vue";

const props = defineProps({
  address: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
    default: () => ({}),
  },
});

const countries = inject("countries", []);

const getCountryStates = computed(() => {
  const country = countries.value.find(
    (c) => c.code === props.address.country_code
  );
  return country?.states ? JSON.parse(country.states) : null;
});
</script>
