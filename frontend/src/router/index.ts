import { createRouter, createWebHistory } from "vue-router";
import App from "../App.vue";
import EventDetailView from "../views/EventDetailView.vue";
import EventFormView from "../views/EventFormView.vue";

const routes = [
  {
    path: '/',
    name: 'Home',
    component: App,
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
