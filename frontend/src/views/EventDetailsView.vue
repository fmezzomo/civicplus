<template>
  <div class="event-details">
    <button @click="goBack" class="back-btn">Back to Events</button>
    <h1>{{ event?.title }}</h1>
    <p>{{ event?.description }}</p>
    <p>{{ formattedStartDate }} - {{ formattedEndDate }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import { fetchEventById } from '../services/apiService';
import { formatDate } from '../utils/dateUtils';

export default defineComponent({
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const router = useRouter();
    const event = ref(null);

    const formattedStartDate = computed(() => formatDate(event.value?.startDate));
    const formattedEndDate = computed(() => formatDate(event.value?.endDate));

    const loadEvent = async () => {
      console.log(`Fetching event with ID: ${props.id}`);
      try {
        event.value = await fetchEventById(props.id);
        console.log('Event loaded:', event.value);
      } catch (error) {
        console.error('Failed to load event:', error);
      }
    };

    const goBack = () => {
      router.push('/');
    };

    onMounted(loadEvent);

    watch(() => props.id, loadEvent);

    return { event, goBack, formattedStartDate, formattedEndDate };
  },
});
</script>

<style scoped>
.back-btn {
  margin-bottom: 15px;
  padding: 10px;
  background-color: #6c757d;
  color: white;
  border: none;
  cursor: pointer;
}
.back-btn:hover {
  background-color: #5a6268;
}
</style>