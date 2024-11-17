<template>
    <AdminLayout>
        <LocationList :locations="locations"></LocationList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    locations:Array
})

import AdminLayout from '../Components/AdminLayout.vue'
import LocationList from './LocationList.vue'
</script>
