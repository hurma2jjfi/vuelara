<template>
  <div>
    <h1>Вход</h1>
    <form @submit.prevent="login">
      <input type="text" v-model="username" placeholder="Имя пользователя" required />
      <input type="password" v-model="password" placeholder="Пароль" required />
      <button type="submit">Войти</button>
    </form>
    <p v-if="error">{{ error }}</p>
    <p v-if="success">{{ success }}</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      username: '',
      password: '',
      error: null,
      success: null,
    };
  },
  methods: {
    async login() {
    this.error = null;
    this.success = null;

    try {
        const response = await axios.post('http://vuelara/spa/api/api.php?action=login', {
            username: this.username,
            password: this.password,
        });

        // Проверка существования пользователя
        if (response.data && response.data.userExists) {
            // Сохраняем пользователя в локальное хранилище
            localStorage.setItem('user', JSON.stringify(response.data.user));

            // Успешный вход
            this.success = 'Успешный вход'; 
            this.$router.push('/'); // Перенаправляем на главную страницу
        } else {
            this.error = response.data.error || 'Ошибка входа'; // Сообщение об ошибке
        }
    } catch (error) {
        // Обработка ошибок
        if (error.response) {
            this.error = error.response.data ? error.response.data.message || 'Ошибка входа' : 'Неожиданная ошибка';
        } else {
            this.error = 'Ошибка сети или сервер недоступен';
        }
    }
}
  },
};
</script>

<style scoped>
/* Добавьте стили для компонента */
</style>






