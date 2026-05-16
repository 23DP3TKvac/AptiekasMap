<template>
  <v-container class="py-12">
    <div class="text-center mb-10">
      <h2 class="text-h4 font-weight-bold mb-2" style="font-family:'Sora',sans-serif">
        <v-icon color="primary" class="mr-2">mdi-chart-bar</v-icon>
        Statistika
      </h2>
      <p class="text-medium-emphasis">Sistēmas datu kopsavilkums un analīze</p>
    </div>

    <div v-if="loading" class="text-center py-12">
      <v-progress-circular color="primary" indeterminate size="56" />
    </div>

    <div v-else>
      <!-- SUMMARY CARDS -->
      <v-row class="mb-8">
        <v-col v-for="s in summaryCards" :key="s.label" cols="6" sm="3">
          <v-card rounded="xl" :color="s.color" variant="tonal" class="pa-4 text-center">
            <v-icon :color="s.color" size="36" class="mb-2">{{ s.icon }}</v-icon>
            <div class="text-h4 font-weight-bold" :class="`text-${s.color}`"
                 style="font-family:'Sora',sans-serif">{{ s.value }}</div>
            <div class="text-body-2 text-medium-emphasis">{{ s.label }}</div>
          </v-card>
        </v-col>
      </v-row>

      <v-row>
        <!-- BAR CHART — zāļu skaits aptiekās -->
        <v-col cols="12" md="6">
          <v-card rounded="xl" elevation="2" class="pa-4">
            <div class="text-subtitle-1 font-weight-bold mb-4">
              <v-icon color="primary" class="mr-2">mdi-store</v-icon>
              Pieejamo zāļu skaits aptiekās
            </div>
            <div v-for="item in stats.medicinesPerPharmacy" :key="item.pharmacy" class="mb-3">
              <div class="d-flex justify-space-between mb-1">
                <span class="text-body-2">{{ item.pharmacy }}</span>
                <span class="text-body-2 font-weight-bold text-primary">{{ item.count }}</span>
              </div>
              <v-progress-linear
                :model-value="(item.count / maxPharmacyCount) * 100"
                color="primary" rounded height="10" bg-color="surface-variant" />
            </div>
          </v-card>
        </v-col>

        <!-- PRICE TABLE -->
        <v-col cols="12" md="6">
          <v-card rounded="xl" elevation="2" class="pa-4">
            <div class="text-subtitle-1 font-weight-bold mb-4">
              <v-icon color="primary" class="mr-2">mdi-cash</v-icon>
              Cenu analīze pēc medikamenta
            </div>
            <v-table density="compact">
              <thead>
                <tr>
                  <th>Medikaments</th>
                  <th class="text-center">Min €</th>
                  <th class="text-center">Vid €</th>
                  <th class="text-center">Max €</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in stats.avgPricePerMedicine" :key="item.medicine">
                  <td class="text-body-2">{{ item.medicine }}</td>
                  <td class="text-center text-success font-weight-bold">{{ item.minPrice }}</td>
                  <td class="text-center text-primary font-weight-bold">{{ item.avgPrice }}</td>
                  <td class="text-center text-error font-weight-bold">{{ item.maxPrice }}</td>
                </tr>
              </tbody>
            </v-table>
          </v-card>
        </v-col>

        <!-- TOP SEARCHES -->
        <v-col cols="12" md="6">
          <v-card rounded="xl" elevation="2" class="pa-4">
            <div class="text-subtitle-1 font-weight-bold mb-4">
              <v-icon color="primary" class="mr-2">mdi-trending-up</v-icon>
              Populārākie meklējumi
            </div>
            <div v-if="stats.topSearches.length === 0" class="text-center py-4 text-medium-emphasis">
              Nav meklēšanas datu
            </div>
            <div v-for="(item, i) in stats.topSearches" :key="item.medicine_name" class="mb-3">
              <div class="d-flex align-center gap-3 mb-1">
                <v-chip size="x-small" :color="i === 0 ? 'primary' : 'default'" variant="tonal">
                  #{{ i + 1 }}
                </v-chip>
                <span class="text-body-2 flex-grow-1">{{ item.medicine_name }}</span>
                <span class="text-body-2 font-weight-bold text-primary">{{ item.count }}x</span>
              </div>
              <v-progress-linear
                :model-value="(item.count / maxSearchCount) * 100"
                color="secondary" rounded height="6" bg-color="surface-variant" />
            </div>
          </v-card>
        </v-col>

        <!-- STATUS PIE (manual) -->
        <v-col cols="12" md="6">
          <v-card rounded="xl" elevation="2" class="pa-4">
            <div class="text-subtitle-1 font-weight-bold mb-4">
              <v-icon color="primary" class="mr-2">mdi-chart-pie</v-icon>
              Pieejamības sadalījums
            </div>
            <div class="d-flex align-center justify-center gap-6 py-4">
              <div class="text-center">
                <v-progress-circular
                  :model-value="availablePercent" color="success"
                  size="100" width="12">
                  <span class="text-h6 font-weight-bold text-success">{{ availablePercent }}%</span>
                </v-progress-circular>
                <div class="text-body-2 mt-2">Pieejams</div>
              </div>
              <div class="text-center">
                <v-progress-circular
                  :model-value="unavailablePercent" color="error"
                  size="100" width="12">
                  <span class="text-h6 font-weight-bold text-error">{{ unavailablePercent }}%</span>
                </v-progress-circular>
                <div class="text-body-2 mt-2">Nav pieejams</div>
              </div>
            </div>
            <v-divider class="my-3" />
            <div class="d-flex justify-space-around text-center">
              <div>
                <div class="text-h6 text-success font-weight-bold">{{ stats.summary?.totalAvailable }}</div>
                <div class="text-caption text-medium-emphasis">Pieejami ieraksti</div>
              </div>
              <div>
                <div class="text-h6 text-primary font-weight-bold">{{ stats.summary?.totalMedicines }}</div>
                <div class="text-caption text-medium-emphasis">Medikamenti</div>
              </div>
              <div>
                <div class="text-h6 text-warning font-weight-bold">{{ stats.summary?.totalPharmacies }}</div>
                <div class="text-caption text-medium-emphasis">Aptiekas</div>
              </div>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </div>

     <!-- USERS TABLE -->
     <v-row class="mt-8">
      <v-col cols="12">
        <v-card rounded="xl" elevation="2" class="pa-4">
          <div class="text-subtitle-1 font-weight-bold mb-4">
            <v-icon color="primary" class="mr-2">mdi-account-group</v-icon>
            Reģistrētie lietotāji
          </div>
          <v-table density="compact">
            <thead>
              <tr>
                <th>ID</th>
                <th>Vārds</th>
                <th>E-pasts</th>
                <th>Loma</th>
                <th>Reģistrēts</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in users" :key="u.id">
                <td>{{ u.id }}</td>
                <td class="font-weight-bold">{{ u.name }}</td>
                <td>{{ u.email }}</td>
                <td>
                  <v-chip :color="u.role === 'admin' ? 'error' : u.role === 'pharmacy_rep' ? 'warning' : 'primary'"
                    size="x-small" variant="tonal">{{ u.role }}</v-chip>
                </td>
                <td class="text-caption text-medium-emphasis">
                  {{ new Date(u.created_at).toLocaleDateString('lv-LV') }}
                </td>
              </tr>
            </tbody>
          </v-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const loading = ref(true)
