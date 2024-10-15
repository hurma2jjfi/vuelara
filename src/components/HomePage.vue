<template>
  <div>
    <h1>Добро пожаловать в наш магазин!</h1>
    <div class="product-list">
      <div v-for="product in products" :key="product.id" class="product-item">
        <h2>{{ product.name }}</h2>
        <p>Цена: {{ product.price }}₽</p>
        <button @click="addToCart(product)">Добавить в корзину</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      products: [],
    };
  },
  
  created() {
    this.fetchProducts();
  },

  methods: {
    async fetchProducts() {
      try {
        const response = await axios.get('http://vuelara/spa/api/api.php?action=getProducts'); // Обновленный URL
        this.products = response.data;
      } catch (error) {
        console.error('Ошибка при загрузке продуктов:', error);
      }
    },

    addToCart(product) {
      let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
      const existingProduct = cartItems.find(item => item.id === product.id);

      if (existingProduct) {
        existingProduct.quantity += 1; // Увеличиваем количество, если товар уже в корзине
      } else {
        cartItems.push({ ...product, quantity: 1 }); // Добавляем новый товар
      }

      localStorage.setItem('cart', JSON.stringify(cartItems));
      alert('Товар добавлен в корзину!');
    },
  },
};
</script>

<style scoped>
.product-list {
  display: flex;
  flex-wrap: wrap;
}

.product-item {
  border: 1px solid #ccc;
  margin: 10px;
  padding: 10px;
  width: 200px;
}
</style>
