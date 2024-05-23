<script setup>
  import { ref, onMounted } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Sidebar from './Sidebar.vue';
  
  const { props } = usePage();
  const form = ref({});
  
  // Initialize the form with data from props if available
  onMounted(() => {
      console.log('props:', props);
      if (props.user) {
          console.log('Received user data:', props.user);
          form.value = {
              name: props.user.name,
              email: props.user.email,
          };
      } else {
          console.error('props.user is missing');
      }
  });
  
  // Handle form submission
  const updateUser = async () => {
      console.log('updateUser called');
      console.log('Form data at submission:', form.value);
  
      try {
          // Send PUT request to update the user
          const response = await router.put(`/users/${props.user.id}`, form.value);
          if (response.status === 200) {
              router.visit('/users');
          } 
      } catch (error) {
      }
  };
  </script>

<template>
    <div class="flex">
      <Sidebar />
    </div>
    <AuthenticatedLayout>
      <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <button class="border border-gray-300 p-2 m-4 bg-violet-300"><a href='/users'>Go Back</a></button>
            <div class="bg-gray-200 text-gray-700 p-6">Edit User:</div>
            <form @submit.prevent="updateUser" class="p-6">
              <div class="mb-4">
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" v-model="form.name" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <div class="mb-4">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" id="email" v-model="form.email" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
              </div>
              <button type="submit" class="border border-gray-300 p-2 bg-yellow-300">Update User</button>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>