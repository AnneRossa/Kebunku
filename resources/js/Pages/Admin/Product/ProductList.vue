<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue'
import { Inertia } from '@inertiajs/inertia';

defineProps({
    products: Array
})

const locations = usePage().props.locations;
const categories = usePage().props.categories;

const isAddProduct = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);

// upload multiple images
const productImages = ref([])
const dialogImageUrl = ref('')

const handleFileChange = (file) => {

    productImages.value.push(file)
}
const handlePictureCardPreview = (file) => {
    dialogImageUrl.value = file.url
    dialogVisible.value = true
}
const handleRemove = (file) => {
    console.log(file)
}

//product from data
const id = ref('');
const title = ref('')
const price = ref('')
const quantity = ref('')
const description = ref('')
const product_images = ref([])
const published = ref('')
const category_id = ref('')
const location_id = ref('')
const inStock = ref('')
//end

// open add modal
const openAddModal = () => {
    isAddProduct.value = true
    dialogVisible.value = true
    editMode.value = false
}

// add product method
const AddProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('description', description.value);
    formData.append('location_id', location_id.value);
    formData.append('category_id', category_id.value);
    // Append product images to the FormData
    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }

    try {
        await router.post('products/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                dialogVisible.value = false;
                resetFormData();
            },
        })
    } catch (err) {
        console.log(err)
    }
}

//rest data after added
const resetFormData = () => {
    id.value = '';
    title.value = '';
    price.value = '';
    quantity.value = '';
    description.value = '';
    productImages.value = [];
    dialogImageUrl.value = ''
};


// Edit Modal
const openEditModal = (product) => {
    editMode.value = true;
    isAddProduct.value = false
    dialogVisible.value = true

    // update data
    id.value = product.id;
    title.value = product.title;
    price.value = product.price;
    quantity.value = product.quantity;
    description.value = product.description;
    location_id.value = product.location_id;
    category_id.value = product.category_id;
    product_images.value = product.product_images;

    editMode.value = true;
    isAddProduct.value = false
    dialogVisible.value = true

}

// delete single image on form
const deleteImage = async (pimage, index) => {
    try {
        await router.delete('/admin/products/image/' + pimage.id, {
            onSuccess: (page) => {
                product_images.value.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                });
            }
        })
    } catch (err) {
        console.log(err);
    }
}

// update product method
const updateProduct = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('price', price.value);
    formData.append('quantity', quantity.value);
    formData.append('description', description.value);
    formData.append('category_id', category_id.value);
    formData.append('location_id', location_id.value);
    formData.append("_method", 'PUT');
    // code to append product images to form data

    for (const image of productImages.value) {
        formData.append('product_images[]', image.raw);
    }
    try {
        await router.post('products/update/' + id.value, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                resetFormData();
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
            }
        })
    } catch (err) {
        console.log(err)
    }

}

// delete product methond
const deleteProduct = (product, index) => {
    Swal.fire({
        title: 'Are you sure',
        text: "This actions cannot undo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'no',
        confirmButtonText: 'yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete('products/destroy/' + product.id, {
                    onSuccess: (page) => {
                        this.delete(product, index);
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            title: page.props.flash.success
                        });
                    }
                })
            } catch (err) {
                console.log(err)
            }
        }
    })
}
</script>
<template>
    <div>
        <h1 class="font-bold text-center font-2xl">Product List</h1>
    </div>
    <section class="  p-3 sm:p-5">
        <!-- dialog for adding product or editing product -->
        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit product' : 'Add Product'" width="50%"
            :before-close="handleClose">
            <!-- form start -->

            <form @submit.prevent="editMode ? updateProduct() : AddProduct()">
                <div class="relative z-0 w-full mb-6 group">
                    <input v-model="title" type="text" name="floating_title" id="floating_title"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        placeholder=" " required />
                    <label for="floating_title"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Title</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="text" name="floating_price" id="floating_price"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        placeholder=" " required v-model="price" />
                    <label for="floating_price"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
                </div>
                <!-- <div class="relative z-0 w-full mb-6 group">
                    <input type="number" name="qty" id="floating_qty"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        placeholder="" required v-model="quantity" />
                    <label for="floating_qty"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Quantity</label>
                </div> -->
                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Category</label>
                    <select id="category" v-model="category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring--500 focus:border--500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring--500 dark:focus:border--500">
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name
                            }}
                        </option>

                    </select>
                </div>


                <div>
                    <label for="location" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Lokasi</label>
                    <select id="location" v-model="location_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring--500 focus:border--500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring--500 dark:focus:border--500">
                        <option v-for="location in locations" :key="location.id" :value="location.id">{{ location.name }}</option>

                    </select>
                </div>


                <div class="grid  md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">

                        <label for="message"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="message" rows="4" v-model="description"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring--500 focus:border--500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring--500 dark:focus:border--500"
                            placeholder="Leave a comment..."></textarea>

                    </div>

                </div>
                <!-- multiple images upload -->
                <div class="grid  md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">
                        <el-upload v-model:file-list="productImages" list-type="picture-card" multiple
                            :on-preview="handlePictureCardPreview" :on-remove="handleRemove"
                            :on-change="handleFileChange">
                            <el-icon>
                                <Plus />
                            </el-icon>
                        </el-upload>

                    </div>
                </div>
                <!-- end -->

                <!-- list of images for selected product -->
                <div class="flex flex-nowrap mb-8 ">
                    <div v-for="(pimage, index) in product_images" :key="pimage.id" class="relative w-32 h-32 ">
                        <img class="w-24 h-20 rounded" :src="`/${pimage.image}`" alt="">
                        <span
                            class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                            <span @click="deleteImage(pimage, index)"
                                class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">x</span>
                        </span>
                    </div>
                </div>

                <!-- end -->




                <button type="submit"
                    class="text-white bg-blue-700 hover:bg--800 focus:ring-4 focus:outline-none focus:ring--300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg--600 dark:hover:bg-amber-700 dark:focus:ring-amber-800">Submit</button>
            </form>

            <!-- end -->



        </el-dialog>

        <!-- end -->
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" @click="openAddModal"
                            class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add product
                        </button>

                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Product name</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Lokasi</th>
                                <!-- <th scope="col" class="px-4 py-3">Quantity</th> -->
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Publish</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in products" :key="product.id"
                                class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ product.title }}</th>
                                <td class="px-4 py-3">{{ product.category.name }}</td>
                                <td class="px-4 py-3">{{ product.location.name }}</td>
                                <!-- <td class="px-4 py-3">{{ product.quantity }}</td> -->
                                <td class="px-4 py-3">Rp.{{ product.price }}</td>

                                <td class="px-4 py-3">
                                    <span v-if="product.inStock == 1"
                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">inStock</span>
                                    <span v-else
                                        class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Out
                                        of Stock</span>

                                </td>
                                <td class="px-4 py-3">
                                    <button v-if="product.published == 1" type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Published</button>
                                    <button v-else type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">UnPublished</button>

                                </td>

                                <td class="px-4 py-3 flex items-center justify-end">

                                    <button :id="`${product.id}-button`" :data-dropdown-toggle="`${product.id}`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="`${product.id}`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            :aria-labelledby="`${product.id}-button`">

                                            <li>
                                                <a href="#" @click="openEditModal(product)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteProduct(product, index)"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</template>
