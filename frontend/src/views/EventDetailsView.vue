<template>
  <div class="event-details">
    <button @click="goBack" class="back-btn">Back to Events</button>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <h1>{{ event?.title }}</h1>
      <p>{{ event?.description }}</p>
      <p>{{ formattedStartDate }} - {{ formattedEndDate }}</p>
    </div>
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
    const loading = ref(false);

    const formattedStartDate = computed(() => formatDate(event.value?.startDate));
    const formattedEndDate = computed(() => formatDate(event.value?.endDate));

    const loadEvent = async () => {
      loading.value = true;
      try {
        event.value = await fetchEventById(props.id);
      } catch (error) {
        console.error('Failed to load event:', error);
      } finally {
        loading.value = false;
      }
    };

    const goBack = () => {
      router.push('/');
    };

    onMounted(loadEvent);

    watch(() => props.id, loadEvent);

    return { event, goBack, formattedStartDate, formattedEndDate, loading };
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