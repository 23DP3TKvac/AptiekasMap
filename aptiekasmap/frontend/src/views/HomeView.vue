<template>
  <div>
    <!-- HERO -->
    <section class="hero-section">
      <div class="hero-glow" />
      <v-container class="hero-inner">
        <v-row align="center" justify="space-between">
          <v-col cols="12" md="6">
            <v-chip color="primary" variant="tonal" class="mb-5" prepend-icon="mdi-map-marker">
              Latvijas aptiekas reāllaikā
            </v-chip>
            <h1 class="hero-title">
              Atrodi <span class="text-primary">zāles</span><br>tuvākajā aptiekā
            </h1>
            <p class="hero-sub">Salīdzini cenas, pārbaudi pieejamību un noskaidro ceļu — vienā vietā.</p>
            <v-card rounded="xl" elevation="8" class="pa-2 hero-search-card">
              <div class="d-flex align-center gap-2">
                <v-text-field v-model="heroQuery" placeholder="Ievadi zāļu nosaukumu, piem. Paracetamol..."
                  prepend-inner-icon="mdi-magnify" variant="plain" hide-details density="comfortable"
                  class="flex-grow-1" @keyup.enter="doHeroSearch" />
                <v-btn color="primary" size="large" rounded="lg" @click="doHeroSearch">Meklēt</v-btn>
              </div>
            </v-card>
            <div class="mt-4 d-flex flex-wrap gap-2 align-center">
              <span class="text-medium-emphasis text-body-2">Populāri:</span>
              <v-chip v-for="tag in popularTags" :key="tag" size="small" variant="tonal" color="primary"
                class="cursor-pointer" @click="quickSearch(tag)">{{ tag }}</v-chip>
            </div>
          </v-col>
          <v-col cols="12" md="4" class="d-none d-md-flex flex-column gap-4">
            <v-card rounded="xl" color="secondary" class="pa-4 floating-card fc1">
              <div class="d-flex align-center gap-3">
                <v-icon size="32" color="primary">mdi-pill</v-icon>
                <div><div class="text-white font-weight-bold">Paracetamol 500mg</div>
                <div class="text-medium-emphasis text-caption">Pieejams 4 aptiekās</div></div>
              </div>
            </v-card>
            <v-card rounded="xl" color="secondary" class="pa-4 floating-card fc2">
              <div class="d-flex align-center gap-3">
                <v-icon size="32" color="primary">mdi-map-marker</v-icon>
                <div><div class="text-white font-weight-bold">BENU Brīvības ielā</div>
                <div class="text-medium-emphasis text-caption">0.3 km no tevis</div></div>
              </div>
            </v-card>
            <v-card rounded="xl" color="secondary" class="pa-4 floating-card fc3">
              <div class="d-flex align-center gap-3">
                <v-icon size="32" color="primary">mdi-cash</v-icon>
                <div><div class="text-white font-weight-bold">No €1.29</div>
                <div class="text-medium-emphasis text-caption">Labākā cena šobrīd</div></div>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </section>

    <!-- STATS BAR -->
    <v-sheet color="primary" class="py-4">
      <v-container>
        <v-row justify="center" align="center">
          <v-col v-for="stat in stats" :key="stat.label" cols="6" sm="3" class="text-center">
            <div class="text-h5 font-weight-bold text-white" style="font-family:'Sora',sans-serif">{{ stat.value }}</div>
            <div class="text-caption text-white" style="opacity:.85">{{ stat.label }}</div>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>

    <!-- SEARCH SECTION -->
    <v-container class="py-12" id="search-section">
      <div class="text-center mb-8">
        <h2 class="text-h4 font-weight-bold mb-2" style="font-family:'Sora',sans-serif">Meklēt zāles</h2>
        <p class="text-medium-emphasis">Izmanto filtrus lai precīzi atrastu vajadzīgo medikamentu</p>
      </div>

      <!-- ADVANCED FILTERS -->
      <v-card rounded="xl" elevation="2" class="mb-6 pa-4">
        <div class="text-subtitle-1 font-weight-bold mb-4">
          <v-icon color="primary" class="mr-2">mdi-filter</v-icon>
          Paplašinātā filtrēšana
        </div>
        <v-row dense>
          <!-- Name -->
          <v-col cols="12" sm="6" md="4">
            <v-text-field v-model="filters.name" label="Zāļu nosaukums vai aktīvā viela"
              prepend-inner-icon="mdi-magnify" variant="outlined" density="compact"
              clearable hide-details @input="applyFilters" />
          </v-col>
          <!-- Form -->
          <v-col cols="6" sm="3" md="2">
            <v-select v-model="filters.form" :items="formOptions" label="Forma"
              variant="outlined" density="compact" hide-details @update:modelValue="applyFilters" />
          </v-col>
          <!-- Status -->
          <v-col cols="6" sm="3" md="2">
            <v-select v-model="filters.status" :items="statusOptions" label="Statuss"
              variant="outlined" density="compact" hide-details @update:modelValue="applyFilters" />
          </v-col>
          <!-- Price min -->
          <v-col cols="6" sm="3" md="2">
            <v-text-field v-model="filters.price_min" label="Cena no (€)"
              type="number" variant="outlined" density="compact" hide-details
              prepend-inner-icon="mdi-currency-eur" @input="applyFilters" />
          </v-col>
          <!-- Price max -->
          <v-col cols="6" sm="3" md="2">
            <v-text-field v-model="filters.price_max" label="Cena līdz (€)"
              type="number" variant="outlined" density="compact" hide-details
              prepend-inner-icon="mdi-currency-eur" @input="applyFilters" />
          </v-col>
        </v-row>

        <v-row dense class="mt-2">
          <!-- Sort -->
          <v-col cols="12" sm="4">
            <v-select v-model="filters.sort" :items="sortOptions" label="Kārtot pēc"
              variant="outlined" density="compact" hide-details @update:modelValue="applyFilters" />
          </v-col>
          <!-- Manufacturer -->
          <v-col cols="12" sm="5">
            <v-text-field v-model="filters.manufacturer" label="Ražotājs"
              variant="outlined" density="compact" hide-details clearable @input="applyFilters" />
          </v-col>
          <!-- Clear -->
          <v-col cols="12" sm="3" class="d-flex align-center">
            <v-btn variant="outlined" color="error" rounded="lg" block @click="clearFilters">
              <v-icon start>mdi-close</v-icon> Notīrīt filtrus
            </v-btn>
          </v-col>
        </v-row>

        <!-- Active filter chips -->
        <div v-if="activeFiltersCount > 0" class="mt-3 d-flex flex-wrap gap-2 align-center">
          <span class="text-caption text-medium-emphasis">Aktīvie filtri:</span>
          <v-chip v-if="filters.name" size="small" closable @click:close="filters.name=''; applyFilters()">
            Nosaukums: {{ filters.name }}
          </v-chip>
          <v-chip v-if="filters.form" size="small" closable @click:close="filters.form=''; applyFilters()">
            Forma: {{ filters.form }}
          </v-chip>
          <v-chip v-if="filters.status" size="small" closable @click:close="filters.status=''; applyFilters()">
            Statuss: {{ filters.status === 'available' ? 'Pieejams' : 'Nav pieejams' }}
          </v-chip>
          <v-chip v-if="filters.price_min" size="small" closable @click:close="filters.price_min=''; applyFilters()">
            No: €{{ filters.price_min }}
          </v-chip>
          <v-chip v-if="filters.price_max" size="small" closable @click:close="filters.price_max=''; applyFilters()">
            Līdz: €{{ filters.price_max }}
          </v-chip>
          <v-chip v-if="filters.manufacturer" size="small" closable @click:close="filters.manufacturer=''; applyFilters()">
            Ražotājs: {{ filters.manufacturer }}
          </v-chip>
        </div>
      </v-card>

      <!-- Results count -->
      <div class="mb-4 text-medium-emphasis text-body-2">
        Atrasti: <strong>{{ filteredMedicines.length }}</strong> medikamenti
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-12">
        <v-progress-circular color="primary" indeterminate size="56" />
        <p class="mt-4 text-medium-emphasis">Ielādē zāles...</p>
      </div>

      <!-- Error -->
      <v-alert v-else-if="error" type="error" class="mb-6">{{ error }}</v-alert>

      <!-- Medicine cards -->
      <v-row v-else>
        <v-col v-for="med in filteredMedicines" :key="med.id" cols="12" sm="6" lg="4">
          <v-card rounded="xl" elevation="2" height="100%" class="med-card d-flex flex-column">
            <v-card-item>
              <template #prepend>
                <v-icon size="36" color="primary">mdi-pill</v-icon>
              </template>
              <v-card-title class="text-body-1 font-weight-bold">{{ med.name }}</v-card-title>
              <v-card-subtitle>{{ med.form }} · {{ med.dose }}</v-card-subtitle>
              <template #append>
                <v-chip :color="med.status === 'available' ? 'success' : 'error'" size="x-small" variant="tonal">
                  {{ med.status === 'available' ? 'Pieejams' : 'Nav pieejams' }}
                </v-chip>
              </template>
            </v-card-item>
            <v-card-text class="flex-grow-1">
              <p class="text-medium-emphasis text-body-2 mb-3">{{ med.description }}</p>
              <p class="text-caption text-medium-emphasis mb-1">
                <v-icon size="14">mdi-factory</v-icon> {{ med.manufacturer }}
              </p>
              <p class="text-caption text-medium-emphasis mb-3">
                <v-icon size="14">mdi-flask</v-icon> Aktīvā viela: {{ med.active_substance }}
              </p>
              <div class="d-flex justify-space-between align-center">
                <span class="text-h6 font-weight-bold text-primary" style="font-family:'Sora',sans-serif">
                  €{{ med.minPrice }}
                </span>
                <span class="text-caption text-medium-emphasis">
                  <v-icon size="14">mdi-store</v-icon> {{ med.pharmacyCount }} aptiekās
                </span>
              </div>
            </v-card-text>
            <v-card-actions class="px-4 pb-4">
              <v-btn color="primary" variant="tonal" rounded="lg" block @click="openAvailability(med)">
                Skatīt aptiekas <v-icon end>mdi-arrow-right</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>

        <v-col v-if="filteredMedicines.length === 0 && !loading" cols="12" class="text-center py-12">
          <v-icon size="64" color="medium-emphasis">mdi-pill-off</v-icon>
          <p class="text-h6 mt-4 text-medium-emphasis">Nekas netika atrasts</p>
          <p class="text-body-2 text-medium-emphasis">Mēģini mainīt filtrus</p>
          <v-btn color="primary" variant="tonal" class="mt-4" @click="clearFilters">Notīrīt filtrus</v-btn>
        </v-col>
      </v-row>
    </v-container>

    <!-- HOW IT WORKS -->
    <v-sheet color="surface" class="py-12">
      <v-container>
        <div class="text-center mb-10">
          <h2 class="text-h4 font-weight-bold mb-2" style="font-family:'Sora',sans-serif">Kā tas darbojas?</h2>
          <p class="text-medium-emphasis">3 soļi līdz savām zālēm</p>
        </div>
        <v-row justify="center">
          <v-col v-for="(step, i) in steps" :key="i" cols="12" sm="4">
            <v-card rounded="xl" variant="tonal" color="primary" class="pa-6 text-center" height="100%">
              <div class="text-caption text-primary font-weight-bold mb-2">0{{ i + 1 }}</div>
              <v-icon size="48" color="primary" class="mb-4">{{ step.icon }}</v-icon>
              <h4 class="text-subtitle-1 font-weight-bold mb-2">{{ step.title }}</h4>
              <p class="text-body-2 text-medium-emphasis">{{ step.desc }}</p>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>

    <!-- CONTACT -->
    <v-container class="py-12" id="contact">
      <v-row align="start" justify="center">
        <v-col cols="12" md="5" class="mb-6 mb-md-0">
          <h2 class="text-h4 font-weight-bold mb-4" style="font-family:'Sora',sans-serif">Sazinies ar mums</h2>
          <p class="text-medium-emphasis mb-6">Vai tava aptieka vēlas pievienoties? Vai ir jautājumi? Raksti mums!</p>
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-center gap-2"><v-icon color="primary">mdi-email</v-icon><span>aptiekasmap@gmail.com</span></div>
            <div class="d-flex align-center gap-2"><v-icon color="primary">mdi-phone</v-icon><span>+371 2000 0000</span></div>
            <div class="d-flex align-center gap-2"><v-icon color="primary">mdi-map-marker</v-icon><span>Rīga, Latvija</span></div>
          </div>
        </v-col>
        <v-col cols="12" md="6">
          <v-card rounded="xl" elevation="3" class="pa-6">
            <v-form @submit.prevent="submitContact">
              <v-text-field v-model="contact.name" label="Tavs vārds" variant="outlined" class="mb-3" />
              <v-text-field v-model="contact.email" label="E-pasta adrese" variant="outlined" class="mb-3" type="email" />
              <v-textarea v-model="contact.message" label="Ziņojums..." variant="outlined" class="mb-3" rows="4" />
              <v-btn type="submit" color="primary" size="large" rounded="lg" block :loading="contactLoading">
                Nosūtīt ziņu <v-icon end>mdi-send</v-icon>
              </v-btn>
              <v-alert v-if="contactSuccess" type="success" class="mt-3" density="compact">
                ✓ Ziņojums veiksmīgi nosūtīts!
              </v-alert>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <!-- AVAILABILITY DIALOG -->
    <v-dialog v-model="availDialog" max-width="560">
      <v-card rounded="xl" class="pa-2">
        <v-card-title class="pt-4 px-6 d-flex align-center gap-2">
          <v-icon color="primary">mdi-pill</v-icon> {{ selectedMed?.name }}
        </v-card-title>
        <v-card-subtitle class="px-6">Pieejamība aptiekās</v-card-subtitle>
        <v-card-text class="px-6">
          <div v-if="availLoading" class="text-center py-6">
            <v-progress-circular color="primary" indeterminate />
          </div>
          <div v-else-if="availability.length === 0" class="text-center py-6 text-medium-emphasis">
            Nav pieejamības datu
          </div>
          <v-list v-else lines="two">
            <v-list-item v-for="a in availability" :key="a.id" :subtitle="a.pharmacy?.address">
              <template #title><span class="font-weight-bold">{{ a.pharmacy?.name }}</span></template>
              <template #append>
                <div class="text-right">
                  <div class="text-primary font-weight-bold" style="font-family:'Sora',sans-serif">€{{ a.price }}</div>
                  <v-chip :color="a.status === 'available' ? 'success' : 'error'" size="x-small" variant="tonal">
                    {{ a.status === 'available' ? 'Pieejams' : 'Nav' }}
                  </v-chip>
                </div>
              </template>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions class="px-6 pb-4">
          <v-spacer /><v-btn variant="text" @click="availDialog = false">Aizvērt</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const popularTags = ['Paracetamol', 'Ibuprofen', 'Amoxicillin', 'Loratadīns']
