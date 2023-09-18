<script setup>
import { bool } from 'vue-types'

const props = defineProps({
    isActive: bool().def(false)
})
</script>

<template>
    <!-- <font-awesome-icon icon="book" class="w-5 h-5 text-slate-400" :class="isActive && '!text-blue-500'"/> -->
    <font-awesome-icon icon="book" />
</template>
 