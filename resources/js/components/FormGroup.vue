<template>
    <div>
      <label class="block font-bold mb-1">{{ label }}</label>
      <slot></slot>
      <p v-if="error" class="text-red-500 text-sm mt-1">{{ error[0] }}</p>
    </div>
  </template>
  
  <script setup>
  defineProps(['label', 'error'])
  </script>
  