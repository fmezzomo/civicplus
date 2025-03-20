<template>
  <ul v-if="events" class="event-list">
    <li v-for="event in events" :key="event.id" @click="viewDetails(event.id)">
      <h3>{{ event.title }}</h3>
      <p>{{ formatDate(event.startDate) }} - {{ formatDate(event.endDate) }}</p>
    </li>
  </ul>
  <p v-else>Loading events...</p>
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
.event-list {
  list-style: none;
  padding: 0;
}
.event-list li {
  padding: 10px;
  margin: 5px 0;
  border: 1px solid #ddd;
  cursor: pointer;
}
.event-list li:hover {
  background-color: #f5f5f5;
}
</style>

<script lang="ts">
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
import { formatDate } from '../utils/dateUtils';

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

    return { viewDetails, formatDate };
  }
});
</script>
