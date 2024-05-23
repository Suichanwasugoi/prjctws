<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import { ref } from 'vue';

const form = ref({
    event: '',
    country: '',
    type: '',
    date: ''
});

const handleSubmit = () => {
    router.post('/events', form.value);
};
</script>

<Head title="EventsCreate" />

<!-- EventsCreate.vue -->
<template>
    <div class="flex">
      <Sidebar />
    </div>
    <AuthenticatedLayout>
      <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <button class="border border-gray-300 p-2 m-4 bg-violet-300"><a href='/events'>Go Back</a></button>
            <div class="bg-gray-200 text-gray-700 p-6">Create a new event:</div>
            <form @submit.prevent="handleSubmit" class="p-6">
              <div class="mb-4">
                <label for="name" class="block text-gray-700">Event:</label>
                <input type="text" id="name" v-model="form.event" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <div class="mb-4">
                <label for="country" class="block text-gray-700">Country:</label>
                <input type="text" id="country" v-model="form.country" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <div class="mb-4">
                <label for="description" class="block text-gray-700">Description:</label>
                <textarea id="description" v-model="form.type" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
              </div>
              <div class="mb-4">
                <label for="date" class="block text-gray-700">Date:</label>
                <input type="date" id="date" v-model="form.date" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <button type="submit" class="border border-gray-300 p-2 bg-green-300">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
