<template>
  <div>
    <h1>Оформленные заказы</h1>
    <div v-if="orders.length === 0">У вас нет оформленных заказов.</div>
    <div v-else>
      <ul>
        <li v-for="order in orders" :key="order.id">
          <strong>Заказ #{{ order.id }}</strong>
          <ul>
            <li v-for="item in order.items" :key="item.id">
              {{ item.name }} (Количество: {{ item.quantity }}) - {{ item.price }}₽
            </li>
          </ul>
          <p><strong>Итого: {{ calculateTotal(order.items) }}₽</strong></p>
        </li>
      </ul>
      <button @click="goBack">Назад</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      orders: [],
    };
  },

  created() {
    this.loadOrders();
  },

  methods: {
    loadOrders() {
      this.orders = JSON.parse(localStorage.getItem('orders')) || [];
    },

    calculateTotal(items) {
      return items.reduce((total, item) => total + item.price * item.quantity, 0);
    },

    goBack() {
      this.$router.push('/cart');
      this.$router.push('/cart'); // Переход на страницу корзины
    },
  },
};
</script>

<style scoped>
/* Добавьте стили для компонента */
</style>