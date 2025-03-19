<script setup lang="ts">
import { onMounted, ref } from "vue";

interface Event {
  id: string;
  title: string;
  description: string;
  startDate: string;
  endDate: string;
}

const events = ref<Event[]>([]);

const fetchEvents = async () => {
  try {
    const response = await fetch("http://localhost:8000/api/events");
    const data = await response.json();
    events.value = data.items;
  } catch (error) {
    console.error("Failed to fetch events:", error);
  }
};


onMounted(fetchEvents);
</script>

<template>
  <div class="container">
    <h1>Events</h1>
  </div>
</template>

<style scoped>
</style>
