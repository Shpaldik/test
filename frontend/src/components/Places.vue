<template>
  <input type="text" class="filters" placeholder="Найти по названию" v-model="search" />
  <input type="number" class="filters" placeholder="Минимальная скорость" v-model="minSpeed" />
  <input type="number" class="filters" placeholder="Максимальная скорость" v-model="maxSpeed" />
  <input type="time" class="filters" placeholder="Время открытия" v-model="openTime" />

  <button class="btn create" @click="startCreate" v-if="role === 'ADMIN'">Добавить место</button>

  <!-- Форма -->
  <div v-if="showForm" class="form">
    <h3>{{ editingPlace.id ? 'Редактировать' : 'Добавить' }} место</h3>
    <input v-model="editingPlace.name" placeholder="Название" />
    <input v-model="editingPlace.type" placeholder="Тип" />
    <input v-model="editingPlace.latitude" placeholder="Широта" />
    <input v-model="editingPlace.longitude" placeholder="Долгота" />
    <input v-model="editingPlace.x" placeholder="X" />
    <input v-model="editingPlace.y" placeholder="Y" />
    <input v-model="editingPlace.image_path" placeholder="Путь к картинке" />
    <input v-model="editingPlace.description" placeholder="Описание" />
    <input v-model="editingPlace.open_time" placeholder="Открытие" />
    <input v-model="editingPlace.close_time" placeholder="Закрытие" />
    <input v-model="editingPlace.speed" placeholder="Скорость" type="number" />


    <button class="btn save" @click="savePlace">Сохранить</button>
    <button class="btn cancel" @click="cancelEdit">Отмена</button>
  </div>

  <!-- Список мест -->
  <div class="cards" v-if="filteredPlaces.length">
    <div v-for="place in filteredPlaces" :key="place.id" class="place-card">
      <p><strong>ID:</strong> {{ place.id }}</p>
      <p><strong>NAME:</strong> {{ place.name }}</p>
      <p><strong>TYPE:</strong> {{ place.type }}</p>
      <p><strong>latitude:</strong> {{ place.latitude }}</p>
      <p><strong>longitude:</strong> {{ place.longitude }}</p>
      <p><strong>x:</strong> {{ place.x }}</p>
      <p><strong>y:</strong> {{ place.y }}</p>
      <p><strong>img_path:</strong> {{ place.image_path }}</p>
      <p><strong>description:</strong> {{ place.description }}</p>
      <p><strong>open_time:</strong> {{ place.open_time }}</p>
      <p><strong>close_time:</strong> {{ place.close_time }}</p>
      <p><strong>speed:</strong> {{ place.speed }}</p>

      <div class="card-actions">
        <button v-if="role === 'ADMIN'" @click="deletePlace(place.id)" class="btn delete">Удалить</button>
        <button v-if="role === 'ADMIN'" @click="startEdit(place)" class="btn edit">Редактировать</button>
      </div>
    </div>
  </div>

  <div v-else>
    Загрузка или ничего не найдено...
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const places = ref([])
const search = ref('')
const showForm = ref(false)
const token = localStorage.getItem('auth_token') || ''

const minSpeed = ref('')
const maxSpeed = ref('')
const openTime = ref('')

const role = ref(localStorage.getItem('role') || '');


const defaultPlace = () => ({
  id: '',
  name: '',
  type: '',
  latitude: '',
  longitude: '',
  x: '',
  y: '',
  image_path: '',
  description: '',
  open_time: '',
  close_time: '',
  speed: ''
})

const editingPlace = ref(defaultPlace())

const loadPlaces = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/v1/place', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    places.value = response.data
  } catch (error) {
    console.error('Ошибка при загрузке данных:', error)
  }
}

onMounted(loadPlaces)

const filteredPlaces = computed(() => {
  return places.value.filter(place => {
    const matchesName = place.name?.toLowerCase().includes(search.value.toLowerCase());

    const matchesSpeed = (!minSpeed.value || Number(place.speed) >= Number(minSpeed.value)) &&
                         (!maxSpeed.value || Number(place.speed) <= Number(maxSpeed.value));

    const matchesOpenTime = !openTime.value || (place.open_time && place.open_time >= openTime.value);

    return matchesName && matchesSpeed && matchesOpenTime;
  });
});


const deletePlace = async (id) => {
  if (!confirm('Вы точно хотите удалить это место?')) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/v1/place/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    await loadPlaces()
  } catch (error) {
    console.error('Ошибка при удалении места:', error.response?.data || error.message)
  }
}

const startEdit = (place) => {
  editingPlace.value = { ...place }
  showForm.value = true
}

const startCreate = () => {
  editingPlace.value = defaultPlace()
  showForm.value = true
}

const savePlace = async () => {
  try {
    if (!editingPlace.value.type) {
      alert("Поле 'Тип' обязательно!")
      return
    }

    if (editingPlace.value.id) {
      // Редактирование
      await axios.put(`http://127.0.0.1:8000/api/v1/place/${editingPlace.value.id}`, editingPlace.value, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
    } else {
      // Создание
      await axios.post(`http://127.0.0.1:8000/api/v1/place`, editingPlace.value, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
    }

    await loadPlaces()
    cancelEdit()
  } catch (error) {
    console.error("Ошибка сохранения", error.response?.data || error.message)
  }
}

const cancelEdit = () => {
  showForm.value = false
  editingPlace.value = defaultPlace()
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
.form input {
  display: block;
  margin: 4px 0;
  padding: 6px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>
