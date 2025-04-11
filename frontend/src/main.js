import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import { createMemoryHistory, createRouter } from 'vue-router'
import Schedule from './components/Schedule.vue';
import Places from './components/Places.vue';
import Home from './components/Home.vue'
import favorites from './components/favorites.vue';

const routes = [
  {
    path: '/',
    redirect: '/home',
  },
  {
    path: '/home',
    component: Home,
    name: 'Home'
  },
    {
      path: '/schedule',
      component: Schedule,
      name: 'schedule',
    },
    {
        path: '/places',
        component: Places,
        name: 'places',
    },
    {
      path: '/favorites',
      component: favorites,
      name: 'favorites'
    }
  ];

const router = createRouter({
    history: createMemoryHistory(),
    routes,
})
createApp(App).use(router).mount('#app')
