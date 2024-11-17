<template>
    <footer class="bg-gray-900 dark:bg-white mt-20">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">

                <div class="mb-6 md:mb-0">
                    <Link href="/" class="flex items-center">
                    <img src="@/images/logo.png" class="h-30 md:h-40" alt="Logo" />
                    </Link>
                </div>

                <div class="md:grid md:grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3 font-thin">
                    <div class="mb-6 md:mb-0">
                        <h2 class="mb-2 md:mb-6 text-sm font-semibold text-white uppercase dark:text-white">Contact
                            Detail</h2>
                        <div>
                            <ul class="text-white dark:text-white">
                                <li class="mb-2">
                                    <p class="hover:underline">Tel : +(62) 813 xxxx xxxx </p>
                                </li>
                                <li>
                                    <p class="hover:underline">E-mail : 2181013@unai.edu</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <hr class="my-4 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />

            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                © 2024
                <Link href="/" class="hover:underline">Kebun Ku</Link>. All Rights Reserved.
            </div>

        </div>
    </footer>
</template>
