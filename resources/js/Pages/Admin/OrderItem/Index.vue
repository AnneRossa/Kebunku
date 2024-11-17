<template>
    <AdminLayout>
        <OrderList :orders="orders"></OrderList>
    </AdminLayout>
</template>

<script setup>
defineProps({
    orders:Array
})

import AdminLayout from '../Components/AdminLayout.vue'
import OrderList from './OrderItemList.vue'
</script>
