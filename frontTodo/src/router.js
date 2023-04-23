// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import TodoApp from "./components/TodoComponent.vue"
import Login from "@/components/LoginComponent.vue";

function isAuthenticated() {
  return !!localStorage.getItem('t');
}

const routes = [
  {
    path: '/todo',
    name: 'Todo',
    component: TodoApp,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) return next();
      next('/login');
    },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
