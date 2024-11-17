<template>
    <AdminLayout>
        <div class="p-4">
            <!-- Ucapan Selamat Datang dengan Animasi -->
            <div class="welcome-container mb-4">
                <h1 class="welcome-text">Selamat Datang di Halaman Admin!</h1>
            </div>


        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import AdminLayout from './Components/AdminLayout.vue'

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>

<script>
export default {
    name: 'AdminPage',
}
</script>
<style scoped>
.welcome-container {
    text-align: center;
    opacity: 0; /* Awalnya tersembunyi */
    transform: translateY(-20px); /* Pindahkan sedikit ke atas */
    animation: fadeInUp 1s forwards; /* Animasi muncul */
}

.welcome-text {
    font-size: 2rem;
    color: #333;
    font-weight: bold;
}

@keyframes fadeInUp {
    to {
        opacity: 1; /* Menjadi terlihat */
        transform: translateY(0); /* Kembali ke posisi semula */
    }
}
</style>
