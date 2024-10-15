<template>
  <div id="app">
    <nav>
      <router-link to="/">Каталог</router-link>
      <router-link to="/register">Регистрация</router-link>
      <router-link to="/login">Вход</router-link>
      <router-link to="/cart">Корзина</router-link>
      <router-link to="/orders">Заказы</router-link>
      <button v-if="isUserLoggedIn" @click="logout">Выйти</button>
    </nav>
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      isUserLoggedIn: false,
    };
  },
  created() {
    // Проверяем, авторизован ли пользователь
    this.checkUserLoginStatus();
  },
  methods: {
    checkUserLoginStatus() {
      const userId = localStorage.getItem('user_id'); // Предполагаем, что ID пользователя хранится в localStorage
      this.isUserLoggedIn = !!userId; // Устанавливаем статус авторизации
    },
    logout() {
      // Удаляем информацию о пользователе из localStorage
      localStorage.removeItem('user_id');
      localStorage.removeItem('token'); // Если у вас есть токен аутентификации
      this.isUserLoggedIn = false; // Обновляем статус авторизации
      this.$router.push('/login'); // Перенаправляем на страницу входа
    }
  }
};
</script>

<style>
nav {
  margin-bottom: 20px;
}

nav a {
  margin-right: 15px;
}
</style>