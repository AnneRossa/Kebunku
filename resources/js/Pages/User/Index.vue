<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { Link, router } from '@inertiajs/vue3';

import Hero from './Layouts/Hero.vue';
import Hero_sec from './Layouts/Hero_sec.vue';

import Products from './Components/Products.vue';

defineProps({
    products: Array
})


</script>
<template>
    <UserLayouts>
        <Hero></Hero>
        <div class="bg-white">
            <div class="mx-3 lg:mx-32">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 mt-20">Latest products list</h2>

                <!-- product list component -->
                <Products :products="products"></Products>

                <div class="flex justify-center mt-16">
                    <Link :href="route('products.index')" type="button" class="text-white bg-green-700 hover:bg-green-300 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center me-2 mb-2 dark:focus:ring-green-900">More Product</Link>
                </div>
            </div>
        </div>
        <Hero_sec></Hero_sec>
    </UserLayouts>
</template>
