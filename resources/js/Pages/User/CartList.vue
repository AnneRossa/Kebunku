<script setup>
import { usePage, router } from '@inertiajs/vue3';
import UserLayouts from './Layouts/UserLayouts.vue';
import { computed, onMounted, reactive } from 'vue'
import axios from 'axios';

console.log(axios)


defineProps({
    userAddress:Object
})

const carts = computed(()=>usePage().props.cart.data.items)
const products = computed(()=>usePage().props.cart.data.products)
const total = computed(()=>usePage().props.cart.data.total)

const itemId = (id) => carts.value.findIndex((item) => item.product_id == id);

const form = reactive ({
    address1: null,
    kelurahan: null,
    kecamatan: null,
    kota: null,
    provinsi: null,
    type: null,
    nomorTlp: null,
    name: null,
    ktp: null,
});

const formFilled = computed(() => {
    return (
        form.address1 != null &&
        form.kelurahan != null &&
        form.kecamatan != null &&
        form.kota != null &&
        form.provinsi != null &&
        form.type != null &&
        form.nomorTlp != null &&
        form.name != null &&
        form.ktp != null

    );
});

const update = (product, quantity) =>
    router.patch(route('cart.update', product), {
        quantity,
});

// remove from cart
const remove = (product) => router.delete(route('cart.delete', product));
const toRupiah = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: 'currency',
        currency: 'IDR',
    }).format(number)
}

onMounted(() => {
    const script = document.createElement('script');
    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
    script.setAttribute('data-client-key', 'SB-Mid-client-juD95QBWc3hPltG8'); // Make sure to use the correct environment variable name
    document.head.appendChild(script);

})


// confirm order
function submit() {
    axios.post(route('checkout.store'), {
                carts: usePage().props.cart.data.items,
                product: usePage().props.cart.data.products,
                total: usePage().props.cart.data.total,
                address: form // Assuming 'address' is a field in your form
            })
            .then(response => {
                // Handle successful response
                if (response.data.status === 'success') {
                    var snapToken = response.data.snap_token;
                    // Use snapToken as needed, e.g., redirect to Snap's checkout page
                    console.log('Snap Token:', snapToken);
                    try {
                    if (window.snap && typeof window.snap.pay === 'function') {
                        window.snap.pay(snapToken);
                    } else {
                        console.error('Snap object is not available.');
                    }
                } catch (error) {
                    console.error('Error creating transaction:', error);
                }

                } else {
                    // Handle error cases if necessary
                    console.error('Error:', response.data);
                }
            })
            .catch(error => {
                // Handle network errors or other exceptions
                console.error('Error:', error);
            });
}
</script>

