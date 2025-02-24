<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { onMounted, watch } from "vue";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const errors = usePage().props.errors;

const user = usePage().props.auth.user;
const customer = usePage().props.customer;
const countries = usePage().props.countries;
const billingAddress = usePage().props.billingAddress;
const shippingAddress = usePage().props.shippingAddress;

const form = useForm({
  first_name: customer?.first_name,
  last_name: customer?.last_name,
  email: user.email,
  phone: customer?.phone,
  billing: {
    address1: billingAddress.address1,
    address2: billingAddress.address2,
    city: billingAddress.city,
    zipcode: billingAddress.zipcode,
    country_code: billingAddress.country_code?.toLowerCase(),
    state: billingAddress.state,
  },
  shipping: {
    address1: shippingAddress.address1,
    address2: shippingAddress.address2,
    city: shippingAddress.city,
    zipcode: shippingAddress.zipcode,
    country_code: shippingAddress.country_code?.toLowerCase(),
    state: shippingAddress.state,
  },
});

const getBillingCountryStates = () => {
  const country = countries.find((c) => c.code === form.billing.country_code);
  return country && country.states ? JSON.parse(country.states) : null;
};

const getShippingCountryStates = () => {
  const country = countries.find((c) => c.code === form.shipping.country_code);
  return country && country.states ? JSON.parse(country.states) : null;
};

onMounted(() => {
  //Remove Item when user redirect to another page
  router.on("before", () => {
    localStorage.removeItem("profileFormData");
  });
  const savedForm = JSON.parse(localStorage.getItem("profileFormData"));
  if (savedForm) {
    form.first_name = savedForm.first_name || form.first_name;
    form.last_name = savedForm.last_name || form.last_name;
    form.email = savedForm.email || form.email;
    form.phone = savedForm.phone || form.phone;
    form.billing = {
      ...form.billing,
      ...savedForm.billing,
    };
    form.shipping = {
      ...form.shipping,
      ...savedForm.shipping,
    };
  }

  if (errors[0]) {
    toast.warning(errors[0]);
  }
});

// Watch for changes to form and save to localStorage
watch(
  form,
  (newForm) => {
    localStorage.setItem("profileFormData", JSON.stringify(newForm));
  },
  { deep: true } // Enables deep watching of nested fields within form
);

