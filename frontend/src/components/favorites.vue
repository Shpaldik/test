<template>
    <div>
      <h2>Избранные маршруты</h2>
      <div class="cards" v-if="favoriteSchedules.length">
        <div v-for="schedule in favoriteSchedules" :key="schedule.id" class="place-card">
          <p><strong>ID:</strong> {{ schedule.id }}</p>
          <p><strong>Путь:</strong> {{ schedule.line }}</p>
          <p><strong>С какого города:</strong> {{ getPlaceNameById(schedule.from_place) }}</p>
          <p><strong>До какого города:</strong> {{ getPlaceNameById(schedule.to_place) }}</p>
          <p><strong>Время отправления:</strong> {{ schedule.departure_time }}</p>
          <p><strong>Время прибытия:</strong> {{ schedule.arrival_time }}</p>
          <p><strong>Расстояние:</strong> {{ schedule.distance }}</p>
          <p><strong>Скорость:</strong> {{ schedule.speed }}</p>
          <p><strong>Статус:</strong> {{ schedule.status }}</p>
        </div>
      </div>
      <div v-else>
        Нет избранных маршрутов.
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const favoriteSchedules = ref([]);
  
  // Загружаем избранные маршруты из localStorage при монтировании компонента
  onMounted(() => {
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    favoriteSchedules.value = favorites;
  });
  
  // Функция для получения названия места по ID (можно перенести в отдельный модуль)
  const getPlaceNameById = (id) => {
    // Предполагаем, что у тебя есть доступ к данным о местах
    // Это может быть какой-то локальный список или данные из API
    const places = JSON.parse(localStorage.getItem('places')) || [];
    const place = places.find(p => p.id === id);
    return place ? place.name : 'неизвестно';
  };
  </script>
  
  <style scoped>
  .cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 30px;
  }
  
  .place-card {
    border: 1px solid #ccc;
    padding: 12px;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  </style>
  