<template>
    <UserLayouts>
        <section class="text-gray-600 body-font relative">
            <div class="px-5 mx-auto">
              <!-- Judul -->
              <div class="text-center mb-8">
                <h1 class="font-bold text-4xl">Cart List</h1>
                <h6 class="text-amber-500">Pengerjaan kebun akan di proses setelah melakukan pembayaran</h6>
              </div>

              <div class="lg:flex justify-center lg:flex-wrap">
                <!-- kiri -->
                <div class="w-full lg:w-1/2 mb-8 lg:mb-0">
                  <div class="flex flex-wrap">
                    <div v-for="product in products" :key="product.id" class="p-4 md:w-1/2 lg:w-1/3">
                      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <img v-if="product.product_images.length > 0" :src="`/${product.product_images[0].image}`" class="w-full h-48 object-cover object-center" alt="Product Image">
                        <img v-else src="@/images/no-img.png" class="w-full h-48 object-cover object-center" alt="Product Image">

                        <div class="p-4">
                          <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">{{ product.title }}</h3>
                          <p class="mt-1 text-md font-bold mb-2 text-gray-900 dark:text-gray-400">Rp. {{ product.price }}</p>
                          <div @click="remove(product)" >
                            <svg class="text-red-600 rounded-sm text-2xl" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="m6.774 6.4l.812 13.648a.8.8 0 0 0 .798.752h7.232a.8.8 0 0 0 .798-.752L17.226 6.4zm11.655 0l-.817 13.719A2 2 0 0 1 15.616 22H8.384a2 2 0 0 1-1.996-1.881L5.571 6.4H3.5v-.7a.5.5 0 0 1 .5-.5h16a.5.5 0 0 1 .5.5v.7zM14 3a.5.5 0 0 1 .5.5v.7h-5v-.7A.5.5 0 0 1 10 3zM9.5 9h1.2l.5 9H10zm3.8 0h1.2l-.5 9h-1.2z"/></svg>
                          </div>
                        </div>
                      </div>

                      <div class="mt-5">
                        <p>masukkan luas tanah dalam satuan meter</p>
                        <div class="flex items-center mb-4">
                            <div>
                                <input v-model="carts[itemId(product.id)].quantity" type="number" id="quantity" class="bg-gray-50 w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="1" required />
                            </div>

                            <button @click.prevent="update(product, carts[itemId(product.id)].quantity)" class="inline-flex items-center justify-center w-auto p-1 ms-3 text-sm font-medium text-green-500 bg-white border rounded-md border-green-300 focus:outline-none hover:bg-green-100 focus:ring-4 focus:ring-green-200 dark:bg-green-800 dark:text-green-400 dark:border-green-600 dark:hover:bg-green-700 dark:hover:border-green-600 dark:focus:ring-green-700" type="button">
                            simpan</button>
                          </div>
                      </div>
                      <!-- Total Harga per Produk -->
                      <p class="mt-1 text-md font-bold mb-2 text-gray-900 dark:text-gray-400">
                        Total: {{ toRupiah(product.price * carts[itemId(product.id)].quantity) }}
                      </p>

                    </div>
                  </div>
                </div>

                <!-- kanan -->
                <div class=" lg:w-1/2 px-4">
                  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-gray-900 text-lg mb-2 font-medium title-font">Grand Total</h2>
                    <p class="leading-relaxed mb-5 text-gray-600 font-bold text-xl">{{ toRupiah(total) }}</p>
                    <div v-if="userAddress">
                        <h2 class="text-gray-900 text-lg mb-2 font-medium title-font">Alamat Penyewaan</h2>
                        <p class="leading-relaxed mb-5 text-gray-600 font-bold">{{userAddress.address1}}, {{userAddress.kelurahan}}, {{userAddress.kecamatan}}, {{userAddress.kota}}, {{userAddress.provinsi}}</p>
                        <p class="leading-relaxed mb-5 text-gray-600">tambahkan alamat baru dibawah</p>
                    </div>
                    <div v-else>
                        <p class="leading-relaxed mb-5 text-gray-600">Tambah alamat baru untuk melanjutkan</p>
                    </div>
                    <form @submit.prevent="submit">
                          <div class="relative mb-4">
                            <label for="name" class="leading-7 text-sm text-gray-600">Nama</label>
                            <input type="text" id="name" name="address1" v-model="form.name"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                            <label for="name" class="leading-7 text-sm text-gray-600">Alamat 1</label>
                            <input type="text" id="name" name="address1" v-model="form.address1"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                              <label for="text" class="leading-7 text-sm text-gray-600">Kelurahan</label>
                              <input type="text" id="name" name="kelurahan" v-model="form.kelurahan"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                            <label for="text" class="leading-7 text-sm text-gray-600">Kecamatan</label>
                            <input type="text" id="name" name="kecamatan" v-model="form.kecamatan"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                              <label for="text" class="leading-7 text-sm text-gray-600">Kota</label>
                              <input type="text" id="text" name="kota"  v-model="form.kota"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                              <label for="text" class="leading-7 text-sm text-gray-600">Provinsi</label>
                              <input type="text" id="text" name="provinsi" v-model="form.provinsi"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                            <label for="text" class="leading-7 text-sm text-gray-600">Nomor Telepon</label>
                            <input type="text" id="text" name="type" v-model="form.nomorTlp"
                              class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                              <label for="text" class="leading-7 text-sm text-gray-600">Tipe Alamat</label>
                              <input type="text" id="text" name="type" v-model="form.type"
                                class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                          </div>
                          <div class="relative mb-4">
                            <label for="text" class="leading-7 text-sm text-gray-600">NIK / NPWP</label>
                            <input type="text" id="text" name="type" v-model="form.ktp"
                              class="w-full bg-white rounded border border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 text-base outline-none text-gray-700 py-2 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                        </div>

                          <button v-if="formFilled || userAddress" type="submit" class="text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded text-lg">
                            Bayar Sekarang
                          </button>
                          <button v-else type="submit" class="text-white bg-amber-500 border-0 py-2 px-6 focus:outline-none hover:bg-amber-600 rounded text-lg">
                            Tambahkan Alamat untuk melanjutkan
                          </button>
                    </form>

                    <p class="text-xs text-gray-500 mt-3 text-center">Lanjut sewa produk lain</p>
                  </div>
                </div>
              </div>
            </div>
          </section>



    </UserLayouts>
</template>

