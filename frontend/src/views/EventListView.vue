<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { fetchEvents } from "../services/apiService";
import EventList from "../components/EventList.vue";
import { Event, Filters, Pagination } from "../interfaces/EventInterfaces";

const events = ref<Event[]>([]);
const filteredEvents = ref<Event[]>([]);
const filters = ref<Filters>({
  title: "",
  startDate: new Date().toISOString().split("T")[0], // Today's date
  endDate: "",
  orderBy: {
    field: "startDate",
    direction: "asc",
  },
});

const pagination = ref<Pagination>({
  page: 1,
  limit: 20,
  total: 0,
});

const router = useRouter();

const loadEvents = async () => {
  try {
    const response = await fetchEvents({
      ...filters.value,
      top: pagination.value.limit,
      skip: pagination.value.limit * (pagination.value.page - 1),
    });
    filteredEvents.value = response.events || [];
    pagination.value.total = response.total || 0;
  } catch (error) {
    console.error("Failed to load events:", error);
    filteredEvents.value = [];
  }
};

const applyFilters = async () => {
  pagination.value.page = 1; // Reset to the first page when filters are applied
  await loadEvents();
};

const changePage = (newPage: number) => {
  pagination.value.page = newPage;
  loadEvents();
};

onMounted(loadEvents);
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
    <div class="pagination">
      <button
        :disabled="pagination.page === 1"
        @click="changePage(pagination.page - 1)"
      >
        Previous
      </button>
      <span>Page {{ pagination.page }} of {{ Math.ceil(pagination.total / pagination.limit) }}</span>
      <button
        :disabled="pagination.page === Math.ceil(pagination.total / pagination.limit)"
        @click="changePage(pagination.page + 1)"
      >
        Next
      </button>
    </div>
  </div>
</template>

<style scoped>

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

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 20px;
}
.pagination button {
  padding: 5px 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #f9f9f9;
  cursor: pointer;
}
.pagination button:disabled {
  background-color: #e0e0e0;
  cursor: not-allowed;
}
</style>
