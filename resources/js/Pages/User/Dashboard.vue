<script setup>
    import UserLayouts from './Layouts/UserLayouts.vue'
    import { computed } from 'vue'

    defineProps({
        orders: Array,
    })

    const toRupiah = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: 'currency',
            currency: 'IDR',
        }).format(number)
    }
</script>

<template>
    <UserLayouts>
        <div class="relative max-w-screen-xl mx-auto overflow-x-auto">
            <div v-if="orders.length === 0" class="text-center p-6 mt-48 mb-48 text-xl font-semibold text-gray-500">
                Tidak ada order yang tersedia.
            </div>

            <div v-else>
                <table v-for="order in orders" :key="order.id" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <p class="px-6 py-3">Order Number #{{order.order_number}}</p>

                    <!-- Menampilkan tabel hanya jika order.order_items ada itemnya -->
                    <template v-if="order.order_items.length > 0">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Product</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Luas Tanah</th>
                                <th scope="col" class="px-6 py-3">Harga Item</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in order.order_items" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ item.product?.title || 'No Product' }}
                                </th>
                                <td class="px-6 py-4">{{ order.status }}</td>
                                <td class="px-6 py-4">{{ item.quantity }}m</td>
                                <td class="px-6 py-4">{{ toRupiah(item.unit_price) }}</td>
                            </tr>
                        </tbody>
                        <p class="font-bold text-gray-950 text-l px-6 py-3">Total Harga {{ toRupiah(order.total_price) }}</p>
                    </template>

                    <!-- Menampilkan pesan jika tidak ada item di dalam order -->
                    <template v-else>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada item untuk order ini.
                            </td>
                        </tr>
                    </template>
                </table>
            </div>
        </div>
    </UserLayouts>
</template>
