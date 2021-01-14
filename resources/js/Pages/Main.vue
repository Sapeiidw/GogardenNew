<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm text-gray-700 underline">
                Dashboard
            </inertia-link>

            <template v-else>
                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                    Login
                </inertia-link>

                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </inertia-link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid mx-20">
    <!-- Question Listing Item Card -->
    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg duration-500 px-2 sm:px-6 md:px-2 py-4 my-6"
        v-for="row in plants.data" :key="row.id"
    >
        <div class="grid grid-cols-12 gap-3">
            <!-- Meta Column -->
            <div class="col-span-0 sm:col-span-2 text-center hidden sm:block">
                <img :src="row.photo_url" :alt="row.name">
            </div>

            <!-- Summary Column -->
            <div class="col-span-12 sm:col-start-3 sm:col-end-13 px-3 sm:px-0">
                <div class="grid block sm:hidden">
                    <div class="flex flex-wrap">
                        <div class="mr-2">
                            <div class="inline-block">
                                <i class="uil uil-clock mr-1"></i>
                                <span class="text-sm font-light">
                                    {{ row.created_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center hidden sm:block">
                    <span class="font-light text-gray-600">
                        {{ row.created_at }}
                    </span>
                </div>

                <div class="mt-2">
                    <a href="#" class="sm:text-sm md:text-md lg:text-lg text-gray-700 font-bold hover:underline">
                        {{ row.name }}
                    </a>

                    <p class="mt-2 text-gray-600 text-sm md:text-md">
                        {{ row.description }}
                    </p>
                </div>

                <!-- Question Labels -->
                <div class="grid grid-cols-2 mt-4 my-auto">
                    <!-- Categories  -->
                    <div class="col-span-12 lg:col-span-8">
                        <span href="#" class="inline-block rounded-full text-white 
                            bg-red-400 hover:bg-red-500 duration-300 
                            text-xs font-bold 
                            mr-1 md:mr-2 mb-2 px-2 md:px-4 py-1 
                            opacity-90 hover:opacity-100"
                            v-for="category in row.categories" :key="category.id"
                            >
                            {{ category.title }}
                        </span>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
        

        </div>
    </div>
</template>

<script>
export default {
    props: {
        plants : Object,
    }
}
</script>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>

<script>
    export default {
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        }
    }
</script>


<!-- component -->
<!-- Unicons -->
<style rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@3.0.6/css/line.css"></style

