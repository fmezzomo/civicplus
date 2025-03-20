<template>
  <div>
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
    <ul v-if="events" class="event-list">
      <li v-for="event in events" :key="event.id" @click="viewDetails(event.id)" class="event-item">
        <h3>{{ event.title }}</h3>
        <p>{{ formatDate(event.startDate) }}<br>{{ formatDate(event.endDate) }}</p>
      </li>
    </ul>
    <p v-else>Loading events...</p>
  </div>
</template>

<style scoped>
.add-btn {
  margin-top: 10px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}
.event-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  list-style: none;
  padding: 0;
  justify-content: center;
}
.event-item {
  flex: 0 0 auto;
  width: 200px;
  box-sizing: border-box;
  padding: 10px;
  margin: 5px 0;
  border: 1px solid #ddd;
  cursor: pointer;
}
.event-item:hover {
  background-color: #f5f5f5;
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

<script lang="ts">
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
import { formatDate } from '../utils/dateUtils';
import { ref } from "vue";

export default defineComponent({
  props: {
    events: {
      type: Array,
      required: true
    }
  },
  setup() {
    const router = useRouter();

    const viewDetails = (eventId: number) => {
      router.push({ name: "EventDetails", params: { id: eventId } });
    };

    const today = new Date().toISOString().split("T")[0];

    const filters = ref({
      title: "",
      startDate: today,
      endDate: "",
      orderBy: {
        field: "startDate",
        direction: "asc"
      }
    });

    const applyFilters = () => {
      console.log("Applying filters:", filters.value);
    };

    return { viewDetails, formatDate, filters, applyFilters };
  }
});
</script>
