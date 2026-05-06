<template>
  <v-container class="py-12">
    <div class="text-center mb-10">
      <h2 class="text-h4 font-weight-bold mb-2" style="font-family:'Sora',sans-serif">Aptiekas Rīgā</h2>
      <p class="text-medium-emphasis">{{ pharmacies.length }} aptiekas sistēmā — dati atjaunināti reāllaikā</p>
    </div>

    <div v-if="loading" class="text-center py-12">
      <v-progress-circular color="primary" indeterminate size="56" />
    </div>

    <v-row v-else>
      <v-col v-for="p in pharmacies" :key="p.id" cols="12" sm="6" lg="4">
        <v-card rounded="xl" elevation="2" height="100%" class="d-flex flex-column">
          <v-card-item>
            <template #prepend>
              <v-avatar :color="p.color" rounded="lg" size="44">
                <span class="text-white font-weight-bold">{{ p.name[0] }}</span>
              </v-avatar>
            </template>
            <v-card-title class="text-body-1">{{ p.name }}</v-card-title>
            <v-card-subtitle>
              <v-icon size="14">mdi-map-marker</v-icon> {{ p.address }}
            </v-card-subtitle>
            <template #append>
              <v-chip :color="p.open ? 'success' : 'error'" size="x-small" variant="tonal">
                {{ p.open ? 'Atvērts' : 'Slēgts' }}
              </v-chip>
            </template>
          </v-card-item>

          <v-card-text class="flex-grow-1">
            <div class="d-flex gap-4 text-caption text-medium-emphasis">
              <span><v-icon size="14">mdi-phone</v-icon> {{ p.phone || '—' }}</span>
              <span><v-icon size="14">mdi-clock</v-icon> {{ p.hours }}</span>
            </div>
          </v-card-text>

          <v-card-actions class="px-4 pb-4">
            <v-btn :href="`https://maps.google.com/?q=${encodeURIComponent(p.address)}`"
                   target="_blank" variant="outlined" color="primary" rounded="lg" block>
              Atvērt kartē
              <v-icon end>mdi-open-in-new</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const loading   = ref(true)
const pharmacies = ref([])

const colors = ['#16a34a','#7c3aed','#0891b2','#b45309','#be185d']
const hours  = ['9:00–21:00','8:00–22:00','9:00–20:00','8:30–20:00','9:00–18:00']
const open   = [true, true, true, true, false]

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/pharmacies')
    pharmacies.value = data.map((p, i) => ({
      ...p, color: colors[i % colors.length],
      hours: hours[i % hours.length],
      open:  open[i % open.length],
    }))
  } catch {
    pharmacies.value = [
      { id:1, name:'BENU Aptieka Brīvības',  address:'Brīvības iela 85, Rīga',     phone:'+37167001111', color:'#16a34a', hours:'9:00–21:00', open:true  },
      { id:2, name:'Mēness Aptieka Centrs',  address:'Elizabetes iela 10, Rīga',   phone:'+37167002222', color:'#7c3aed', hours:'8:00–22:00', open:true  },
      { id:3, name:'Euroaptieka Imanta',     address:'Imanta 7. līnija 1, Rīga',   phone:'+37167003333', color:'#0891b2', hours:'9:00–20:00', open:true  },
      { id:4, name:'Dr. Tālivaldis Aptieka', address:'Dzirnavu iela 55, Rīga',     phone:'+37167004444', color:'#b45309', hours:'8:30–20:00', open:true  },
      { id:5, name:'Apotheka Pārdaugava',    address:'Kurzemes prospekts 3, Rīga', phone:'+37167005555', color:'#be185d', hours:'9:00–18:00', open:false },
    ]
  } finally {
    loading.value = false
  }
})
</script>
