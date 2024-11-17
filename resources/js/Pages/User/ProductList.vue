<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import { XMarkIcon, PlusIcon, MinusIcon, FunnelIcon } from '@heroicons/vue/24/outline';
import Products from './Components/Products.vue';
import SecondaryButtonVue from '@/Components/SecondaryButton.vue';

const sortOptions = [
  { name: 'Most Popular', href: '#', current: true },
  { name: 'Best Rating', href: '#', current: false },
  { name: 'Newest', href: '#', current: false },
  { name: 'Price: Low to High', href: '#', current: false },
  { name: 'Price: High to Low', href: '#', current: false },
];



const filterPrices = useForm({
  prices: [0, 10000]
});

const priceFilter = () => {
  filterPrices.transform((data) => ({
    ...data,
    prices: {
      from: filterPrices.prices[0],
      to: filterPrices.prices[1]
    }
  })).get('products', {
    preserveState: true,
    replace: true
  });
};

const mobileFiltersOpen = ref(false);

const props = defineProps({
  products: Array,
  locations: Array,
  categories: Array
});

// Filter locations and categories
const selectedLocations = ref([]);
const selectedCategories = ref([]);

watch([selectedLocations, selectedCategories], () => {
  updateFilteredProducts();
});

function updateFilteredProducts() {
  router.get('products', {
    locations: selectedLocations.value,
    categories: selectedCategories.value
  }, {
    preserveState: true,
    replace: true
  });
}
</script>

<template>
  <UserLayouts>
    <div class="bg-white">
      <!-- Mobile filter dialog -->
      <TransitionRoot as="template" :show="mobileFiltersOpen">
        <Dialog class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
          <TransitionChild
            as="template"
            enter="transition-opacity ease-linear duration-300"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="transition-opacity ease-linear duration-300"
            leave-from="opacity-100"
            leave-to="opacity-0"
          >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
          </TransitionChild>

          <div class="fixed inset-0 z-40 flex">
            <TransitionChild
              as="template"
              enter="transition ease-in-out duration-300 transform"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transition ease-in-out duration-300 transform"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                <div class="flex items-center justify-between px-4">
                  <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                  <button
                    type="button"
                    class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                    @click="mobileFiltersOpen = false"
                  >
                    <span class="sr-only">Close menu</span>
                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                  </button>
                </div>

                <!-- Filters -->
                <form class="mt-4 border-t border-gray-200 px-4">
                  <!-- Price filter -->
                  <div class="flex items-center justify-between space-x-3 mb-4">
                    <div class="basis-1/3">
                      <label for="filters-price-from" class="mb-2 block text-sm font-medium text-gray-900">
                        From
                      </label>
                      <input
                        type="number"
                        id="filters-price-from"
                        v-model="filterPrices.prices[0]"
                        placeholder="Min price"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                      />
                    </div>
                    <div class="basis-1/3">
                      <label for="filters-price-to" class="mb-2 block text-sm font-medium text-gray-900">
                        To
                      </label>
                      <input
                        type="number"
                        id="filters-price-to"
                        v-model="filterPrices.prices[1]"
                        placeholder="Max price"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                      />
                    </div>
                    <SecondaryButtonVue class="self-end" @click="priceFilter()">
                      Ok
                    </SecondaryButtonVue>
                  </div>

                  <!-- Category filter -->
                  <Disclosure as="div" class="border-t border-gray-200 pt-4">
                    <h3 class="-mx-2 -my-3 flow-root">
                      <DisclosureButton class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                        <span class="font-medium text-gray-900">Categories</span>
                        <span class="ml-6 flex items-center">
                          <PlusIcon v-if="!categoriesOpen" class="h-5 w-5" aria-hidden="true" />
                          <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                        </span>
                      </DisclosureButton>
                    </h3>
                    <DisclosurePanel class="pt-6">
                      <div class="space-y-4">
                        <div v-for="category in categories" :key="category.id" class="flex items-center">
                          <input
                            :id="`filter-category-${category.id}`"
                            :value="category.id"
                            type="checkbox"
                            v-model="selectedCategories"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                          />
                          <label :for="`filter-category-${category.id}`" class="ml-3 text-sm text-gray-600">
                            {{ category.name }}
                          </label>
                        </div>
                      </div>
                    </DisclosurePanel>
                  </Disclosure>

                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </Dialog>
      </TransitionRoot>

      <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
          <h1 class="text-4xl font-bold tracking-tight text-gray-900">Produk Kebunku</h1>
          <div class="flex items-center">
            <!-- Mobile filter button -->
            <button
              type="button"
              class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 lg:hidden"
              @click="mobileFiltersOpen = true"
            >
              <span class="sr-only">Filters</span>
              <FunnelIcon class="h-5 w-5" aria-hidden="true" />
            </button>
          </div>
        </div>

        <section aria-labelledby="products-heading" class="pb-24 pt-6">
          <h2 id="products-heading" class="sr-only">Products</h2>

          <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
            <!-- Desktop filters -->
            <form class="hidden lg:block">
              <!-- Price filter -->
              <h3 class="sr-only">Price</h3>
              <div class="flex items-center justify-between space-x-3 mb-4">
                <div class="basis-1/3">
                  <label for="filters-price-from" class="mb-2 block text-sm font-medium text-gray-900">
                    From
                  </label>
                  <input
                    type="number"
                    id="filters-price-from"
                    v-model="filterPrices.prices[0]"
                    placeholder="Min price"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                  />
                </div>
                <div class="basis-1/3">
                  <label for="filters-price-to" class="mb-2 block text-sm font-medium text-gray-900">
                    To
                  </label>
                  <input
                    type="number"
                    id="filters-price-to"
                    v-model="filterPrices.prices[1]"
                    placeholder="Max price"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500"
                  />
                </div>
                <SecondaryButtonVue class="self-end" @click="priceFilter()">
                  Ok
                </SecondaryButtonVue>
              </div>

              <!-- Category filter -->
              <Disclosure as="div" class="border-t border-gray-200 pt-4">
                <h3 class="-mx-2 -my-3 flow-root">
                  <DisclosureButton class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                    <span class="font-medium text-gray-900">Categories</span>
                    <span class="ml-6 flex items-center">
                      <PlusIcon v-if="!categoriesOpen" class="h-5 w-5" aria-hidden="true" />
                      <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                    </span>
                  </DisclosureButton>
                </h3>
                <DisclosurePanel class="pt-6">
                  <div class="space-y-4">
                    <div v-for="category in categories" :key="category.id" class="flex items-center">
                      <input
                        :id="`filter-category-${category.id}`"
                        :value="category.id"
                        type="checkbox"
                        v-model="selectedCategories"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                      />
                      <label :for="`filter-category-${category.id}`" class="ml-3 text-sm text-gray-600">
                        {{ category.name }}
                      </label>
                    </div>
                  </div>
                </DisclosurePanel>
              </Disclosure>
            </form>

            <!-- Product grid -->
            <div class="lg:col-span-3">
              <!-- Product List -->
              <Products :products="products.data"></Products>
            </div>
          </div>
        </section>
      </main>
    </div>
  </UserLayouts>
</template>
