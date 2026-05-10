<template>
  <v-container class="py-12">

    <!-- Not admin -->
    <div v-if="!isAdmin" class="text-center py-12">
      <v-icon size="80" color="error">mdi-shield-off</v-icon>
      <h2 class="text-h5 mt-4 mb-2">Piekļuve liegta</h2>
      <p class="text-medium-emphasis">Tikai administratori var piekļūt šai lapai.</p>
      <v-btn color="primary" class="mt-4" to="/">Uz sākumu</v-btn>
    </div>

    <div v-else>
      <div class="d-flex align-center justify-space-between mb-6">
        <h2 class="text-h4 font-weight-bold" style="font-family:'Sora',sans-serif">
          <v-icon color="primary" class="mr-2">mdi-shield-crown</v-icon>
          Administrācijas panelis
        </h2>
        <v-btn color="primary" rounded="lg" prepend-icon="mdi-plus" @click="openCreate">
          Pievienot zāles
        </v-btn>
      </div>

      <!-- Stats -->
      <v-row class="mb-6">
        <v-col cols="12" sm="4">
          <v-card rounded="xl" color="primary" variant="tonal" class="pa-4 text-center">
            <div class="text-h4 font-weight-bold text-primary">{{ medicines.length }}</div>
            <div class="text-body-2 text-medium-emphasis">Medikamenti</div>
          </v-card>
        </v-col>
        <v-col cols="12" sm="4">
          <v-card rounded="xl" color="success" variant="tonal" class="pa-4 text-center">
            <div class="text-h4 font-weight-bold text-success">{{ availableCount }}</div>
            <div class="text-body-2 text-medium-emphasis">Pieejami</div>
          </v-card>
        </v-col>
        <v-col cols="12" sm="4">
          <v-card rounded="xl" color="error" variant="tonal" class="pa-4 text-center">
            <div class="text-h4 font-weight-bold text-error">{{ unavailableCount }}</div>
            <div class="text-body-2 text-medium-emphasis">Nav pieejami</div>
          </v-card>
        </v-col>
      </v-row>

      <!-- Search -->
      <v-text-field v-model="search" label="Meklēt zāles..." prepend-inner-icon="mdi-magnify"
        variant="outlined" density="compact" clearable class="mb-4" hide-details />

      <!-- Table -->
      <v-card rounded="xl" elevation="2">
        <v-data-table :headers="headers" :items="filteredMedicines" :loading="loading"
          item-value="id" class="rounded-xl">

          <template #item.status="{ item }">
            <v-chip :color="item.status === 'available' ? 'success' : 'error'" size="small" variant="tonal">
              {{ item.status === 'available' ? 'Pieejams' : 'Nav pieejams' }}
            </v-chip>
          </template>

          <template #item.minPrice="{ item }">
            <span class="text-primary font-weight-bold">€{{ item.minPrice }}</span>
          </template>

          <template #item.actions="{ item }">
            <v-btn icon="mdi-pencil" size="small" variant="text" color="warning"
              class="mr-1" @click="openEdit(item)" />
            <v-btn icon="mdi-delete" size="small" variant="text" color="error"
              @click="confirmDelete(item)" />
          </template>

        </v-data-table>
      </v-card>
    </div>

    <!-- CREATE/EDIT DIALOG -->
    <v-dialog v-model="formDialog" max-width="560">
      <v-card rounded="xl" class="pa-2">
        <v-card-title class="pt-4 px-6">
          {{ editMode ? 'Labot zāles' : 'Pievienot jaunas zāles' }}
        </v-card-title>
        <v-card-text class="px-6">
          <v-form ref="formRef" @submit.prevent="submitForm">
            <v-text-field v-model="form.name" label="Nosaukums *" variant="outlined" class="mb-3"
              :rules="[v => !!v || 'Obligāts lauks']" />
            <v-text-field v-model="form.active_substance" label="Aktīvā viela *" variant="outlined" class="mb-3"
              :rules="[v => !!v || 'Obligāts lauks']" />
            <v-row>
              <v-col cols="6">
                <v-select v-model="form.form" :items="['Tabletes','Kapsulas','Šķidrums','Ziede','Injekcija']"
                  label="Forma *" variant="outlined" :rules="[v => !!v || 'Obligāts lauks']" />
              </v-col>
              <v-col cols="6">
                <v-text-field v-model="form.dose" label="Deva *" variant="outlined"
                  :rules="[v => !!v || 'Obligāts lauks']" />
              </v-col>
            </v-row>
            <v-text-field v-model="form.manufacturer" label="Ražotājs *" variant="outlined" class="mb-3"
              :rules="[v => !!v || 'Obligāts lauks']" />
            <v-textarea v-model="form.description" label="Apraksts" variant="outlined" rows="3" />
            <v-alert v-if="formError" type="error" class="mt-3" density="compact">{{ formError }}</v-alert>
          </v-form>
        </v-card-text>
        <v-card-actions class="px-6 pb-4">
          <v-spacer />
          <v-btn variant="text" @click="formDialog = false">Atcelt</v-btn>
          <v-btn color="primary" variant="flat" rounded="lg" :loading="formLoading" @click="submitForm">
            {{ editMode ? 'Saglabāt' : 'Pievienot' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- DELETE CONFIRM -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card rounded="xl" class="pa-2">
        <v-card-title class="pt-4 px-6">Dzēst zāles?</v-card-title>
        <v-card-text class="px-6">
          Vai tiešām vēlies dzēst <strong>{{ deleteTarget?.name }}</strong>? Šo darbību nevar atsaukt.
        </v-card-text>
        <v-card-actions class="px-6 pb-4">
          <v-spacer />
          <v-btn variant="text" @click="deleteDialog = false">Atcelt</v-btn>
          <v-btn color="error" variant="flat" rounded="lg" :loading="deleteLoading" @click="doDelete">
            Dzēst
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Snackbar -->
    <v-snackbar v-model="snackbar" :color="snackbarColor" rounded="lg" timeout="3000">
      {{ snackbarText }}
    </v-snackbar>

  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const medicines     = ref([])
const loading       = ref(true)
const search        = ref('')
const formDialog    = ref(false)
const deleteDialog  = ref(false)
const editMode      = ref(false)
const formLoading   = ref(false)
const deleteLoading = ref(false)
const deleteTarget  = ref(null)
const formError     = ref('')
const formRef       = ref(null)
const snackbar      = ref(false)
const snackbarText  = ref('')
const snackbarColor = ref('success')

const form = ref({
  name: '', active_substance: '', form: '', dose: '', manufacturer: '', description: ''
})
const editId = ref(null)

const token   = localStorage.getItem('token')
const user    = ref(null)
const isAdmin = computed(() => user.value?.role === 'admin')

const availableCount   = computed(() => medicines.value.filter(m => m.status === 'available').length)
const unavailableCount = computed(() => medicines.value.filter(m => m.status !== 'available').length)

const filteredMedicines = computed(() => {
  if (!search.value) return medicines.value
  const q = search.value.toLowerCase()
  return medicines.value.filter(m =>
    m.name.toLowerCase().includes(q) || m.manufacturer?.toLowerCase().includes(q)
  )
})

const headers = [
  { title: 'Nosaukums', key: 'name' },
  { title: 'Forma', key: 'form' },
  { title: 'Deva', key: 'dose' },
  { title: 'Ražotājs', key: 'manufacturer' },
  { title: 'Statuss', key: 'status' },
  { title: 'Cena no', key: 'minPrice' },
  { title: 'Darbības', key: 'actions', sortable: false },
]

function showSnack(text, color = 'success') {
  snackbarText.value = text
  snackbarColor.value = color
  snackbar.value = true
}

async function loadMedicines() {
  loading.value = true
  try {
    const { data } = await axios.get('/api/medicines')
    medicines.value = data
  } catch {
    showSnack('Kļūda ielādējot datus', 'error')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editMode.value = false
  editId.value = null
  form.value = { name:'', active_substance:'', form:'', dose:'', manufacturer:'', description:'' }
  formError.value = ''
  formDialog.value = true
}

function openEdit(item) {
  editMode.value = true
  editId.value = item.id
  form.value = {
    name: item.name, active_substance: item.active_substance,
    form: item.form, dose: item.dose,
    manufacturer: item.manufacturer, description: item.description || ''
  }
  formError.value = ''
  formDialog.value = true
}

async function submitForm() {
  const { valid } = await formRef.value.validate()
  if (!valid) return

  formLoading.value = true
  formError.value = ''
  try {
    if (editMode.value) {
      await axios.put(`/api/medicines/${editId.value}`, form.value, {
        headers: { Authorization: `Bearer ${token}` }
      })
      showSnack('Zāles veiksmīgi atjauninātas!')
    } else {
      await axios.post('/api/medicines', form.value, {
        headers: { Authorization: `Bearer ${token}` }
      })
      showSnack('Zāles veiksmīgi pievienotas!')
    }
    formDialog.value = false
    await loadMedicines()
  } catch (e) {
    formError.value = e.response?.data?.message || 'Kļūda saglabājot datus.'
  } finally {
    formLoading.value = false
  }
}

function confirmDelete(item) {
  deleteTarget.value = item
  deleteDialog.value = true
}

async function doDelete() {
  deleteLoading.value = true
  try {
    await axios.delete(`/api/medicines/${deleteTarget.value.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    showSnack('Zāles dzēstas veiksmīgi!')
    deleteDialog.value = false
    await loadMedicines()
  } catch {
    showSnack('Kļūda dzēšot!', 'error')
  } finally {
    deleteLoading.value = false
  }
}

onMounted(async () => {
  if (token) {
    try {
      const { data } = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      })
      user.value = data
    } catch {}
  }
  await loadMedicines()
})
</script>
