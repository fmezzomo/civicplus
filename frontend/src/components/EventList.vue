<template>
  <div>
    <ul v-if="events" class="event-list">
      <li v-for="event in events" :key="event.id" @click="viewDetails(event.id)" class="event-item">
        <h3>{{ event.title }}</h3>
        <p>{{ formatDate(event.startDate) }}<br>{{ formatDate(event.endDate) }}</p>
        <span class="hover-text">View Details</span>
      </li>
    </ul>
    <p v-else>Loading events...</p>
  </div>
</template>

<style scoped>
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
  position: relative;
}
.event-item:hover {
  background-color: #f5f5f5;
}
.hover-text {
  display: none;
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 5px;
  border-radius: 4px;
  font-size: 12px;
}
.event-item:hover .hover-text {
  display: block;
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