const stats   = ref({
  medicinesPerPharmacy: [],
  avgPricePerMedicine:  [],
  topSearches:          [],
  summary:              {},
})

const users = ref([])

async function loadUsers() {
  try {
    const token = localStorage.getItem('token')
    const { data } = await axios.get('/api/admin/users', {
      headers: { Authorization: `Bearer ${token}` }
    })
    users.value = data
  } catch {
    users.value = []
  }
}

const summaryCards = computed(() => [
  { label:'Medikamenti',  value: stats.value.summary?.totalMedicines,  color:'primary', icon:'mdi-pill' },
  { label:'Aptiekas',     value: stats.value.summary?.totalPharmacies, color:'success', icon:'mdi-store' },
  { label:'Pieejami',     value: stats.value.summary?.totalAvailable,  color:'warning', icon:'mdi-check-circle' },
  { label:'Meklējumi',    value: stats.value.summary?.totalSearches,   color:'info',    icon:'mdi-magnify' },
])

const maxPharmacyCount = computed(() =>
  Math.max(...stats.value.medicinesPerPharmacy.map(i => i.count), 1)
)
const maxSearchCount = computed(() =>
  Math.max(...stats.value.topSearches.map(i => i.count), 1)
)
const availablePercent = computed(() => {
  const total = (stats.value.summary?.totalAvailable || 0) + (stats.value.summary?.totalMedicines || 0)
  if (!total) return 0
  return Math.round((stats.value.summary?.totalAvailable / total) * 100)
})
const unavailablePercent = computed(() => 100 - availablePercent.value)

onMounted(async () => { loadUsers()
  try {
    const { data } = await axios.get('/api/statistics')
    stats.value = data
  } catch {
    // Demo data
    stats.value = {
      medicinesPerPharmacy: [
        { pharmacy: 'BENU Aptieka Brīvības', count: 3 },
        { pharmacy: 'Mēness Aptieka Centrs', count: 3 },
        { pharmacy: 'Euroaptieka Imanta',    count: 3 },
        { pharmacy: 'Dr. Tālivaldis',        count: 2 },
        { pharmacy: 'Apotheka Pārdaugava',   count: 2 },
      ],
      avgPricePerMedicine: [
        { medicine: 'Paracetamol BENU',   minPrice: 1.29, avgPrice: 1.37, maxPrice: 1.45 },
        { medicine: 'Ibuprofen Grindeks', minPrice: 1.99, avgPrice: 2.05, maxPrice: 2.10 },
        { medicine: 'Loratadīns Teva',    minPrice: 3.10, avgPrice: 3.15, maxPrice: 3.20 },
      ],
      topSearches: [
        { medicine_name: 'Paracetamol', count: 3 },
        { medicine_name: 'Ibuprofen',   count: 2 },
      ],
      summary: { totalMedicines: 6, totalPharmacies: 5, totalAvailable: 13, totalSearches: 4 },
    }
  } finally {
    loading.value = false
  }
})
</script>
