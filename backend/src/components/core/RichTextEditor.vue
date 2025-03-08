<template>
  <div class="relative w-full border rounded-lg p-3 bg-white shadow-sm">
    <!-- Toolbar -->
    <div
      v-if="editor"
      class="w-full flex flex-wrap items-center gap-2 border-b pb-2 overflow-x-auto"
    >
      <!-- Undo/Redo -->
      <div class="flex items-center gap-2 px-2 border-r">
        <button
          type="button"
          @click="editor.chain().focus().undo().run()"
          :disabled="!editor.can().undo()"
          class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 rounded"
          title="Undo"
        >
          <ArrowUturnLeftIcon
            :class="{
              'grayscale opacity-50 cursor-not-allowed': !editor.can().undo(),
            }"
            class="w-5 h-5 font-black"
          />
        </button>
        <button
          type="button"
          @click="editor.chain().focus().redo().run()"
          :disabled="!editor.can().redo()"
          class="w-8 h-8 flex items-center justify-center hover:bg-gray-200 rounded"
          title="Redo"
        >
          <ArrowUturnRightIcon
            class="w-5 h-5 font-bold"
            :class="{
              'grayscale opacity-50 cursor-not-allowed': !editor.can().redo(),
            }"
          />
        </button>
      </div>

      <!-- Headings -->
      <div class="px-2">
        <div class="button-group flex items-center gap-3">
          <select
            class="p-2 rounded bg-white w-[150px]"
            @input="toggleHeading"
            :value="currentHeading"
          >
            <option value="paragraph">Paragraph</option>
            <option value="1">Heading 1</option>
            <option value="2">Heading 2</option>
            <option value="3">Heading 3</option>
          </select>
        </div>
      </div>

      <!-- Text Formatting -->
      <div class="flex items-center gap-1 px-2 border-l">
        <button
          type="button"
          @click="editor.chain().focus().toggleBold().run()"
          :class="{ 'bg-gray-300 font-bold': editor.isActive('bold') }"
          class="w-8 h-8 rounded hover:bg-gray-200"
          title="Bold"
        >
          B
        </button>
        <button
          type="button"
          @click="editor.chain().focus().toggleItalic().run()"
          :class="{ 'bg-gray-300 italic': editor.isActive('italic') }"
          class="w-8 h-8 rounded hover:bg-gray-200"
          title="Italic"
        >
          I
        </button>
        <button
          type="button"
          @click="editor.chain().focus().toggleUnderline().run()"
          :class="{ 'bg-gray-300 underline': editor.isActive('underline') }"
          class="w-8 h-8 rounded hover:bg-gray-200"
          title="Underline"
        >
          U
        </button>
      </div>

      <!-- Other Formatting -->
      <div class="flex items-center gap-2 px-2 border-l">
        <button
          type="button"
          @click="editor.chain().focus().toggleBlockquote().run()"
          :class="{
            'bg-gray-300 text-blue-600 border border-gray-400':
              editor.isActive('blockquote'),
          }"
          class="w-8 h-8 rounded text-xl hover:bg-gray-200"
          title="Blockquote"
        >
          ❝
        </button>
        <button
          type="button"
          @click="setLink()"
          :class="{
            'bg-blue-200 text-blue-600 cursor-pointer': editor.isActive('link'),
          }"
          class="w-8 h-8 rounded hover:bg-gray-200 flex items-center justify-center"
          title="Link"
        >
          <LinkIcon class="w-5 h-5" />
        </button>
        <div class="inline-block">
          <!-- Table Button with Dropdown Icon -->
          <button
            type="button"
            @click="toggleDropdown"
            class="w-14 h-8 rounded hover:bg-gray-200 flex items-center justify-center px-2"
            title="Table Options"
          >
            <TableCellsIcon class="w-5 h-5" />
            <ChevronDownIcon class="w-4 h-4 ml-1" />
          </button>

          <!-- Dropdown Menu -->
          <div
            v-if="isDropdownOpen"
            class="absolute right-[15%] top-[15%] mt-2 w-40 bg-white border border-gray-300 rounded-lg shadow-lg z-50 p-4 flex flex-col gap-2"
          >
            <button @click="addTable" class="dropdown-item text-left">
              Insert Table
            </button>
            <hr />
            <button @click="addRow" class="dropdown-item text-left">
              Add Row
            </button>
            <button @click="addColumn" class="dropdown-item text-left">
              Add Column
            </button>
            <button @click="deleteRow" class="dropdown-item text-left">
              Delete Row
            </button>
            <button @click="deleteColumn" class="dropdown-item text-left">
              Delete Column
            </button>
            <hr />
            <button
              @click="deleteTable"
              class="dropdown-item text-left text-red-500"
            >
              Delete Table
            </button>
          </div>
        </div>
      </div>

      <!-- Text Alignment -->
      <div class="flex items-center gap-2 px-2 border-l">
        <button
          type="button"
          @click="editor.chain().focus().setTextAlign('left').run()"
          class="w-8 h-8 rounded hover:bg-gray-200 flex items-center justify-center"
          title="Left Align"
        >
          <Bars3BottomLeftIcon class="w-5 h-5" />
        </button>
        <button
          type="button"
          @click="editor.chain().focus().setTextAlign('center').run()"
          class="w-8 h-8 rounded hover:bg-gray-200 flex items-center justify-center"
          title="Center Align"
        >
          <Bars3CenterLeftIcon class="w-5 h-5" />
        </button>
        <button
          type="button"
          @click="editor.chain().focus().setTextAlign('right').run()"
          class="w-8 h-8 rounded hover:bg-gray-200 flex items-center justify-center"
          title="Right Align"
        >
          <Bars3BottomRightIcon class="w-5 h-5" />
        </button>
        <button
          type="button"
          @click="editor.chain().focus().setTextAlign('justify').run()"
          class="w-8 h-8 rounded hover:bg-gray-200 flex items-center justify-center"
          title="Justify"
        >
          <Bars3Icon class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- Editor Content -->
    <editor-content
      class="min-h-[300px] mt-2 p-2 border rounded prose prose-lg max-w-none dark:prose-invert"
      :editor="editor"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Heading from "@tiptap/extension-heading";
