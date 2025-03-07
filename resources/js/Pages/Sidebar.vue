<script setup>
import { defineProps, defineEmits, onMounted, ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTachometerAlt, faCalendarAlt, faUsers, faUser } from '@fortawesome/free-solid-svg-icons';

const props = defineProps({
  isSidebarOpen: Boolean
});

const emits = defineEmits(['toggleSidebar']);

const isSidebarOpen = ref(props.isSidebarOpen);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
  emits('toggleSidebar', isSidebarOpen.value);
  localStorage.setItem('sidebarState', isSidebarOpen.value ? 'open' : 'closed');
};

onMounted(() => {
  const savedState = localStorage.getItem('sidebarState');
  if (savedState === 'closed') {
    isSidebarOpen.value = false;
    emits('toggleSidebar', false);
  }
});
</script>

<template>
  <div>
    <div
      class="fixed top-4 left-4 p-2 cursor-pointer bg-violet-700 text-white rounded-md z-50"
      @click="toggleSidebar">
      <div class="w-6 h-1 bg-white mb-1"></div>
      <div class="w-6 h-1 bg-white mb-1"></div>
      <div class="w-6 h-1 bg-white"></div>
    </div>

    <!-- Sidebar -->
    <div
      class="fixed top-0 left-0 h-full w-[260px] text-2xl font-extrabold bg-violet-700 text-white transition-transform duration-300 ease-in-out z-40"
      :class="{ 'translate-x-0': isSidebarOpen, '-translate-x-full': !isSidebarOpen }">
      <!-- Logo -->
      <div class="flex justify-center items-center p-2 mt-10">
        <img src="/image/Logo2.png" alt="Logo" class="w-auto h-fill">
      </div>

      <!-- Links -->
      <ul class="mt-[10px]">
        <li class="p-8 hover:bg-violet-400"><a href="/dashboard"><FontAwesomeIcon icon="tachometer-alt" /> Dashboard</a></li>
        <li class="p-8 hover:bg-violet-400"><a href="/events"><FontAwesomeIcon icon="calendar-alt" /> Events</a></li>
        <li class="p-8 hover:bg-violet-400"><a href="/users"><FontAwesomeIcon icon="users" /> Users</a></li>
        <li class="p-8 hover:bg-violet-400"><a href="/profile"><FontAwesomeIcon icon="user" /> My Account</a></li>
      </ul>
    </div>

    <!-- Sidebar icons when collapsed -->
    <div
      class="fixed top-0 left-0 h-full w-16 flex flex-col items-center bg-violet-700 text-white transition-transform duration-300 ease-in-out z-30"
      :class="{ 'translate-x-0': !isSidebarOpen, '-translate-x-full': isSidebarOpen }">
      <ul class="mt-20">
        <li class="mb-8 hover:bg-violet-400 p-4 rounded-md"><a href="/dashboard"><FontAwesomeIcon icon="tachometer-alt" /></a></li>
        <li class="mb-8 hover:bg-violet-400 p-4 rounded-md"><a href="/events"><FontAwesomeIcon icon="calendar-alt" /></a></li>
        <li class="mb-8 hover:bg-violet-400 p-4 rounded-md"><a href="/users"><FontAwesomeIcon icon="users" /></a></li>
        <li class="mb-8 hover:bg-violet-400 p-4 rounded-md"><a href="/profile"><FontAwesomeIcon icon="user" /></a></li>
      </ul>
    </div>
  </div>
</template>
