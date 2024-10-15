<template>
  <div>
    <h1>Регистрация</h1>
    <form @submit.prevent="registerUser">
      <input type="text" v-model="username" placeholder="Имя пользователя" required />
      <input type="password" v-model="password" placeholder="Пароль" required />
      <button type="submit">Зарегистрироваться</button>
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
  async registerUser() {
    this.error = null;
    this.success = null;

    try {
      const response = await axios.post('http://vuelara/spa/api/api.php?action=register', {
        username: this.username,
        password: this.password,
      });

      // Проверьте, есть ли данные и сообщения в ответе
      if (response && response.data) {
        this.success = response.data.message || 'Регистрация прошла успешно'; // Убедитесь, что message существует
      } else {
        this.error = 'Неизвестная ошибка';
      }
    } catch (error) {
      // Обработка ошибок
      if (error.response) {
        // Проверяем наличие response и его поля data
        this.error = error.response.data ? error.response.data.error || 'Ошибка регистрации' : 'Неожиданная ошибка';
      } else {
        this.error = 'Ошибка сети или сервер недоступен';
      }
    }
  },
}
};
</script>