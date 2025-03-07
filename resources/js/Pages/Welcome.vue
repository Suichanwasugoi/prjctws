<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" /> 
    <div class="bg-violet-300">
        


                <div class="border-2 text-3xl">
                    <nav v-if="canLogin" class="mx-3 flex flex-1 justify-end p-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center px-5 py-3 border border-transparent leading-4 font-bold rounded-md
             text-white bg-violet-500 hover:text-white hover:bg-violet-700 focus:outline-none 
             transition ease-in-out duration-150"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center px-5 py-3 border border-transparent leading-4 font-bold rounded-md
             text-white bg-violet-500 hover:text-white bg-violet-700focus:outline-none 
             transition ease-in-out duration-150 mr-[10px]">
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="inline-flex items-center px-5 py-3 border border-transparent leading-4 font-bold rounded-md
             text-white bg-violet-500 hover:text-white bg-violet-700focus:outline-none 
             transition ease-in-out duration-150">
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>

            </div>
            
    
    <div class="bg-purple-400 h-screen">

    </div>

                
</template>