const stats = [
  { value: '6+', label: 'Medikamenti' },
  { value: '5', label: 'Aptiekas Rīgā' },
  { value: 'Reāllaikā', label: 'Pieejamības dati' },
  { value: 'Bezmaksas', label: 'Reģistrācija' },
]
const steps = [
  { icon: 'mdi-magnify', title: 'Meklē zāles', desc: 'Ievadi zāļu nosaukumu vai aktīvo vielu meklēšanas laukā' },
  { icon: 'mdi-chart-bar', title: 'Salīdzini cenas', desc: 'Redzi pieejamību un cenas visās tuvākajās aptiekās uzreiz' },
  { icon: 'mdi-map', title: 'Dodies uz aptieku', desc: 'Atver Google Maps un iegūsti maršrutu līdz izvēlētajai aptiekai' },
]
const statusOptions = [
  { title: 'Visi statusi', value: '' },
  { title: 'Pieejams', value: 'available' },
  { title: 'Nav pieejams', value: 'unavailable' },
]
const sortOptions = [
  { title: 'Noklusējums', value: '' },
  { title: 'Cena: mazākā', value: 'price-asc' },
  { title: 'Cena: lielākā', value: 'price-desc' },
  { title: 'Nosaukums A–Z', value: 'name' },
]

const medicines        = ref([])
const formOptions      = ref([{ title: 'Visas formas', value: '' }])
const loading          = ref(true)
const error            = ref('')
const heroQuery        = ref('')
const availDialog      = ref(false)
const availLoading     = ref(false)
const availability     = ref([])
const selectedMed      = ref(null)
const contact          = ref({ name: '', email: '', message: '' })
const contactLoading   = ref(false)
const contactSuccess   = ref(false)

