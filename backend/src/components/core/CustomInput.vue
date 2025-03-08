<template>
  <div>
    <label :for="name" class="sr-only">{{ label }}</label>
    <div class="mt-1 flex rounded-md shadow-sm">
      <span
        v-if="prepend"
        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
      >
        {{ prepend }}
      </span>

      <!-- Rich Text Editor -->
      <template v-if="type === 'richtext'">
        <RichTextEditor
          :name="name"
          :required="required"
          :value="modelValue"
          @update:modelValue="emit('update:modelValue', $event)"
        />
      </template>

      <!-- Textarea -->
      <!-- <template v-else-if="type === 'textarea'">
        <textarea
          :name="name"
          :required="required"
          :value="props.modelValue"
          @input="emit('update:modelValue', $event.target.value)"
          :class="inputClasses"
          :placeholder="label"
        ></textarea>
      </template> -->

      <!-- File Upload -->
      <template v-else-if="type === 'file'">
        <input
          :type="type"
          :name="name"
          :required="required"
          @change="handleFileChange"
          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
        />
      </template>

      <!-- Default Input -->
      <template v-else>
        <input
          :type="type"
          :name="name"
          :required="required"
          :value="props.modelValue"
          @input="emit('update:modelValue', $event.target.value)"
          :class="inputClasses"
          :placeholder="label"
          step="0.01"
        />
      </template>

      <span
        v-if="append"
        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"
      >
        {{ append }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import RichTextEditor from "./RichTextEditor.vue";

const props = defineProps({
  modelValue: [String, Number, File],
  label: String,
  type: {
    type: String,
    default: "text",
  },
  name: String,
  required: Boolean,
  prepend: {
    type: String,
    default: "",
  },
  append: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const inputClasses = computed(() => {
  const cls = [
    `block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm`,
  ];

  if (props.append && !props.prepend) {
    cls.push(`rounded-l-md`);
  } else if (props.prepend && !props.append) {
    cls.push(`rounded-r-md`);
  } else if (!props.prepend && !props.append) {
    cls.push("rounded-md");
  }
  return cls.join(" ");
});

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    emit("update:modelValue", file);
  }
};
</script>

<style>
button {
  cursor: pointer;
}

.tiptap:focus,
.ProseMirror:focus {
  outline: none !important;
  box-shadow: none !important;
}
</style>
