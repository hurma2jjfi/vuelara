<template>
  <div>
    <h1>Корзина</h1>
    <div v-if="cartItems.length === 0">Корзина пуста.</div>
    <div v-else>
      <ul>
        <li v-for="item in groupedCartItems" :key="item.id">
          {{ item.name }} - {{ item.price }}₽ (Количество: {{ item.quantity }})
          <button @click="increaseQuantity(item.id)">+</button>
          <button @click="decreaseQuantity(item.id)">-</button>
          <button @click="removeFromCart(item.id)">Удалить</button>
        </li>
      </ul>
      <button @click="checkout">Оформить заказ</button>
      <button @click="goBack">Назад</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cartItems: [],
    };
  },

  created() {
    this.loadCart();
  },

  computed: {
    groupedCartItems() {
      const grouped = {};
      this.cartItems.forEach(item => {
        if (!grouped[item.id]) {
          grouped[item.id] = { ...item, quantity: 0 };
        }
        grouped[item.id].quantity += 1;
      });
      return Object.values(grouped);
    },
  },

  methods: {
    loadCart() {
      this.cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    },

    increaseQuantity(productId) {
      const item = this.cartItems.find(item => item.id === productId);
      if (item) {
        this.cartItems.push(item); // Увеличиваем количество
        localStorage.setItem('cart', JSON.stringify(this.cartItems));
      }
    },

    decreaseQuantity(productId) {
      const index = this.cartItems.findIndex(item => item.id === productId);
      if (index > -1) {
        this.cartItems.splice(index, 1); // Уменьшаем количество
        localStorage.setItem('cart', JSON.stringify(this.cartItems));
      }
    },

    removeFromCart(productId) {
      this.cartItems = this.cartItems.filter(item => item.id !== productId);
      localStorage.setItem('cart', JSON.stringify(this.cartItems));
    },

    checkout() {
      if (this.cartItems.length > 0) {
        const orderId = Date.now();
        const order = {
          id: orderId,
          items: this.groupedCartItems,
        };

        // Сохраняем заказ в localStorage
        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        orders.push(order);
        localStorage.setItem('orders', JSON.stringify(orders));

        alert('Заказ оформлен! ID заказа: ' + orderId);
        localStorage.removeItem('cart'); // Очищаем корзину после оформления
        this.cartItems = [];
      }
    },

    goBack() {
      this.$router.push('/'); // Переход на домашний экран
    },
  },
};
</script>

<style scoped>
/* Добавьте стили для компонента */
</style>