const filters = ref({
  name: '', form: '', status: '', price_min: '', price_max: '',
  manufacturer: '', sort: ''
})

const activeFiltersCount = computed(() =>
  Object.entries(filters.value).filter(([k, v]) => v && k !== 'sort').length
)

const filteredMedicines = computed(() => {
  let list = [...medicines.value]

  if (filters.value.sort === 'price-asc')
    list.sort((a, b) => parseFloat(a.minPrice) - parseFloat(b.minPrice))
  else if (filters.value.sort === 'price-desc')
    list.sort((a, b) => parseFloat(b.minPrice) - parseFloat(a.minPrice))
  else if (filters.value.sort === 'name')
    list.sort((a, b) => a.name.localeCompare(b.name))

  return list
})

async function applyFilters() {
  loading.value = true
  error.value = ''
  try {
    const params = {}
    if (filters.value.name)         params.name = filters.value.name
    if (filters.value.form)         params.form = filters.value.form
    if (filters.value.status)       params.status = filters.value.status
    if (filters.value.price_min)    params.price_min = filters.value.price_min
    if (filters.value.price_max)    params.price_max = filters.value.price_max
    if (filters.value.manufacturer) params.manufacturer = filters.value.manufacturer

    const { data } = await axios.get('/api/medicines', { params })
    medicines.value = data
  } catch {
    error.value = 'API kļūda'
  } finally {
    loading.value = false
  }
}

