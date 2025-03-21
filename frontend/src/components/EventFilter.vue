<script setup lang="ts">
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  filters: Object
});

const emits = defineEmits(["applyFilters"]);
</script>

<template>
  <div class="filter-container">
    <form @submit.prevent="$emit('applyFilters')" class="filter-form">
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
.filter-actions {
  display: flex;
  justify-content: center;
}
</style>
