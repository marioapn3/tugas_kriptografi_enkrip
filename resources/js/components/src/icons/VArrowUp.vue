<script setup>
import { bool } from 'vue-types'

const props = defineProps({
    isActive: bool().def(false)
})
</script>

<template>
    <font-awesome-icon icon="fa-solid fa-arrow-up" class="w-5 h-5 text-slate-400" :class="class" />
</template>
 