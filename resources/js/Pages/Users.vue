<!-- Users.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from './Sidebar.vue';
import { Head, router } from '@inertiajs/vue3';

const userList = ref([]);

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
  <AuthenticatedLayout>
    <div class="flex">
      <Sidebar />
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-violet-800">Users List</div>
          <br/>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 my-4">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in userList" :key="user.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button @click="editUser(user.id)" class="text-blue-500 hover:underline mr-2">Edit</button>
                    <button @click="confirmDeleteUser(user.id)" class="text-red-500 hover:underline mr-2">Delete</button>
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
