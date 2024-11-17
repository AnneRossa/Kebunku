<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Plus } from '@element-plus/icons-vue';
import { Inertia } from '@inertiajs/inertia';

// Define Props from the parent
defineProps({
    orders: Array
    // user_addresses: Array
});

const toRupiah = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: 'currency',
            currency: 'IDR',
        }).format(number)
    }

const isAddOrder = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false);

// Order data (related to orders and order items)
const id = ref('');
const total_price = ref('');
const status = ref('');
const order_number = ref('');
const order_items = ref([]); // This should be an array if you're handling multiple order items


// Add a new order method
// const AddOrder = async () => {
//     const formData = new FormData();
//     formData.append('order_number', order_number.value);
//     formData.append('total_price', total_price.value);
//     formData.append('status', status.value);

//     // Append order items (if any)
//     for (const item of order_items.value) {
//         formData.append('order_items[]', JSON.stringify(item)); // Send item as JSON
//     }

//     try {
//         await router.post('/orders/store', formData, {
//             onSuccess: (page) => {
//                 Swal.fire({
//                     toast: true,
//                     icon: 'success',
//                     position: 'top-end',
//                     showConfirmButton: false,
//                     title: page.props.flash.success,
//                 });
//                 dialogVisible.value = false;
//                 resetFormData();
//             },
//         });
//     } catch (err) {
//         console.error(err);
//         Swal.fire('Error', 'Failed to add order', 'error');
//     }
// };

// Reset form data after adding an order
// const resetFormData = () => {
//     id.value = '';
//     order_number.value = '';
//     total_price.value = '';
//     status.value = '';
//     order_items.value = [];
// };

// Edit Order Modal
const openEditModal = (order) => {
    editMode.value = true;
    isAddOrder.value = false;
    dialogVisible.value = true;

    // Update order data
    id.value = order.id;
    order_number.value = order.order_number;
    total_price.value = order.total_price;
    status.value = order.status;
    order_items.value = order.order_items.map(item => ({
        id: item.id, // Include id for existing items to facilitate updating
        product_id: item.product_id,
        quantity: item.quantity
    })); // Assuming order_items is an array of objects // Assuming order_items is an array of objects
};

// Delete single order item
const deleteOrderItem = async (item, index) => {
    try {
        await router.delete(`/orders/item/${item.id}`, {
            onSuccess: (page) => {
                order_items.value.splice(index, 1);
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                });
            },
        });
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Failed to delete order item', 'error');
    }
};

// Update Order method
const updateOrder = async () => {
    const formData = new FormData();
    formData.append('order_number', order_number.value);
    formData.append('total_price', total_price.value);
    formData.append('status', status.value);
    formData.append('_method', 'PUT');

    // Append updated order items (if any)
    for (const item of order_items.value) {
        formData.append('order_items[]', JSON.stringify(item)); // Send item as JSON
    }

    try {
        await router.post(`/orderitems/update/${id.value}`, formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                resetFormData();
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                });
            },
        });
    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Failed to update order', 'error');
    }
};

// Delete Order method
const deleteOrder = (order, index) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes, delete!',
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                router.delete(`/orders/destroy/${order.id}`, {
                    onSuccess: (page) => {
                        // Assuming you have a method to delete the item from the local list
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmButton: false,
                            title: page.props.flash.success,
                        });
                    },
                });
            } catch (err) {
                console.error(err);
                Swal.fire('Error', 'Failed to delete order', 'error');
            }
        }
    });
};
</script>

<template>
    <div>
        <h1 class="font-bold text-center font-2xl">Order List</h1>
    </div>
    <section class="  p-3 sm:p-5">
        <!-- dialog for adding order or editing order -->
        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit order' : 'Add Order'" width="50%"
            :before-close="handleClose">
            <!-- form start -->

            <form @submit.prevent="editMode ? updateOrder() : AddOrder()">
                <div class="relative z-0 w-full mb-6 group">
                    <select v-model="status" name="status" id="status"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-amber-500 focus:outline-none focus:ring-0 focus:border-amber-600 peer"
                        required>
                        <option value="" disabled selected>Choose Status</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="on_process">On Process</option>
                        <option value="package_sent">Package Sent</option>
                        <option value="completed">Completed</option>
                    </select>
                    <label for="status"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-amber-600 peer-focus:dark:text-amber-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                        Status
                    </label>
                </div>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg--800 focus:ring-4 focus:outline-none focus:ring--300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg--600 dark:hover:bg-amber-700 dark:focus:ring-amber-800">Submit</button>
            </form>
            <!-- end -->
        </el-dialog>
        <!-- end -->

        <!-- Main Page -->
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div v-if="orders.length === 0" class="text-center p-6 mt-48 mb-48 text-xl font-semibold text-gray-500">
                    Tidak ada order yang tersedia.
                </div>
                <div v-else class="overflow-x-auto">
                    <table  v-for="order in orders" :key="order.id"  class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Product Name</th>
                                <th scope="col" class="px-4 py-3">Order Number</th>
                                <th scope="col" class="px-4 py-3">Unit Price</th>
                                <th scope="col" class="px-4 py-3">Total Price</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">User Address</th>
                                <th scope="col" class="px-4 py-3">Nama Pemesan</th>
                                <th scope="col" class="px-4 py-3">Nomor Telepon</th>
                                <th scope="col" class="px-4 py-3">NIK/NPWP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in order.order_items && order.user_addresses" :key="item.id"
                                class="border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ item.product?.title }}</th>
                                <td class="px-4 py-3">{{ order.order_number }}</td>
                                <td class="px-4 py-3">{{ toRupiah(item.unit_price) }}</td>
                                <td class="px-4 py-3">{{ order.total_price }}</td>
                                <td class="px-4 py-3">{{ order.status }}</td>
                                <td class="px-4 py-3">{{ item.user_address?.address1 }}</td>
                                <td class="px-4 py-3">{{ item.user_address?.name }}</td>
                                <td class="px-4 py-3">{{ item.user_address?.nomorTlp }}</td>
                                <td class="px-4 py-3">{{ item.user_address?.ktp }}</td>
                                <!-- Button Action -->
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <button :id="`${order.id}-button`" :data-dropdown-toggle="`${order.id}`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="`${order.id}`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            :aria-labelledby="`${order.id}-button`">

                                            <li>
                                                <a href="#" @click="openEditModal(order)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteOrderItem(order, index)"
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
