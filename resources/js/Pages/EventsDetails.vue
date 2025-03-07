<template>
  <div
    class="fixed inset-y-0 right-0 bg-gray-300 shadow-lg transform transition-transform duration-300 ease-in-out z-50"
    :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }"
    @click.stop
    style="width: 1000px;"
  >
    <div class="p-6">
      <button @click="close" class="text-white text-2xl p-4 font-bold border rounded-lg items-center tracking-widest bg-red-500">Close</button>
      <div v-if="event && Object.keys(event).length > 0">
        <h2 class="text-4xl font-bold mb-4 py-8">{{ event.event }}</h2>
        <div class="text-2xl font-bold tracking-wide py-32">
        <p><strong>Description:</strong> {{ event.type }}</p>
        <p><strong>Location:</strong> {{ event.country }}</p>
        <p><strong>Date:</strong> {{ event.date }}</p>
      </div>
      </div>
      <div v-else>
        <p>No event details available.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    isOpen: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    close() {
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.translate-x-full {
  transform: translateX(100%);
}
.translate-x-0 {
  transform: translateX(0);
}
</style>
