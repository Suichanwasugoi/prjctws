<script setup>
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import { ref, onMounted, computed } from 'vue';

// Array of image URLs
const images = ref([
  './image/mlbb.jpg',
  './image/azur.jpg',
  './image/hydra.png'
]);

// Index of the current image in the carousel
const currentIndex = ref(0);

// Function to navigate to the next image
const next = () => {
  currentIndex.value = (currentIndex.value + 1) % images.value.length;
};

// Function to navigate to the previous image
const prev = () => {
  currentIndex.value = (currentIndex.value - 1 + images.value.length) % images.value.length;
};

// Automatically move to the next image every 3 seconds
onMounted(() => {
  setInterval(next, 10000); // Change image every 3 seconds
});

// Array of video sources
const videos = ref([
  { src: '/image/AzurLane.mp4' },
  { src: '/image/mlbbex.mp4' },
]);

// Index of the current video in the carousel
const currentVideoIndex = ref(0);

// Function to play the next video when the current video ends
const playNextVideo = (index) => {
  // Check if the ended video is the current one
  if (index === currentVideoIndex.value) {
    // Move to the next video
    currentVideoIndex.value = (currentVideoIndex.value + 1) % videos.value.length;
  }
};

// Flag to toggle the sidebar
const isSidebarOpen = ref(true);

// Function to toggle the sidebar
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// Array to store today's events
const todayEvents = ref([]);

// Fetch today's events from the API when component is mounted
onMounted(async () => {
  await fetchTodayEvents();
});

// Function to fetch today's events
const fetchTodayEvents = async () => {
  try {
    const response = await axios.get('/api/events-for-today');
    todayEvents.value = response.data; // Assuming the response contains the events directly
  } catch (error) {
    console.error('Error fetching today\'s events:', error);
  }
};

// Computed property to calculate the total number of events this month
const totalEventsThisMonth = computed(() => {
  // Get the current month and year
  const currentDate = new Date();
  const currentMonth = currentDate.getMonth() + 1; // Month is zero-based
  const currentYear = currentDate.getFullYear();

  // Filter the events that occurred this month
  const eventsThisMonth = todayEvents.value.filter(event => {
    const eventDate = new Date(event.date);
    return eventDate.getMonth() + 1 === currentMonth && eventDate.getFullYear() === currentYear;
  });

  // Return the total count of events this month
  return eventsThisMonth.length;
});
</script>

<template>
  <div class="flex h-screen">
    <!-- Sidebar component -->
    <Sidebar :isSidebarOpen="isSidebarOpen" @toggleSidebar="toggleSidebar" />

    <!-- AuthenticatedLayout component -->
    <AuthenticatedLayout :class="{ 'ml-64': isSidebarOpen }" class="flex-grow flex flex-col transition-all duration-300 ease-in-out">
      <!-- Main content -->

      <!-- Events Listing and Number of Events this Month -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-row justify-between mt-2">
         <!-- Events Listing for Today -->
         <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg p-6 flex-grow">
          <h2 class="text-2xl font-semibold mb-4">Events for Today</h2>
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-violet-300 text-left tracking-widest text-xl font-bold">
                <th class="p-2 border-r">Event</th>
                <th class="p-2 border-r">Country</th>
                <th class="p-2 border-r">Description</th>
                <th class="p-2 border-r">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="event in todayEvents" :key="event.id">
                <td class="p-2 border-r">{{ event.event }}</td>
                <td class="p-2 border-r">{{ event.country }}</td>
                <td class="p-2 border-r">{{ event.type }}</td>
                <td class="p-2 border-r">{{ event.date }}</td>
              </tr>
            </tbody>
          </table>
         </div>
         <!-- Total Number of Events this Month -->
         <div class="bg-violet-900 text-4xl text-white py-2 px-4 rounded text-center ml-14 tracking-wider self-start mt-16">
           Number of Events this Month: {{ totalEventsThisMonth }}
         </div>
      </div>

      <div class="flex-grow p-4 sm:p-8 bg-purple-400 flex">
        <!-- Image Carousel -->
        <div class="relative overflow-hidden mb-8 w-full max-w-3xl ml-30 content-center">
          <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
            <div v-for="image in images" :key="image" class="min-w-full">
              <img :src="image" class="w-full h-auto object-cover">
            </div>
          </div>
          <button @click="prev" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-transparent text-white p-2 text-4xl"><</button>
          <button @click="next" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-transparent text-white p-2 text-4xl">></button>
        </div>

        <!-- Trends -->
        <div class="mr-8 ml-32 mt-2"> <!-- Added margin-top -->
          <div class="border-b border-gray-400 pb-2">
            <h2 class="text-4xl font-semibold text-violet-100 bg-purple-800">Trending Events happening Today!</h2>
          </div>
        <!-- Video Carousel -->
        <div class="relative overflow-hidden mb-8 w-full max-w-3xl ml-30">
          <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${currentVideoIndex * 100}%)` }">
            <video v-for="(video, index) in videos" :key="index" :src="video.src" controls autoplay muted @ended="playNextVideo(index)" class="min-w-full"></video>
          </div>
        </div>
      </div>
      </div>
       <!-- Trends Summary -->
       <div class="bg-purple-400">
       <div class="bg-white p-6 rounded-lg shadow-md max-w-7xl mx-auto">
          <h2 class="text-4xl font-semibold mb-4 bg-purple-300 font-sans tracking-widest">Trends Summary</h2>
          <p class="text-gray-700 tracking-wider text-xl font-mono">
            Today, we have GAME EVENTS!
            <br/>
            We Will bring you the LATEST Updates for the Events that you`re interested with.
            <br/>
            In today`s Event/s
            We have AZUR LANE, MOBILE LEGENDS: BANG BANG, and the 2017 Game of the Year winner, with
            <br/>
            its sequel been released last year with some delays.. The Legend of Zelda Tears of the Kingdom!
            <br/>
            <br/>
            <br/>
            Azur Lane released a new event titled "Light of the Martyrium" departing the newest Iris Libre Ultra Rare ship.
            <br/>
            Alsace! Log in now to the game to view more details.
            <br/>
            <br/>
            Mobile Legends released there sequel event for the Exorcists. As they previously released the skins for
            <br/>
            Kagura and Yu Zhong exorcists, in this sequel event, as also for their celebration of their anniversary.
            <br/>
            they released the new Exorcists skins of Hayabusa and Granger!
            <br/>
            Log in to the game now to see more of the details.
            <br/>
            <br/>
            The Legend of Zelda Tears of the Kingdom. Players are trying to beat The Hydra that`s been guarding the Hryule Castle
            <br/>
            for The past months. Trying to beat speedrunners for the most less time expended defeating the Hydra.
            <br/>
            come and partake to this online event!. Don`t forget to record your entry for documentation!
          </p>
        </div>
      </div>
    </AuthenticatedLayout>
  </div>
</template>
