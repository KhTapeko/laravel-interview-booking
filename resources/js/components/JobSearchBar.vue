<template>
  <div class="max-w-xl mx-auto mt-8 flex">
    <input
      :value="modelValue"
      @input="updateSearch"
      type="text"
      placeholder="輸入職缺名稱、公司名稱"
      class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
    />
    <button
      @click="onSearchClick"
      class="bg-blue-600 text-white px-6 py-3 rounded-r-lg hover:bg-blue-700 transition flex items-center gap-2"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
      搜尋
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: String
})
const emit = defineEmits(['update:modelValue', 'submit'])

const updateSearch = (event) => {
  emit('update:modelValue', event.target.value)
}

const onSearchClick = () => {
  emit('submit') // 這是觸發 Home.vue 的 @submit
}
</script>