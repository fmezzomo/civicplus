import { createRouter, createWebHistory } from "vue-router";
import EventListView from "../views/EventListView.vue";
import EventDetailView from "../views/EventDetailsView.vue";
import EventFormView from "../views/EventFormView.vue";

const routes = [
  {
    path: '/',
    name: 'EventList',
    component: EventListView,
  },
  {
    path: '/event/:id',
    name: 'EventDetails',
    component: EventDetailView,
    props: true,
  },
  {
    path: '/event/new',
    name: 'EventForm',
    component: EventFormView,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
