<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue'

defineProps({
    categories: Array
})

const isAddCategory = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);


//category from data
const id = ref('');
const name = ref('')
const slug = ref('')

//end

// open add modal
const openAddModal = () => {
    isAddCategory.value = true
    dialogVisible.value = true
    editMode.value = false
}

// add Category method
const AddCategory = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('slug', slug.value);
    try {
        await router.post('categories/store', formData, {
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
    name.value = '';
    slug.value = '';
    dialogImageUrl.value = ''
};


// Edit Modal
const openEditModal = (location) => {
    editMode.value = true;
    isAddLocation.value = false
    dialogVisible.value = true

    // update data
    id.value = location.id;
    name.value = location.name;
    slug.value = location.slug;

    editMode.value = true;
    isAddLocation.value = false
    dialogVisible.value = true

}

// delete single image on form
const deleteImage = async (pimage, index) => {
    try {
        await router.delete('/admin/categories/image/', {
            onSuccess: (page) => {
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

// update category method
const updateCategory = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('slug', slug.value);
    formData.append("_method", 'PUT');
    // code to append location images to form data
    try {
        await router.post('categories/update/' + id.value, formData, {
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

// delete category methond
const deleteCategory = (category, index) => {
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
                router.delete('categories/destroy/' + category.id, {
                    onSuccess: (page) => {
                        this.delete(category, index);
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

            }
        }
    })
}
</script>
<template>
    <div>
        <h1 class="font-bold text-center font-2xl">Category List</h1>
    </div>
    <section class="  p-3 sm:p-5">
        <!-- dialog for adding category or editing category -->
        <el-dialog v-model="dialogVisible" :name="editMode ? 'Edit category' : 'Add Category'" width="50%"
            :before-close="handleClose">
            <!-- form start -->

            <form @submit.prevent="editMode ? updateCategory() : AddCategory()">
                <div class="relative z-0 w-full mb-6 group">
                    <input v-model="name" type="text" name="floating_name" id="floating_name"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        placeholder=" " required />
                    <label for="floating_name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="text" name="floating_slug" id="floating_slug"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        placeholder=" " required v-model="slug" />
                    <label for="floating_slug"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Slug</label>
                </div>
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
                            Add category
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Category name</th>
                                <th scope="col" class="px-4 py-3">Slug</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(category, index) in categories" :key="category.id"
                                class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ category.name }}</th>
                                <td class="px-4 py-3">{{ category.slug }}</td>
                                <td class="px-4 py-3 flex items-center justify-end">

                                    <button :id="`${category.id}-button`" :data-dropdown-toggle="`${category.id}`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="`${category.id}`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            :aria-labelledby="`${category.id}-button`">

                                            <li>
                                                <a href="#" @click="openEditModal(category)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteCategory(category, index)"
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