import Bold from "@tiptap/extension-bold";
import Italic from "@tiptap/extension-italic";
import Underline from "@tiptap/extension-underline";
import Blockquote from "@tiptap/extension-blockquote";
import Image from "@tiptap/extension-image";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import {
  ArrowUturnLeftIcon,
  ArrowUturnRightIcon,
  Bars3BottomLeftIcon,
  Bars3BottomRightIcon,
  Bars3Icon,
  Bars3CenterLeftIcon,
  LinkIcon,
  TableCellsIcon,
  ChevronDownIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({ value: String });
const emit = defineEmits(["update:modelValue"]);

const editor = ref(null);
const currentHeading = ref("paragraph");

const isDropdownOpen = ref(false);
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

onMounted(() => {
  editor.value = new Editor({
    extensions: [
      StarterKit.configure({
        heading: false, // Disable to avoid duplicates
        bold: false, // Disable to avoid duplicates
        italic: false, // Disable to avoid duplicates
        blockquote: false, // Disable to avoid duplicates
      }),
      Heading.configure({
        levels: [1, 2, 3],
        HTMLAttributes: {
          class: "my-custom-class",
        },
      }),
      Bold,
      Italic,
      Underline,
      Blockquote.configure({
        HTMLAttributes: {
          class:
            "border-l-4 border-blue-500 pl-3 italic bg-gray-100 text-gray-700 my-2 py-1",
        },
      }),
      Image,
      Table.configure({ resizable: true }),
      TableRow,
      TableCell,
      TableHeader,
      Link.configure({
        openOnClick: false,
        defaultProtocol: "https",
        autolink: true,
        HTMLAttributes: {
          class: "text-blue-600 underline hover:text-blue-800", // Tailwind classes
        },
      }),
      TextAlign.configure({
        types: ["heading", "paragraph"],
      }),
    ],
    content: props.value || "<p></p>",
    onUpdate: ({ editor }) => {
      emit("update:modelValue", editor.getHTML());
      nextTick(updateCurrentHeading); // Update the dropdown value when the editor content changes
    },
  });
  nextTick(updateCurrentHeading);
});

watch(
  () => props.value,
  (newValue) => {
    if (editor.value && newValue !== editor.value.getHTML()) {
      editor.value.commands.setContent(newValue, false);
      nextTick(updateCurrentHeading);
    }
  }
);

onBeforeUnmount(() => {
  if (editor.value) editor.value.destroy();
});

const updateCurrentHeading = () => {
  if (!editor.value) return;

  const level = editor.value.getAttributes("heading").level;
  currentHeading.value = level ? level.toString() : "paragraph";
};

const toggleHeading = (event) => {
  if (!editor.value) return;

  const level = event.target.value;

  const chain = editor.value.chain().focus();

  if (level === "paragraph") {
    // Set the selected text or current paragraph to a paragraph
    chain.setParagraph().run();
  } else {
    // Apply the selected heading level to the selected text or current paragraph
    chain.toggleHeading({ level: parseInt(level) }).run();
  }
  nextTick(updateCurrentHeading);
};

const setLink = () => {
  const previousUrl = editor.value.getAttributes("link").href || "";

  if (previousUrl) {
    // If link exists, remove it
    editor.value.chain().focus().unsetLink().run();
  } else {
    // Otherwise, prompt for a new link
    const url = prompt("Enter URL:", previousUrl);

    if (!url) return; // Exit if the user cancels

    const isValidUrl = /^https?:\/\/[\w\-]+(\.[\w\-]+)+[/#?]?.*$/.test(url);
    if (!isValidUrl) {
      alert("Please enter a valid URL (starting with http:// or https://)");
      return;
    }
    if (url) {
      editor.value.chain().focus().setLink({ href: url }).run();
    }
  }
};

const addTable = () => {
  editor.value.chain().focus().insertTable({ rows: 3, cols: 3 }).run();
  isDropdownOpen.value = false;
};

const addRow = () => {
  editor.value.chain().focus().addRowAfter().run();
  isDropdownOpen.value = false;
};

const addColumn = () => {
  editor.value.chain().focus().addColumnAfter().run();
  isDropdownOpen.value = false;
};

const deleteRow = () => {
  editor.value.chain().focus().deleteRow().run();
  isDropdownOpen.value = false;
};

const deleteColumn = () => {
  editor.value.chain().focus().deleteColumn().run();
  isDropdownOpen.value = false;
};

const deleteTable = () => {
  editor.value.chain().focus().deleteTable().run();
  isDropdownOpen.value = false;
};
</script>

<style>
h1.my-custom-class {
  font-size: 2rem; /* Adjust as needed */
  font-weight: bold;
}

h2.my-custom-class {
  font-size: 1.75rem;
  font-weight: bold;
}

h3.my-custom-class {
  font-size: 1.5rem;
  font-weight: bold;
}

.prose table {
  @apply w-full border border-gray-300;
}

.prose th,
.prose td {
  @apply border border-gray-300 px-3 py-2 text-left;
}

.prose th {
  @apply bg-gray-100 font-bold;
}
</style>
