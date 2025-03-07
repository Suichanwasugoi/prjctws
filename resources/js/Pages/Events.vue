<script>
import { ref, onMounted } from 'vue';
import { Line } from 'vue-chartjs';
import 'chart.js/auto';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from './Sidebar.vue';
import EventsDetails from './EventsDetails.vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';

export default {
  components: {
    Line,
    AuthenticatedLayout,
    Sidebar,
    EventsDetails,
  },
  setup() {
    const externalEvents = ref([]);
    const eventList = ref({
      data: [],
      current_page: 1,
      last_page: 1,
    });
    const selectedEvent = ref(null);
    const isPopupOpen = ref(false);

    const staticMonthlyStats = [
      { month: 1, count: 0 },
      { month: 2, count: 0 },
      { month: 3, count: 0 },
      { month: 4, count: 0 },
      { month: 5, count: 5 },
      { month: 6, count: 4 },
      { month: 7, count: 1 },
      { month: 8, count: 0 },
      { month: 9, count: 0 },
      { month: 10, count: 0 },
      { month: 11, count: 0 },
      { month: 12, count: 0 },
    ];

    const chartData = ref({
      labels: moment.monthsShort(),
      datasets: [
        {
          label: 'Events Added per Month',
          data: staticMonthlyStats.map(item => item.count),
          borderColor: '#b025dd',
          borderWidth: 2,
          fill: false,
          tension: 0.4,
        },
      ],
    });

    const chartOptions = {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        x: {
          title: {
            display: true,
            text: 'Months',
          },
          beginAtZero: false,
        },
        y: {
          title: {
            display: true,
            text: 'Number of Events',
          },
          min: 0,
          max: 20,
          stepSize: 1,
        },
      },
      plugins: {
        legend: {
          display: true,
        },
      },
    };

    const fetchMonthlyStats = async () => {
      try {
        const staticData = [
          { month: 5, count: 2 },
          { month: 6, count: 4 },
          { month: 7, count: 3 },
          { month: 8, count: 1 }
        ];

        const updatedData = Array(12).fill(0);

        staticData.forEach(item => {
          const monthIndex = item.month - 1;
          updatedData[monthIndex] = item.count;
        });

        chartData.value.datasets[0].data = updatedData;

      } catch (error) {
        console.error('Failed to fetch monthly statistics:', error);
      }
    };

    onMounted(() => {
      fetchEvents();
      fetchExternalEvents();
      fetchMonthlyStats();
    });

    const fetchExternalEvents = async () => {
      const url = 'https://holidays-by-api-ninjas.p.rapidapi.com/v1/holidays?country=us&year=2021&type=federal_holiday';
      const options = {
        method: 'GET',
        headers: {
          'x-rapidapi-key': 'd191756b40mshbe5b613a03bd297p1c5fafjsn54fcd4ccc4d9',
          'x-rapidapi-host': 'holidays-by-api-ninjas.p.rapidapi.com',
          'Content-Type': 'application/json'
        }
      };

      try {
        const response = await fetch(url, options);
        const data = await response.json();
        externalEvents.value = data;
      } catch (error) {
        console.error('Failed to fetch external events:', error);
      }
    };

    const fetchEvents = async (page = 1) => {
      try {
        const response = await fetch(`/api/events?page=${page}`);
        const data = await response.json();

        if (data && data.events) {
          data.events.data.forEach(event => {
            const today = moment().startOf('day');
            const eventDate = moment(event.date);
            if (eventDate.isSame(today, 'day')) {
              event.status = { text: 'Available', color: 'green' };
            } else if (eventDate.isAfter(today, 'day')) {
              event.status = { text: 'Coming Soon', color: 'blue' };
            } else {
              event.status = { text: 'Already Ended', color: 'gray' };
            }
          });
          eventList.value = data.events;
        } else {
          console.error('Unexpected data format: events is not an array');
        }
      } catch (error) {
        console.error('Failed to fetch events:', error);
      }
    };

    const editEvent = (eventId) => {
      router.visit(`/events/${eventId}/edit`);
    };

    const deleteEvent = (eventId) => {
      if (confirm('Are you sure you want to delete this event?')) {
        axios
          .delete(`/api/events/${eventId}`, {
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
          })
          .then(() => {
            fetchEvents(eventList.value.current_page);
          })
          .catch(error => {
            console.error('Failed to delete event:', error);
          });
      }
    };

    const isSidebarOpen = ref(true);

    const toggleSidebar = () => {
      isSidebarOpen.value = !isSidebarOpen.value;
    };

    const showEventsDetails = (event) => {
      selectedEvent.value = event;
      isPopupOpen.value = true;
    };

    const closePopup = () => {
      isPopupOpen.value = false;
      selectedEvent.value = null;
    };

    return {
      externalEvents,
      eventList,
      chartData,
      chartOptions,
      editEvent,
      deleteEvent,
      fetchEvents,
      toggleSidebar,
      isSidebarOpen,
      selectedEvent,
      isPopupOpen,
      showEventsDetails,
      closePopup,
    };
  },
};
</script>

