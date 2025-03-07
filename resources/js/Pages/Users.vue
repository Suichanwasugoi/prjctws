<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from './Sidebar.vue';
import { Head, router } from '@inertiajs/vue3';

const userList = ref([]);

const isSidebarOpen = ref(true);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

async function fetchUsers() {
  try {
    const response = await axios.get('/api/users');
    userList.value = response.data.users;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
}

async function editUser(userId) {
  router.visit(`/users/${userId}/edit`);
}

async function confirmDeleteUser(userId) {
  const result = confirm('Are you sure you want to delete this user?');
  if (result) {
    deleteUser(userId);
  }
}

async function deleteUser(userId) {
  try {
    await axios.delete(`/users/${userId}`);
    alert('User deleted successfully!');
    userList.value = userList.value.filter(u => u.id !== userId);
  } catch (error) {
    console.error('Error deleting user:', error);
  }
}

onMounted(fetchUsers);
</script>

<template>
  <div class="flex">
      <Sidebar :isSidebarOpen="isSidebarOpen" @toggleSidebar="toggleSidebar" />
    </div>
    <AuthenticatedLayout :class="{ 'ml-64': isSidebarOpen }" class="flex-grow flex flex-col transition-all duration-300 ease-in-out">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-violet-800 text-4xl font-extrabold tracking-widest">Admin List</div>
          <br/>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 my-4">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left  font-bold text-2xl text-gray-700 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left  font-bold text-2xl text-gray-700 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left  font-bold text-2xl text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in userList" :key="user.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xl tracking-wider font-medium text-gray-900">{{ user.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xl tracking-widest text-gray-500">{{ user.email }}</div>
                  </td>
                  <td class="px-10 py-4 whitespace-nowrap text-xl font-medium">
                    <button @click="editEvent(event.id)" class="text-blue-500 border-2 rounded-sm p-2 mr-2">
                  <font-awesome-icon :icon="['fas', 'edit']" /></button>
                  <button @click="deleteEvent(event.id)" class="text-red-500 border-2 rounded-sm p-2">
                  <font-awesome-icon :icon="['fas', 'trash']" />
                </button>
                </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
