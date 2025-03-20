<script setup lang="ts">
import { onMounted, ref, computed } from "vue";
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
const filters = ref({
  title: "",
  startDate: new Date().toISOString().split("T")[0], // Today's date
  endDate: "",
  orderBy: {
    field: "startDate",
    direction: "asc"
  }
});

const filteredEvents = computed(() => {
  let filtered = [...events.value];

  if (filters.value.title) {
    filtered = filtered.filter(event =>
      event.title.toLowerCase().includes(filters.value.title.toLowerCase())
    );
  }

  if (filters.value.startDate) {
    filtered = filtered.filter(event => event.startDate >= filters.value.startDate);
  }

  if (filters.value.endDate) {
    filtered = filtered.filter(event => event.endDate <= filters.value.endDate);
  }

  filtered.sort((a, b) => {
    const field = filters.value.orderBy.field as keyof Event;
    const direction = filters.value.orderBy.direction === "asc" ? 1 : -1;
    return a[field] > b[field] ? direction : -direction;
  });

  return filtered;
});

const router = useRouter();

const loadEvents = async () => {
  try {
    events.value = await fetchEvents();
  } catch (error) {
    console.error("Failed to load events:", error);
  }
};

onMounted(loadEvents);

const applyFilters = () => {
  console.log("Filters applied:", filters.value);
};
</script>

<template>
  <div class="container">
    <h1>Events</h1>
    <div class="filter-container">
      <form @submit.prevent="applyFilters" class="filter-form">
        <div class="filter-fields">
          <label>
            Title:
            <input v-model="filters.title" type="text" placeholder="Search by title" />
          </label>
          <label>
            Start Date:
            <input v-model="filters.startDate" type="date" placeholder="Start date" />
          </label>
          <label>
            End Date:
            <input v-model="filters.endDate" type="date" placeholder="End date" />
          </label>
          <label>
            Order By:
            <select v-model="filters.orderBy.field">
              <option value="startDate">Start Date</option>
              <option value="endDate">End Date</option>
              <option value="title">Title</option>
            </select>
          </label>
          <label>
            Order Direction:
            <select v-model="filters.orderBy.direction">
              <option value="asc">Ascending</option>
              <option value="desc">Descending</option>
            </select>
          </label>
        </div>
        <div class="filter-actions">
          <button type="submit" class="add-btn">Apply Filters</button>
        </div>
      </form>
    </div>
    <button @click="router.push('/event/new')" class="add-btn">Add Event</button>
    <EventList :events="filteredEvents" />
  </div>
</template>

<style scoped>
.add-btn {
  margin-bottom: 15px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}
.filter-container {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
  background-color: #f9f9f9;
}
.filter-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.filter-fields {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}
.filter-form input,
.filter-form select {
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.filter-form label {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.filter-actions {
  display: flex;
  justify-content: center;
}
</style>
