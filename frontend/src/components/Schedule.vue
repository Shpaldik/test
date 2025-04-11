<template>
  <input type="text" class="filters" placeholder="Найти по названию" v-model="search" />

  <button class="btn create" @click="startCreate" v-if="role === 'ADMIN'">Добавить место</button>

  <!-- Кнопка для перехода в избранные -->
<button @click="goToFavorites" class="btn">Перейти в Избранное</button>


  <!-- Форма -->
  <div v-if="showForm" class="form">
    <h3>{{ editingSchedule.id ? 'Редактировать' : 'Добавить' }} место</h3>
    
    <input v-model="editingSchedule.line" placeholder="Путь" />
    <input v-model="editingSchedule.from_place" placeholder="С какого города" />
    <input v-model="editingSchedule.to_place" placeholder="До какого города" />
    <input v-model="editingSchedule.departure_time" placeholder="Время отправления" />
    <input v-model="editingSchedule.arrival_time" placeholder="Время прибытия" />
    <input v-model="editingSchedule.distance" placeholder="Расстояние" />
    <input v-model="editingSchedule.speed" placeholder="Скорость" />
    <input v-model="editingSchedule.status" placeholder="Статус" />
    
    <button class="btn save" @click="savePlace">Сохранить</button>
    <button class="btn cancel" @click="cancelEdit">Отмена</button>
  </div>

  <!-- Список мест -->
  <div class="cards" v-if="filteredSchedule.length">
    <div v-for="schedule in filteredSchedule" :key="schedule.id" class="place-card">
      <p><strong>ID:</strong> {{ schedule.id }}</p>
      <p><strong>Путь:</strong> {{ schedule.line }}</p>
      <p><strong>С какого города:</strong> {{ getPlaceNameById(schedule.from_place) }}</p>
      <p><strong>До какого города:</strong> {{ getPlaceNameById(schedule.to_place) }}</p>
      <p><strong>Время отправления:</strong> {{ schedule.departure_time }}</p>
      <p><strong>Время прибытия:</strong> {{ schedule.arrival_time }}</p>
      <p><strong>Расстояние:</strong> {{ schedule.distance }}</p>
      <p><strong>Скорость:</strong> {{ schedule.speed }}</p>
      <p><strong>Статус:</strong> {{ schedule.status }}</p>

      <div class="card-actions">
        <button v-if="role === 'ADMIN'" @click="deletePlace(schedule.id)" class="btn delete">Удалить</button>
        <button v-if="role === 'ADMIN'" @click="startEdit(schedule)" class="btn edit">Редактировать</button>
      </div>
      <!-- Внутри цикла, где отображаются маршруты -->
<button  @click="addToFavorites(schedule)" class="btn favorite">Добавить в избранное</button>

    </div>
  </div>

  <div v-else>
    Загрузка или ничего не найдено...
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const schedules = ref([])
const places = ref([]) // Для хранения списка мест
const search = ref('')
const showForm = ref(false)
const token = localStorage.getItem('auth_token') || ''

const role = ref(localStorage.getItem('role') || '')

const defaultSchedule = () => ({
  id: '',
  line: '',
  from_place: '',
  to_place: '',
  departure_time: '',
  arrival_time: '',
  distance: '',
  speed: '',
  status: '',
})



const editingSchedule = ref(defaultSchedule())


const router = useRouter()
const goToFavorites = () => {
 router.push('/favorites')
}
// Функция для добавления маршрута в избранное
const addToFavorites = (schedule) => {
  let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

  // Проверяем, если маршрут уже есть в избранном
  if (!favorites.some(fav => fav.id === schedule.id)) {
    favorites.push(schedule);  // Добавляем в избранное
    localStorage.setItem('favorites', JSON.stringify(favorites));  // Сохраняем в localStorage
    alert('Маршрут добавлен в избранное!');
  } else {
    alert('Этот маршрут уже в избранном');
  }
};

// Загружаем графики
const loadSchedule = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/schedule', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    schedules.value = response.data
  } catch (error) {
    console.error('Ошибка при загрузке данных:', error)
  }
}

// Загружаем города
const loadPlaces = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/place', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    places.value = response.data
  } catch (error) {
    console.error('Ошибка при загрузке мест:', error)
  }
}

// Функция для получения названия места по ID
const getPlaceNameById = (id) => {
  const place = places.value.find(p => p.id === id)
  return place ? place.name : 'неизвестно'
}

// Фильтрация графиков по названиям городов
const filteredSchedule = computed(() => {
  return schedules.value.filter(schedule => {
    const from = getPlaceNameById(schedule.from_place).toLowerCase()
    const to = getPlaceNameById(schedule.to_place).toLowerCase()
    return from.includes(search.value.toLowerCase()) || to.includes(search.value.toLowerCase())
  })
})

onMounted(async () => {
  await Promise.all([loadPlaces(), loadSchedule()])
})

// Удаление графика
const deletePlace = async (id) => {
  if (!confirm('Вы точно хотите удалить это место?')) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/v1/schedule/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    await loadSchedule()
  } catch (error) {
    console.error('Ошибка при удалении графика:', error.response?.data || error.message)
  }
}

// Начать редактирование
const startEdit = (schedule) => {
  editingSchedule.value = { ...schedule }
  showForm.value = true
}

// Начать создание
const startCreate = () => {
  editingSchedule.value = defaultSchedule()
  showForm.value = true
}

// Сохранить изменения или создать новый график
const savePlace = async () => {
  try {
    if (!editingSchedule.value.line) {
      alert("Поле 'Путь' обязательно!")
      return
    }

    if (editingSchedule.value.id) {
      // Редактирование
      await axios.put(`http://127.0.0.1:8000/api/v1/schedule/${editingSchedule.value.id}`, editingSchedule.value, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
    } else {
      // Создание
      await axios.post('http://127.0.0.1:8000/api/v1/schedule', editingSchedule.value, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
    }

    await loadSchedule()
    cancelEdit()
  } catch (error) {
    console.error("Ошибка сохранения", error.response?.data || error.message)
  }
}

// Отмена редактирования
const cancelEdit = () => {
  showForm.value = false
  editingSchedule.value = defaultSchedule()
}
</script>

<style scoped>
.filters {
  padding: 10px;
  margin-top: 10px;
  margin-bottom: 10px;
  width: 100%;
}

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

.card-actions {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}

.btn {
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.delete {
  background-color: #e53e3e;
  color: white;
}

.edit {
  background-color: #3182ce;
  color: white;
}

.create {
  background-color: #38a169;
  color: white;
  margin-bottom: 10px;
}

.save {
  background-color: #38a169;
  color: white;
  margin-top: 10px;
}

.cancel {
  background-color: gray;
  color: white;
  margin-left: 10px;
}

.form input,
.form select {
  display: block;
  margin: 4px 0;
  padding: 6px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>
