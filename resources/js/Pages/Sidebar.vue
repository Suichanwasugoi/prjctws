<template>
    <div>
      <!-- Sidebar trigger icon -->
      <div
        class="fixed top-4 left-4 p-2 cursor-pointer bg-violet-700 text-white rounded-md"
        @mouseenter="openSidebar">
        <div class="w-6 h-1 bg-white mb-1"></div>
        <div class="w-6 h-1 bg-white mb-1"></div>
        <div class="w-6 h-1 bg-white"></div>
      </div>
  
      <!-- Sidebar -->
      <div
        class="fixed top-0 left-0 h-full w-64 bg-violet-700 text-white transition-transform duration-300 ease-in-out"
        :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }"
        @mouseenter="openSidebar"
        @mouseleave="closeSidebar"
      >
        <ul class="mt-20">
          <li class="p-2 hover:bg-violet-400"><a href="/dashboard">Dashboard</a></li>
          <li class="p-2 hover:bg-violet-400"><a href="/profile">My Account</a></li>
          <li class="p-2 hover:bg-violet-400"><a href="/events">Events</a></li>
          <li class="p-2 hover:bg-violet-400"><a href="/users">Users</a></li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const isSidebarOpen = ref(false);
  
  const openSidebar = () => {
    isSidebarOpen.value = true;
  };
  
  const closeSidebar = () => {
    isSidebarOpen.value = false;
  };
  </script>
  
  
  <style scoped>

  </style>
  