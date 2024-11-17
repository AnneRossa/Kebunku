<script setup>
import { Link, router } from '@inertiajs/vue3';
defineProps({
    products: Array
})


const addToCart = (product) => {
    console.log(product)
    router.post(route('cart.store', product), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        }
    })
}

const toRupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: 'currency',
        currency: 'IDR',
    }).format(number)
}


</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
        <div v-for="product in products" :key="product.id" class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75">
                <img v-if="product.product_images.length > 0" :src="`/${product.product_images[0].image}`" :alt="product.imageAlt" class="object-cover object-center w-full h-full sm:h-50 lg:h-80" />
                <img v-else src="@/images/no-img.png" :alt="product.imageAlt" class="object-cover object-center w-full h-full lg:h-80" />
            </div>
            <div class="p-5">
                <p class="text-xl font-bold text-gray-900">{{ toRupiah(product.price)  }} /m</p>
                <h3 class="text-md text-gray-900">
                    {{ product.title }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ product.location.name }}</p>
                <div class="flex items-center justify-between mt-4">
                    <!-- more detail -->
                    <div>
                        <Link :href="`/detail/${product.id}`"  class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-700 bg-gray-100 rounded-lg focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-400 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                More Details
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                        </Link>
                    </div>

                    <!-- add to cart icon -->
                    <div>
                        <a @click="addToCart(product)" class="inline-flex mt-2 text-right px-3 py-3 text-sm font-medium text-gray-100 bg-gray-700 rounded-lg hover:bg-green-200 hover:text-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5">
                                        <path fill="currentColor"
                                            d="M19.5 22a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m-10 0a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3" />
                                        <path
                                            d="M5 4h17l-2 11H7zm0 0c-.167-.667-1-2-3-2m18 13H5.23c-1.784 0-2.73.781-2.73 2s.946 2 2.73 2H19.5" />
                                    </g>
                                </svg>
                            </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
