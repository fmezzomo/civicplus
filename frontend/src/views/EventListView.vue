<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { fetchEvents } from "../services/apiService";
import EventList from "../components/EventList.vue";

interface Event {
  id: string;
  title: string;
  description: string;
  startDate: string;
  endDate: string;
}

const events = ref<Event[]>([]);
const router = useRouter();

const loadEvents = async () => {
  try {
    events.value = await fetchEvents();
  } catch (error) {
    console.error("Failed to load events:", error);
  }
};

onMounted(loadEvents);
</script>

<template>
  <div class="container">
    <h1>Events</h1>
    <button @click="router.push('/event/new')" class="add-btn">Add Event</button>
    <EventList :events="events" />
  </div>
</template>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
  text-align: center;
}
.add-btn {
  margin-bottom: 15px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}
</style>
