<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
</script>

<Head title="Dashboard" />

<template>
    <div class="flex">
        <Sidebar />
    </div>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-violet-800">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

