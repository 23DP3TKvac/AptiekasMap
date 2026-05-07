<template>
  <v-container class="py-12" v-if="user">
    <v-row justify="center">
      <v-col cols="12" md="8">

        <!-- Profile card -->
        <v-card rounded="xl" elevation="3" class="mb-6">
          <v-card-item>
            <template #prepend>
              <v-avatar color="primary" size="64">
                <span class="text-h5 text-white font-weight-bold">
                  {{ user.name[0].toUpperCase() }}
                </span>
              </v-avatar>
            </template>
            <v-card-title class="text-h5">{{ user.name }}</v-card-title>
            <v-card-subtitle>{{ user.email }}</v-card-subtitle>
            <template #append>
              <v-chip :color="roleColor" variant="tonal" size="small">
                {{ roleLabel }}
              </v-chip>
            </template>
          </v-card-item>
          <v-card-actions class="px-6 pb-4">
            <v-btn color="error" variant="outlined" rounded="lg" @click="logout">
              <v-icon start>mdi-logout</v-icon>
              Iziet
            </v-btn>
          </v-card-actions>
        </v-card>

        <!-- Search history -->
        <v-card rounded="xl" elevation="3" class="mb-6">
          <v-card-title class="pa-6 pb-2">
            <v-icon color="primary" class="mr-2">mdi-history</v-icon>
            Meklēšanas vēsture
          </v-card-title>
          <v-card-text>
            <div v-if="historyLoading" class="text-center py-4">
              <v-progress-circular color="primary" indeterminate />
            </div>
            <v-list v-else-if="history.length > 0">
              <v-list-item v-for="h in history" :key="h.id"
                           :subtitle="formatDate(h.created_at)"
                           :title="h.medicine_name"
                           prepend-icon="mdi-magnify">
                <template #append>
                  <v-btn size="small" variant="tonal" color="primary"
                         @click="searchMedicine(h.medicine_name)">
                    Meklēt vēlreiz
                  </v-btn>
                </template>
              </v-list-item>
            </v-list>
            <div v-else class="text-center py-6 text-medium-emphasis">
              <v-icon size="48">mdi-history</v-icon>
              <p class="mt-2">Meklēšanas vēsture ir tukša</p>
            </div>
          </v-card-text>
        </v-card>

        <!-- Notifications -->
        <v-card rounded="xl" elevation="3">
          <v-card-title class="pa-6 pb-2">
            <v-icon color="primary" class="mr-2">mdi-bell</v-icon>
            Paziņojumi
            <v-chip v-if="unreadCount > 0" color="error" size="x-small" class="ml-2">
              {{ unreadCount }}
            </v-chip>
          </v-card-title>
          <v-card-text>
            <div v-if="notifLoading" class="text-center py-4">
              <v-progress-circular color="primary" indeterminate />
            </div>
            <v-list v-else-if="notifications.length > 0">
              <v-list-item v-for="n in notifications" :key="n.id"
                           :title="n.message"
                           :subtitle="formatDate(n.created_at)"
                           prepend-icon="mdi-bell-outline"
                           :class="n.is_read === 'not_read' ? 'bg-primary-lighten' : ''">
                <template #append>
                  <v-btn v-if="n.is_read === 'not_read'" size="small"
                         variant="text" color="primary"
                         @click="markRead(n.id)">
                    Atzīmēt kā lasītu
                  </v-btn>
                  <v-icon v-else color="success">mdi-check</v-icon>
                </template>
              </v-list-item>
            </v-list>
            <div v-else class="text-center py-6 text-medium-emphasis">
              <v-icon size="48">mdi-bell-off</v-icon>
              <p class="mt-2">Paziņojumu nav</p>
            </div>
          </v-card-text>
        </v-card>

      </v-col>
    </v-row>
  </v-container>

  <!-- Not logged in -->
  <v-container v-else class="py-12 text-center">
    <v-icon size="80" color="medium-emphasis">mdi-account-off</v-icon>
    <h2 class="text-h5 mt-4 mb-2">Tu neesi pieslēdzies</h2>
    <p class="text-medium-emphasis mb-6">Lūdzu pieslēdzies vai reģistrējies</p>
    <v-btn color="primary" size="large" rounded="lg" to="/">
      Doties uz sākumu
    </v-btn>
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router  = useRouter()
const user    = ref(null)
const history = ref([])
const notifications = ref([])
const historyLoading = ref(true)
const notifLoading   = ref(true)

const roleLabel = computed(() => {
  const roles = { admin: 'Administrators', pharmacy_rep: 'Aptieka', user: 'Lietotājs' }
  return roles[user.value?.role] || 'Lietotājs'
})
const roleColor = computed(() => {
  const colors = { admin: 'error', pharmacy_rep: 'warning', user: 'success' }
  return colors[user.value?.role] || 'success'
})
const unreadCount = computed(() =>
  notifications.value.filter(n => n.is_read === 'not_read').length
)

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleString('lv-LV')
}

function searchMedicine(name) {
  router.push({ path: '/', query: { search: name } })
}

async function logout() {
  try {
    await axios.post('/api/auth/logout')
  } catch {}
  localStorage.removeItem('token')
  delete axios.defaults.headers.common['Authorization']
  router.push('/')
}

async function markRead(id) {
  try {
    await axios.patch(`/api/user/notifications/${id}/read`)
    const n = notifications.value.find(n => n.id === id)
    if (n) n.is_read = 'read'
  } catch {}
}

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const { data } = await axios.get('/api/user')
    user.value = data
  } catch {
    localStorage.removeItem('token')
    return
  }

  try {
    const { data } = await axios.get('/api/user/history')
    history.value = data
  } catch {} finally {
    historyLoading.value = false
  }

  try {
    const { data } = await axios.get('/api/user/notifications')
    notifications.value = data
  } catch {} finally {
    notifLoading.value = false
  }
})
</script>
