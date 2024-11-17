<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

defineProps({
    users: Array
});

const isAddUser = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);

// User form data
const id = ref('');
const name = ref('');
const email = ref('');
const password = ref('');
const isAdmin = ref(false);

// Open add modal
const openAddModal = () => {
    resetFormData();
    isAddUser.value = true;
    dialogVisible.value = true;
    editMode.value = false;
};

// Add User method
const AddUser = async () => {
    if (password.value.length < 6) { // Basic password validation
        Swal.fire('Error', 'Password must be at least 6 characters long', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('email', email.value);
    formData.append('password', password.value);
    formData.append('isAdmin', isAdmin.value ? 1 : 0);

    try {
        await router.post('users/store', formData, {
            onSuccess: page => {
                Swal.fire({ toast: true, icon: 'success', position: 'top-end', showConfirmButton: false, title: page.props.flash.success });
                dialogVisible.value = false;
                resetFormData();
            },
        });
    } catch (err) {
        console.error(err);
    }
};

// Reset form data
const resetFormData = () => {
    id.value = '';
    name.value = '';
    email.value = '';
    password.value = '';
    isAdmin.value = false;
};

// Open edit modal
const openEditModal = (user) => {
    id.value = user.id;
    name.value = user.name;
    email.value = user.email;
    isAdmin.value = user.isAdmin === 1;

    isAddUser.value = false;
    dialogVisible.value = true;
    editMode.value = true;
};

// Update user method
const updateUser = async () => {
    const formData = new FormData();
    formData.append('name', name.value);
    formData.append('email', email.value);
    formData.append('isAdmin', isAdmin.value ? 1 : 0);
    formData.append("_method", 'PUT');

    if (password.value) { // Only include password if provided
        if (password.value.length < 6) {
            Swal.fire('Error', 'Password must be at least 6 characters long', 'error');
            return;
        }
        formData.append('password', password.value);
    }

    try {
        await router.post('users/update/' + id.value, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                resetFormData();
                Swal.fire({ toast: true, icon: "success", position: "top-end", showConfirmButton: false, title: page.props.flash.success });
            }
        });
    } catch (err) {
        console.error(err);
    }
};

// Delete user method
const deleteUser = (user, index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete('users/destroy/' + user.id, {
                onSuccess: (page) => {
                    users.splice(index, 1);
                    Swal.fire({ toast: true, icon: "success", position: "top-end", showConfirmButton: false, title: page.props.flash.success });
                }
            });
        }
    });
};
</script>
<template>
    <div>
        <h1 class="font-bold text-center text-2xl">User List</h1>
    </div>
    <section class="p-3 sm:p-5">
        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit User' : 'Add User'" width="50%">
            <form @submit.prevent="editMode ? updateUser() : AddUser()">
                <div class="relative z-0 w-full mb-6 group">
                    <input v-model="name" type="text" required
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white"
                        placeholder=" " />
                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3">Name</label>
                </div>
                <div class="relative z-0 w-full mb-6 group">
                    <input type="email" v-model="email" required
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white"
                        placeholder=" " />
                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3">Email</label>
                </div>

                <div class="relative z-0 w-full mb-6 group">
                    <label class="block mb-2 text-sm text-gray-500">Is Admin</label>
                    <input type="checkbox" v-model="isAdmin" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 rounded-lg text-sm w-full px-5 py-2.5">Submit</button>
            </form>
        </el-dialog>

        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3">User Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Is Admin</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user) in users" :key="user.id" class="border-b dark:border-gray-700">
                                <th class="px-4 py-3">{{ user.name }}</th>
                                <td class="px-4 py-3">{{ user.email }}</td>
                                <td class="px-4 py-3">{{ user.isAdmin === 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="#" @click="openEditModal(user)"
                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</template>