function clearFilters() {
  filters.value = { name: '', form: '', status: '', price_min: '', price_max: '', manufacturer: '', sort: '' }
  applyFilters()
}

function quickSearch(term) {
  filters.value.name = term
  document.getElementById('search-section')?.scrollIntoView({ behavior: 'smooth' })
  const token = localStorage.getItem('token')
  if (token) axios.post('/api/user/history', { medicine_name: term }).catch(() => {})
  applyFilters()
}

function doHeroSearch() {
  if (heroQuery.value.trim()) quickSearch(heroQuery.value.trim())
}

async function openAvailability(med) {
  selectedMed.value = med
  availDialog.value = true
  availLoading.value = true
  availability.value = []
  try {
    const { data } = await axios.get(`/api/availability/${med.id}`)
    availability.value = data
  } catch {
    availability.value = [
      { id:1, pharmacy: { name:'BENU Aptieka Brīvības', address:'Brīvības iela 85, Rīga' }, price:'1.45', status:'available' },
      { id:2, pharmacy: { name:'Mēness Aptieka Centrs', address:'Elizabetes iela 10, Rīga'}, price:'1.35', status:'available' },
    ]
  } finally {
    availLoading.value = false
  }
}

function submitContact() {
  contactLoading.value = true
  setTimeout(() => {
    contactLoading.value = false
    contactSuccess.value = true
    contact.value = { name:'', email:'', message:'' }
    setTimeout(() => contactSuccess.value = false, 4000)
  }, 800)
}

