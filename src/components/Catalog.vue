<template>
    <div>
      <h1>Каталог товаров</h1>
      <div v-if="!user">
        <router-link to="/register">Регистрация</router-link>
        <router-link to="/login">Вход</router-link>
      </div>
      <div v-else>
        <button @click="logout">Выход</button>
        <router-link to="/orders">Мои заказы</router-link>
      </div>
      
      <div v-for="product in products" :key="product.id">
        <h2>{{ product.name }}</h2>
        <p>{{ product.description }}</p>
        <span>{{ product.price }}₽</span>
        <button v-if="user && user.role === 'client'" @click="addToCart(product.id)">Добавить в корзину</button>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        products: [],
        user: null,
      };
    },
    
    created() {
      this.fetchProducts();
      this.user = JSON.parse(localStorage.getItem('user')); // Получаем пользователя из локального хранилища
    },
    
    methods: {
      async fetchProducts() {
        const response = await axios.get(`${process.env.VUE_APP_API_URL}/products`);
        this.products = response.data;
      },
      addToCart(productId) {
        // Логика добавления товара в корзину
        console.log(`Товар ${productId} добавлен в корзину.`);
      },
      logout() {
        localStorage.removeItem('user'); // Выход из системы
        this.user = null;
      }
    },
  };
  </script>
  
  <style scoped>
  /* Добавьте стили для компонента */
  </style>