<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from './Sidebar.vue';

const { props } = usePage();
const form = ref({});

// Initialize the form with data from props if available
onMounted(() => {
    console.log('props:', props);
    if (props.event) {
        console.log('Received event data:', props.event);
        form.value = {
            event: props.event.event,
            country: props.event.country,
            type: props.event.type,
            date: props.event.date,
        };
    } else {
        console.error('props.event is missing');
    }
});

// Handle form submission
const handleSubmit = async () => {
    console.log('handleSubmit called');
    console.log('Form data at submission:', form.value);

    if (!props.event) {
        console.error('Failed to update event: props.event is undefined');
        return;
    }

    try {
        // Send PUT request to update the event
        const response = await router.put(`/events/${props.event.id}`, form.value);
            if (response.status === 200) {
                router.visit('/events');
            } 
    } catch (error) {
    }
};
</script>

<!-- EventsEdit.vue -->
<template>
    <div class="flex">
      <Sidebar />
    </div>
    <AuthenticatedLayout>
      <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <button class="border border-gray-300 p-2 m-4 bg-violet-300"><a href='/events'>Go Back</a></button>
            <div class="bg-gray-200 text-gray-700 p-6">Edit Event:</div>
            <form @submit.prevent="handleSubmit" class="p-6">
              <div class="mb-4">
                <label for="event" class="block text-gray-700">Event:</label>
                <input type="text" id="event" v-model="form.event" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <div class="mb-4">
                <label for="country" class="block text-gray-700">Country:</label>
                <input type="text" id="country" v-model="form.country" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <div class="mb-4">
                <label for="type" class="block text-gray-700">Description:</label>
                <textarea id="type" v-model="form.type" class="mt-1 block w-full border border-gray-300 rounded-md p-2"></textarea>
              </div>
              <div class="mb-4">
                <label for="date" class="block text-gray-700">Date:</label>
                <input type="date" id="date" v-model="form.date" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <button type="submit" class="border border-gray-300 p-2 bg-yellow-300">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  