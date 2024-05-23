<script>
import { ref, onMounted } from 'vue';
import { Line } from 'vue-chartjs';
import 'chart.js/auto';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from './Sidebar.vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import moment from 'moment';

export default {
  components: {
    Line,
    AuthenticatedLayout,
    Sidebar,
  },
  setup() {
    const externalEvents = ref([]);
    const eventList = ref({
      data: [],
      current_page: 1,
      last_page: 1,
    });

    const staticMonthlyStats = [
      { month: 1, count: 0 },
      { month: 2, count: 0 },
      { month: 3, count: 0 },
      { month: 4, count: 0 },
      { month: 5, count: 2 },
      { month: 6, count: 4 },
      { month: 7, count: 3 },
      { month: 8, count: 1 },
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
          borderColor: '#00FF00',
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
        // Simulated static data
        const staticData = [
          { month: 5, count: 2 },
          { month: 6, count: 4 },
          { month: 7, count: 3 },
          { month: 8, count: 1 }
        ];

        console.log('Monthly stats data:', staticData);

        // Create a new array to store the updated counts for each month
        const updatedData = Array(12).fill(0);

        staticData.forEach(item => {
          const monthIndex = item.month - 1;
          updatedData[monthIndex] = item.count;
        });

        // Update the data property of the chartData dataset
        chartData.value.datasets[0].data = updatedData;

        console.log('Updated chart data:', chartData.value.datasets[0].data);
      } catch (error) {
        console.error('Failed to fetch monthly statistics:', error);
      }
    };

    onMounted(() => {
      fetchMonthlyStats();
      fetchEvents(eventList.value.current_page);
      fetchExternalEvents();
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
          eventList.value = data.events;

          // Logging for debugging purposes
          console.log('Fetched events data:', data.events);
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

    return {
      externalEvents,
      eventList,
      chartData,
      chartOptions,
      editEvent,
      deleteEvent,
      fetchEvents,
    };
  },
};
</script>
<template>
  <AuthenticatedLayout>
    <div class="flex">
      <Sidebar />
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-violet-800">Events List</div>
        </div>
      </div>
    </div>
    <div>
      <button class="border-solid border-2 ml-[1180px] p-2 bg-white rounded-xl" type="submit">
        <a href='/eventscreate'>Create New Event</a>
      </button>
    </div>
    <br/>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-bold mb-4">Event Details</h2>
        <table class="min-w-full border border-violet-800">
          <thead>
            <tr class="bg-violet-300 text-left">
              <th class="p-2 border border-gray-300">Event</th>
              <th class="p-2 border border-gray-300">Country</th>
              <th class="p-2 border border-gray-300">Type</th>
              <th class="p-2 border border-gray-300">Date</th>
              <th class="p-2 border border-gray-300">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="event in eventList.data" :key="event.id" class="border-b">
              <td class="p-2 border border-gray-300">{{ event.event }}</td>
              <td class="p-2 border border-gray-300">{{ event.country }}</td>
              <td class="p-2 border border-gray-300">{{ event.type }}</td>
              <td class="p-2 border border-gray-300">{{ event.date }}</td>
              <td class="p-2 border border-gray-300">
                <button @click="editEvent(event.id)" class="text-blue-500 border-2 rounded-sm p-2 mr-2">Edit</button>
                <button @click="deleteEvent(event.id)" class="text-red-500 border-2 rounded-sm p-2">Delete</button>
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
        <h2 class="text-xl font-bold mb-4">Event History to Commemorate</h2>
        <table class="min-w-full border border-violet-800">
          <thead>
            <tr class="bg-violet-300 text-left">
              <th class="p-2 border border-gray-300">Event</th>
              <th class="p-2 border border-gray-300">Country</th>
              <th class="p-2 border border-gray-300">Type</th>
              <th class="p-2 border border-gray-300">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="event in externalEvents" :key="event.id" class="border-b">
              <td class="p-2 border border-gray-300">{{ event.name }}</td>
              <td class="p-2 border border-gray-300">{{ event.country }}</td>
              <td class="p-2 border border-gray-300">{{ event.type }}</td>
              <td class="p-2 border border-gray-300">{{ event.date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br/>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Event Statistics</h2>
            <Line :data="chartData" :options="chartOptions" />
        </div>
      </div>
  </AuthenticatedLayout>
</template>
