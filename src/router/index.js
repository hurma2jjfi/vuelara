import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/HomePage.vue'; // Изменено
import UserRegister from '../components/UserRegister.vue'; // Изменено
import UserLogin from '../components/UserLogin.vue'; // Изменено
import ShoppingCart from '../components/ShoppingCart.vue'; // Изменено
import ShoppingOrders from '@/components/ShoppingOrders.vue';

const routes = [
  {
    path: '/',
    name: 'HomePage',
    component: HomePage,
  },
  {
    path: '/register',
    name: 'UserRegister',
    component: UserRegister,
  },
  {
    path: '/login',
    name: 'UserLogin',
    component: UserLogin,
  },
  {
    path: '/cart',
    name: 'ShoppingCart',
    component: ShoppingCart,
  },
  {
    path: '/orders',
    name: 'ShoppingOrders',
    component: ShoppingOrders,
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;