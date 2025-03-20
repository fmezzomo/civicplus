<template>
  <div class="event-details">
    <h1>{{ event?.title }}</h1>
    <p>{{ event?.description }}</p>
    <p>{{ event?.startDate }} - {{ event?.endDate }}</p>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch } from 'vue';
import { fetchEventById } from '../services/apiService';

export default defineComponent({
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const event = ref(null);

    const loadEvent = async () => {
      console.log(`Fetching event with ID: ${props.id}`);
      try {
        event.value = await fetchEventById(props.id);
        console.log('Event loaded:', event.value);
      } catch (error) {
        console.error('Failed to load event:', error);
      }
    };

    onMounted(loadEvent);

    watch(() => props.id, loadEvent);

    return { event };
  },
});
</script>