<template>
  <AuthenticatedLayout :class="{ 'ml-64': isSidebarOpen }" class="flex-grow flex flex-col transition-all duration-300 ease-in-out relative">
    <div class="flex">
      <Sidebar :isSidebarOpen="isSidebarOpen" @toggleSidebar="toggleSidebar" />
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-violet-800 text-4xl font-extrabold tracking-widest">Events List</div>
        </div>
      </div>
    </div>
    <div>
      <button class="border-solid border-2 ml-[1080px] p-6 text-xl font-extrabold text-white tracking-widest bg-violet-400 rounded-xl" type="submit">
        <a href='/eventscreate'>Create New Event</a>
      </button>
    </div>
    <br />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-3xl font-bold mb-4">Event Details</h2>
        <table class="min-w-full border">
          <thead>
            <tr class="bg-violet-300 text-left tracking-widest text-xl font-bold">
              <th class="p-2 border-r">Event Name</th>
              <th class="p-2 border-r">Location</th>
              <th class="p-2 border-r">Description</th>
              <th class="p-2 border-r">Start Date</th>
              <th class="p-2 border-r">Status</th>
              <th class="p-2 border-r">Details</th>
              <th class="p-2">Action</th>
            </tr>
          </thead>
          <tbody>
          <tr v-for="event in eventList.data" :key="event.id" class="border-b tracking-widest font-mono text-lg">
            <td class="p-2 border-r">{{ event.event }}</td>
            <td class="p-2 border-r">{{ event.country }}</td>
            <td class="p-2 border-r">{{ event.type }}</td>
            <td class="p-2 border-r">{{ event.date }}</td>
            <td class="p-2 border-r flex items-center space-x-2">
              <span :class="{'text-green-500': event.status.text === 'Available', 'text-blue-500': event.status.text === 'Coming Soon', 'text-gray-500': event.status.text === 'Already Ended'}">
                {{ event.status.text }}
              </span>
              <span :class="{'bg-green-500': event.status.text === 'Available', 'bg-blue-500': event.status.text === 'Coming Soon', 'bg-gray-500': event.status.text === 'Already Ended'}" class="w-2.5 h-2.5 rounded-full"></span>
            </td>
            <td class="p-2 border-r">
              <button @click="showEventsDetails(event)" class="text-violet-700 underline">See More</button>
            </td>
            <td class="p-2 flex space-x-2">
              <button @click="editEvent(event.id)" class="text-blue-500 border-2 rounded-sm p-2 mr-2">
                <font-awesome-icon :icon="['fas', 'edit']" />
              </button>
              <button @click="deleteEvent(event.id)" class="text-red-500 border-2 rounded-sm p-2">
                <font-awesome-icon :icon="['fas', 'trash']" />
              </button>
            </td>
          </tr>
        </tbody>
        </table>
        <div class="flex justify-center mt-4">
          <button
            v-for="page in eventList.last_page"
            :key="page"
            :class="{ 'bg-violet-400': page === eventList.current_page }"
            @click="fetchEvents(page)"
            class="border p-2 rounded mr-2"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-3xl font-bold mb-4">Event History to Commemorate</h2>
        <table class="min-w-full border border-violet-800">
          <thead>
            <tr class="bg-violet-300 text-left tracking-widest text-xl font-bold">
              <th class="p-2 border border-gray-300">Event</th>
              <th class="p-2 border border-gray-300">Country</th>
              <th class="p-2 border border-gray-300">Description</th>
              <th class="p-2 border border-gray-300">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="event in externalEvents" :key="event.id" class="border-b tracking-widest font-mono text-lg">
              <td class="p-2 border border-gray-300">{{ event.name }}</td>
              <td class="p-2 border border-gray-300">{{ event.country }}</td>
              <td class="p-2 border border-gray-300">{{ event.type }}</td>
              <td class="p-2 border border-gray-300">{{ event.date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-3xl font-bold mb-4">Event Statistics</h2>
        <Line :data="chartData" :options="chartOptions" />
      </div>
    </div>
    <EventsDetails :event="selectedEvent" :isOpen="isPopupOpen" @close="closePopup" />
    <div v-if="isPopupOpen" class="fixed inset-0 bg-black opacity-50 z-40" @click="closePopup"></div>
  </AuthenticatedLayout>
</template>

<style scoped>
.translate-x-full {
  transform: translateX(100%);
}
.translate-x-0 {
  transform: translateX(0);
}
</style>
