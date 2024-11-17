<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { Link, router } from '@inertiajs/vue3';


</script>
<template>
    <UserLayouts>
        <div class="bg-green-800 p-1 lg:px-4 text-center  py-10 lg:py-50">
            <p class="mb-4 text-lg  text-gray-100 lg:text-xl sm:px-16 lg:px-48 font-bold">ABOUT US</p>
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">VISI & MISI</h1>
         </div>
        <div class="mx-3 lg:mx-32">

            <div class="md:w-3/4 lg:w-4/6 xl:w-1/2 sm:w-6/12 mx-auto mt-20">
                <p class="mb-3 text-gray-500 dark:text-gray-400  first-line:tracking-widest first-letter:text-7xl first-letter:font-bold first-letter:text-gray-900 dark:first-letter:text-gray-100 first-letter:me-3 first-letter:float-start">KEBUN KU adalah sebuah platform di bidang agrikultur khususnya di bidang perkebunan, yang bertujuan untuk menyediakan platform bagi para petani untuk menyewa 'Alsintan' (Alat dan Mesin Pertanian).</p>
                <p class="text-gray-500 dark:text-gray-400">KEBUN KU mengintegrasikan prinsip-prinsip keberlanjutan dalam operasinya, dengan fokus pada pengurangan jejak karbon, pengelolaan limbah, dan penerapan praktik pertanian ramah lingkungan. Dengan komitmen mereka terhadap inovasi, pendidikan, dan keberlanjutan, KEBUN KU memainkan peran penting dalam memajukan industri pertanian menuju masa depan yang lebih cerdas dan berkelanjutan.</p>
                <p class="mt-20 text-xl font-extrabold">VISI</p>
                <p class="text-gray-500 dark:text-gray-400">Menjadi solusi dalam meningkatkan kesejahteraan petani Indonesia dengan mengintegrasikan teknologi ke dalam pertanian, menyediakan platform inovatif untuk penyewaan mesin yang terjangkau dan mudah diakses.</p>
                <img class="rounded-lg mt-10" src="@/images/about2.jpg" alt="">
                <p class="mt-10 text-xl font-extrabold">MISI</p>
                <ul class="space-y-4 text-left text-gray-500 dark:text-gray-400">
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                        <span>Membantu para petani untuk memaksimalkan kinerjanya </span>
                    </li>
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                        <span> Menghubungkan petani dengan berbagai alsintan modern melalui platform online</span>
                    </li>
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                        <span>Mendorong pertumbuhan ekonomi komunitas petani lokal dengan mengoptimalkan hasil panen melalui penggunaan alsintan modern.</span>
                    </li>
                </ul>
            </div>
        </div>

    </UserLayouts>
</template>

<script>
export default {
    data() {
        return {
            image: ['contact-bg']
        }
    }
}
</script>

<style scoped>
.background-image {
    background: url('@/assets/img/contact-bg.jpg');
    background-color: #8a919a;
    background-blend-mode: multiply;
    background-size: cover;

}

</style>
