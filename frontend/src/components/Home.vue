<template>
    <div class="container">
      <div v-if="!isLoggedIn" class="login-box">
        <h2>Вход</h2>
        <form @submit.prevent="login">
          <input type="text" v-model="username" placeholder="Username или Email" required />
          <input type="password" v-model="password" placeholder="Пароль" required />
          <button type="submit">Войти</button>
        </form>
      </div>
      <div v-else class="authenticated-container">
        <header class="header">
          <h2>Вы авторизованы!</h2>
          <button @click="logout" class="logout-btn">Выйти</button>
          <router-link to="/schedule">Посмотреть графики</router-link>
          <router-link to="/places">Посмотреть места</router-link>
        </header>
        <p class="token-text">Токен: <span>{{ token }}</span></p>
      </div>
    </div>
</template>
  
  <script setup>
import { ref, computed } from 'vue';
  import axios from 'axios';
  
  const username = ref('');
  const password = ref('');
  const token = ref(localStorage.getItem('auth_token') || '');
  const currentRole = computed(() => localStorage.getItem('role') || 'user');
  
  
  
  
  
  const isLoggedIn = computed(() => token.value !== '');
  
  const login = async () => {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/v1/auth/login', {
        username: username.value,
        password: password.value
      });
      token.value = response.data.token;
      localStorage.setItem('auth_token', token.value);
      localStorage.setItem('role' , response.data.role)
      alert('Вход успешен!');
    } catch (error) {
      alert('Ошибка входа!');
      console.error(error);
    }
  };
  
  const logout = async () => {
    try {
      await axios.get('http://127.0.0.1:8000/api/v1/auth/logout', {
        headers: {
          Authorization: 'Bearer ' + token.value
        }
      });
      localStorage.removeItem('auth_token');
      token.value = '';
      alert('Выход выполнен успешно!');
    } catch (error) {
      alert('Ошибка выхода!');
      console.error(error);
    }
  };
  </script>
  
  <style>
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f3f4f6;
  }
  
  .login-box {
    background-color: white;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    width: 400px;
    text-align: center;
  }
  
  .login-box h2 {
    font-size: 24px;
    margin-bottom: 16px;
  }
  
  input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    outline: none;
  }
  
  input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 4px #3b82f6;
  }
  
  button {
    width: 100%;
    background-color: #3b82f6;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
  }
  
  button:hover {
    background-color: #2563eb;
  }
  
  .authenticated-container {
    width: 600px;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 10px;
    margin-bottom: 20px;
  }
  
  .logout-btn {
    background-color: #ef4444;
    width: 200px;
  }
  
  .logout-btn:hover {
    background-color: #dc2626;
  }
  
  .token-text {
    color: #6b7280;
  }
  
  .token-text span {
    font-family: monospace;
    color: #374151;
  }
  </style>
  