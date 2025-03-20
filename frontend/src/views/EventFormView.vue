<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { createEvent } from "../services/apiService";

const title = ref("");
const description = ref("");
const start_date = ref("");
const end_date = ref("");
const router = useRouter();

const successMessage = ref("");
const errorMessage = ref("");

const saveEvent = async () => {
  try {
    await createEvent({
      title: title.value,
      description: description.value,
      start_date: start_date.value,
      end_date: end_date.value,
    });

    successMessage.value = "Event created successfully!";
    errorMessage.value = "";
    setTimeout(() => router.push("/"), 4000);
  } catch (error) {
    errorMessage.value = "Failed to create event. Please try again.";
    successMessage.value = "";
    console.error("Failed to create event:", error);
  }
};
</script>

<template>
  <div class="container">
    <h1>Add Event</h1>
    <form @submit.prevent="saveEvent">
      <label>Title:</label>
      <input v-model="title" required />

      <label>Description:</label>
      <textarea v-model="description" required></textarea>

      <label>Start Date and Time:</label>
      <input type="datetime-local" v-model="start_date" required />

      <label>End Date and Time:</label>
      <input type="datetime-local" v-model="end_date" required />

      <button type="submit">Save</button>
      <button type="button" @click="router.push('/')" class="cancel-btn">Cancel</button>
    </form>
    <p v-if="successMessage" class="success">{{ successMessage }}</p>
    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
  </div>
</template>

<style scoped>
form {
  display: flex;
  flex-direction: column;
}
input, textarea {
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ddd;
}
button {
  padding: 10px;
  margin-top: 10px;
  background-color: #28a745;
  color: white;
  border: none;
  cursor: pointer;
}
.cancel-btn {
  background-color: #dc3545;
}
.success {
  color: #28a745;
  margin-bottom: 10px;
}
.error {
  color: #dc3545;
  margin-bottom: 10px;
}
</style>