// After a successful submit, clear local storage
function updateProfile() {
  router.visit(route("profile.update"), {
    method: "patch",
    data: form,
    onSuccess: () => {
      localStorage.removeItem("profileFormData");
      toast.success("Profile updated successfully");
    },
    onError: (errors) => {
      // Keep data in local storage if there's an error
      toast.error("Please fill in the required field");
    },
  });
}
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

      <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
      </p>
    </header>

    <form @submit.prevent="updateProfile" class="mt-6 space-y-6">
      <div class="grid sm:grid-cols-2 gap-8">
        <div>
          <InputLabel for="first_name" value="First Name" />

          <TextInput
            @input="errors.first_name = null"
            id="first_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.first_name"
            autofocus
            autocomplete="first_name"
            :hasError="!!errors.first_name"
          />

          <InputError class="mt-2" v-if="errors" :message="errors.first_name" />
        </div>
        <div>
          <InputLabel for="last_name" value="Last Name" />

          <TextInput
            @input="errors.last_name = null"
            id="last_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.last_name"
            autofocus
            autocomplete="last_name"
            :hasError="!!errors.last_name"
          />

          <InputError class="mt-2" :message="errors.last_name" />
        </div>
      </div>

      <div>
        <InputLabel for="email" value="Email" />

        <TextInput
          @input="errors.email = null"
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          autocomplete="username"
          :hasError="!!errors.email"
        />

        <InputError class="mt-2" :message="errors.email" />
      </div>

      <div>
        <InputLabel for="phone" value="Phone" />

        <TextInput
          @input="errors.phone = null"
          id="phone"
          type="tel"
          class="mt-1 block w-full"
          v-model="form.phone"
          autocomplete="phone"
          :hasError="!!errors.phone"
        />

        <InputError class="mt-2" :message="errors.phone" />
      </div>

      <header>
        <h3 class="mt-20 text-lg font-medium text-gray-900">Billing Address</h3>
      </header>

      <div>
        <InputLabel for="address1" value="Address 1" />

        <TextInput
          @input="errors['billing.address1'] = null"
          id="address1"
          type="text"
          class="mt-1 block w-full"
          v-model="form.billing.address1"
          autocomplete="address1"
          :hasError="!!errors['billing.address1']"
        />

        <InputError class="mt-2" :message="errors['billing.address1']" />
      </div>

      <div>
        <InputLabel for="address2" value="Address 2" />

        <TextInput
          @input="errors['billing.address2'] = null"
          id="address2"
          type="text"
          class="mt-1 block w-full"
          v-model="form.billing.address2"
          autocomplete="address2"
          :hasError="!!errors['billing.address2']"
        />

        <InputError class="mt-2" :message="errors['billing.address2']" />
      </div>

      <div class="grid sm:grid-cols-2 gap-8">
        <div>
          <InputLabel for="city" value="City" />

          <TextInput
            @input="errors['billing.city'] = null"
            id="city"
            type="text"
            class="mt-1 block w-full"
            v-model="form.billing.city"
            autocomplete="city"
            :hasError="!!errors['billing.city']"
          />

          <InputError class="mt-2" :message="errors['billing.city']" />
        </div>

        <div>
          <InputLabel for="zipcode" value="Zip Code" />

          <TextInput
            @input="errors['billing.zipcode'] = null"
            id="zipcode"
            type="text"
            class="mt-1 block w-full"
            v-model="form.billing.zipcode"
            autocomplete="zipcode"
            :hasError="!!errors['billing.zipcode']"
          />

          <InputError class="mt-2" :message="errors['billing.zipcode']" />
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-8">
        <div>
          <InputLabel for="country" value="Country" />

          <TextInput
            @input="errors['billing.country_code'] = null"
            id="country"
            type="select"
            class="mt-1 block w-full"
            v-model="form.billing.country_code"
            autocomple="country"
            :hasError="!!errors['billing.country_code']"
          >
            <option value="">Select country</option>
            <template v-for="country in countries" :key="country.code">
              <option :value="country.code">
                {{ country.name }}
              </option>
            </template>
          </TextInput>

          <InputError class="mt-2" :message="errors['billing.country_code']" />
        </div>
        <div>
          <InputLabel for="states" value="States" />

          <TextInput
            @input="errors['billing.state'] = null"
            v-if="getBillingCountryStates()"
            id="states"
            type="select"
            class="mt-1 block w-full"
            v-model="form.billing.state"
            autocomple="states"
            :hasError="!!errors['billing.state']"
          >
            <option value="">Select states</option>
            <template
              v-for="[code, state] in Object.entries(getBillingCountryStates())"
              :key="code"
            >
              <option :value="state">{{ state }}</option>
            </template>
          </TextInput>

          <TextInput
            @input="errors['billing.state'] = null"
            v-else
            id="states"
            type="text"
            class="mt-1 block w-full"
            v-model="form.billing.state"
            autocomple="states"
            :hasError="!!errors['billing.state']"
          ></TextInput>

          <InputError class="mt-2" :message="errors['billing.state']" />
        </div>
      </div>

      <header class="mt-20 flex-col sm:flex sm:flex-row sm:justify-between">
        <h3 class="text-lg font-medium text-gray-900">Shipping Address</h3>
        <div class="flex gap-2 mt-5 sm:mt-0">
          <input
            type="checkbox"
            @change="
              $event.target.checked
                ? (form.shipping = { ...form.billing })
                : (form.shipping = null)
            "
            id="sameAsBillingAddress"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
          />
          <InputLabel
            for="sameAsBillingAddress"
            value="Same as Billing Address"
          />
        </div>
      </header>

      <div>
        <InputLabel for="s_address1" value="Address 1" />

        <TextInput
          @input="errors['shipping.address1'] = null"
          id="s_address1"
          type="text"
          class="mt-1 block w-full"
          v-model="form.shipping.address1"
          autocomplete="s_address1"
          :hasError="!!errors['shipping.address1']"
        />

        <InputError class="mt-2" :message="errors['shipping.address1']" />
      </div>

      <div>
        <InputLabel for="s_address2" value="Address 2" />

        <TextInput
          @input="errors['shipping.address2'] = null"
          id="s_address2"
          type="text"
          class="mt-1 block w-full"
          v-model="form.shipping.address2"
          autocomplete="s_address2"
          :hasError="!!errors['shipping.address2']"
        />

        <InputError class="mt-2" :message="errors['shipping.address2']" />
      </div>

      <div class="grid sm:grid-cols-2 gap-8">
        <div>
          <InputLabel for="s_city" value="City" />

          <TextInput
            @input="errors['shipping.city'] = null"
            id="s_city"
            type="text"
            class="mt-1 block w-full"
            v-model="form.shipping.city"
            autocomplete="s_city"
            :hasError="!!errors['shipping.city']"
          />

          <InputError class="mt-2" :message="errors['shipping.city']" />
        </div>

        <div>
          <InputLabel for="s_zipcode" value="Zip Code" />

          <TextInput
            @input="errors['shipping.zipcode'] = null"
            id="s_zipcode"
            type="text"
            class="mt-1 block w-full"
            v-model="form.shipping.zipcode"
            autocomplete="s_zipcode"
            :hasError="!!errors['shipping.zipcode']"
          />

          <InputError class="mt-2" :message="errors['shipping.zipcode']" />
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-8">
        <div>
          <InputLabel for="s_country" value="Country" />

          <TextInput
            @input="errors['shipping.country_code'] = null"
            id="s_country"
            type="select"
            class="mt-1 block w-full"
            v-model="form.shipping.country_code"
            autocomplete="s_country"
            :hasError="!!errors['shipping.country_code']"
          >
            <option disabled selected="!form.shipping.country_code" value="">
              Select country
            </option>
            <template v-for="country in countries" :key="country.code">
              <option :value="country.code">
                {{ country.name }}
              </option>
            </template>
          </TextInput>

          <InputError class="mt-2" :message="errors['shipping.country_code']" />
        </div>
        <div>
          <InputLabel for="s_states" value="States" />

          <TextInput
            @input="errors['shipping.state'] = null"
            v-if="getShippingCountryStates()"
            id="s_states"
            type="select"
            class="mt-1 block w-full"
            v-model="form.shipping.state"
            autocomple="s_states"
            :hasError="!!errors['shipping.state']"
          >
            <option value="">Select states</option>
            <template
              v-for="[code, state] in Object.entries(
                getShippingCountryStates()
              )"
              :key="code"
            >
              <option :value="state">{{ state }}</option>
            </template>
          </TextInput>

          <TextInput
            @input="errors['shipping.state'] = null"
            v-else
            id="s_states"
            type="text"
            class="mt-1 block w-full"
            v-model="form.shipping.state"
            autocomple="s_states"
            :hasError="!!errors['shipping.state']"
          ></TextInput>

          <InputError class="mt-2" :message="errors['shipping.state']" />
        </div>
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="text-sm mt-2 text-gray-800">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 font-medium text-sm text-green-600"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing" buttonText="Save" />

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
            Saved.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
