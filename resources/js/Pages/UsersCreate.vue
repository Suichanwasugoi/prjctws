<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import { ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    password: ''
});

const handleSubmit = async () => {
    await router.post('/users', form.value);
};
</script>

<Head title="UsersCreate" />

<template>
    <div class="flex">
        <Sidebar />
    </div>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <button class="border-solid border-2 p-2" type="submit"><a href='/users'>Go Back</a></button>
                    <div class="p-6 text-violet-800">Create a new user:</div>
                    <form @submit.prevent="handleSubmit">
                        <div>
                            <label for="name">Name:</label>
                            <input class="ml-2" type="text" id="name" v-model="form.name" required>
                        </div>
                        <br/>
                        <div>
                            <label for="email">Email:</label>
                            <input class="ml-2" type="email" id="email" v-model="form.email" required>
                        </div>
                        <br/>
                        <div>
                            <label for="password">Password:</label>
                            <input class="ml-2" type="password" id="password" v-model="form.password" required>
                        </div>
                        <br/>
                        <button class="border-solid border-2 p-2" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
