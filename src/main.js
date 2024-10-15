import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Убедитесь, что этот путь правильный


const app = createApp(App);

// Подключите маршрутизатор и Vuex, если используете
app.use(router);


app.mount('#app');