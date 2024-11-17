<script setup>
import { computed, ref } from 'vue';
import Header from './Layouts/Header.vue';
import Footer from './Layouts/Footer.vue';
import Swal from 'sweetalert2'; // Ensure you have sweetalert2 installed and imported
import { usePage, useForm, router } from '@inertiajs/vue3'; // Import usePage and useForm from Inertia

// Define props for a single product and related data
const props = defineProps({
    product: Object,
    locations: Array,
    categories: Array
});

// Helper function to format currency
const toRupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: 'currency',
        currency: 'IDR',
    }).format(number);
};

// Function to add product to the cart
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

// Ref for current image index
const currentImageIndex = ref(0);

// Function to change the image
const changeImage = (index) => {
    if (index >= 0 && index < props.product.product_images.length) {
        currentImageIndex.value = index;
    }
}
</script>


<template>
    <Header />
    <div v-if="product" class="lg:mx-40 grid grid-cols-1 md:grid-cols-2 lg:gap-2 md:gap-2 gap-4 mt-8 md:mt-24 mx-4 md:mx-0">
        <!-- Sisi Kiri -->
        <div class="flex justify-center md:justify-start">
            <div class="max-w-xs md:max-w-xl">
                <!-- Carousel atau galeri gambar -->
                <div class="relative overflow-hidden rounded-lg">
                    <img
                        :src="`/${product.product_images[currentImageIndex].image}`"
                        :alt="product.imageAlt"
                        class="object-cover object-center w-full h-full sm:h-50 lg:h-80 rounded-lg"
                    />
                    <div v-if="product.product_images.length > 1">
                        <!-- Slider controls -->
                        <button @click="changeImage(currentImageIndex - 1)" type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button @click="changeImage(currentImageIndex + 1)" type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Sisi Kanan -->
        <div class="flex flex-col justify-center">
            <p class="font-extrabold text-3xl md:text-4xl mb-2">{{ product.title }}</p>
            <p class="text-gray-600 mb-2">Lokasi : {{ product.location.name }}</p>
            <p class="text-gray-500 underline mb-2">{{ product.category.name }}</p>
            <p style="background-color: #E6FF94;" class="font-extrabold text-xl md:text-2xl bg-green-300 rounded-md py-3 mt-2 md:mt-3 mb-4">{{ toRupiah(product.price) }}</p>
            <p class="text-left rtl:text-right mt-2 mb-4 text-gray-500 dark:text-gray-400">{{ product.description }}</p>
            <button @click.prevent="addToCart(product)" type="button" class="text-white bg-gray-700 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 md:px-6 md:py-3 me-2 md:mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 md:mt-2">Sewa sekarang</button>
        </div>
    </div>
    <Footer />
</template>