onMounted(async () => {
  try {
    const [medsRes, formsRes] = await Promise.all([
      axios.get('/api/medicines'),
      axios.get('/api/medicines/forms'),
    ])
    medicines.value = medsRes.data
    formOptions.value = [
      { title: 'Visas formas', value: '' },
      ...formsRes.data.map(f => ({ title: f, value: f }))
    ]
  } catch {
    error.value = 'API nav pieejams — parādās demo dati.'
    medicines.value = [
      { id:1, name:'Paracetamol BENU',   form:'Tabletes', dose:'500mg',    description:'Pretsāpju līdzeklis',    status:'available',   minPrice:'1.29', pharmacyCount:4, active_substance:'Paracetamols',  manufacturer:'BENU Pharma' },
      { id:2, name:'Ibuprofen Grindeks', form:'Tabletes', dose:'400mg',    description:'Pretiekaisuma līdzeklis', status:'available',   minPrice:'1.99', pharmacyCount:2, active_substance:'Ibuprofēns',    manufacturer:'Grindeks AS' },
      { id:3, name:'Amoxicillin Sandoz', form:'Kapsulas', dose:'500mg',    description:'Antibiotiķis',            status:'unavailable', minPrice:'5.80', pharmacyCount:1, active_substance:'Amoksicilīns',  manufacturer:'Sandoz' },
    ]
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.hero-section { background:#0f172a; position:relative; overflow:hidden; padding:80px 0 60px; }
.hero-glow { position:absolute; inset:0; pointer-events:none;
  background: radial-gradient(ellipse 70% 60% at 65% 50%, rgba(22,163,74,.18) 0%, transparent 70%),
              radial-gradient(ellipse 50% 70% at 10% 80%, rgba(37,99,235,.12) 0%, transparent 60%); }
.hero-inner { position:relative; z-index:1; }
.hero-title { font-family:'Sora',sans-serif; font-size:clamp(2rem,5vw,3rem); font-weight:700; color:white; line-height:1.15; margin-bottom:16px; }
.hero-sub { color:#94a3b8; font-size:1.05rem; margin-bottom:28px; max-width:440px; }
.hero-search-card { max-width:540px; }
.floating-card { animation:float 4s ease-in-out infinite; }
.fc1 { animation-delay:0s; } .fc2 { animation-delay:1.5s; } .fc3 { animation-delay:3s; }
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
.med-card { transition:transform .25s,box-shadow .25s; }
.med-card:hover { transform:translateY(-4px); }
.cursor-pointer { cursor:pointer; }
</style>
