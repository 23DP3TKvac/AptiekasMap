<template>
  <v-app :theme="theme">
    <!-- NAVBAR -->
    <v-app-bar color="secondary" elevation="0" border="b">
      <v-app-bar-title>
        <span class="logo-cross">✚</span>
        <span class="logo-name">AptiekasMap</span>
      </v-app-bar-title>

      <v-btn variant="text" color="white" to="/">Sākums</v-btn>
      <v-btn variant="text" color="white" to="/pharmacies">Aptiekas</v-btn>
      <v-btn variant="text" color="white" href="#search-section">Meklēt</v-btn>
      <v-btn variant="text" color="white" to="/statistics">Statistika</v-btn>
      <v-btn variant="text" color="white" to="/admin">Admin</v-btn>

      <v-spacer />

      <!-- Theme toggle -->
      <v-btn :icon="theme === 'dark' ? 'mdi-weather-sunny' : 'mdi-weather-night'"
             variant="text" color="white" @click="toggleTheme" />

      <!-- Login dialog -->
      <v-btn v-if="isLoggedIn" color="primary" variant="flat" rounded="lg" class="mr-3" to="/profile">
        <v-icon start>mdi-account</v-icon> Profils
      </v-btn>
      <v-btn v-else color="primary" variant="flat" rounded="lg" class="mr-3" @click="loginDialog = true">
        Pieslēgties
      </v-btn>

      <v-app-bar-nav-icon class="d-md-none" color="white" @click="drawer = !drawer" />
    </v-app-bar>

    <!-- Mobile drawer -->
    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item to="/"           title="Sākums"   prepend-icon="mdi-home" />
        <v-list-item to="/pharmacies" title="Aptiekas" prepend-icon="mdi-store" />
      </v-list>
    </v-navigation-drawer>

    <!-- Main content -->
    <v-main>
      <router-view />
    </v-main>

    <!-- Footer -->
    <v-footer color="secondary" class="text-center py-6">
      <div class="w-100">
        <div class="mb-2">
          <span class="logo-cross">✚</span>
          <span class="text-white font-weight-bold ml-1">AptiekasMap</span>
        </div>
        <div class="text-medium-emphasis text-caption">
          © 2025/2026 AptiekasMap · Timurs Kvačovs · Rīgas Valsts Tehnikums
        </div>
      </div>
    </v-footer>

    <!-- Login/Register dialog -->
    <v-dialog v-model="loginDialog" max-width="420">
      <v-card rounded="xl" class="pa-2">
        <v-card-title class="pt-4 px-6">
          <v-tabs v-model="authTab" color="primary">
            <v-tab value="login">Pieslēgties</v-tab>
            <v-tab value="register">Reģistrēties</v-tab>
          </v-tabs>
        </v-card-title>

        <v-card-text class="px-6">
          <v-window v-model="authTab">

            <!-- LOGIN -->
            <v-window-item value="login">
              <v-form @submit.prevent="handleLogin">
                <v-text-field v-model="loginForm.email"    label="E-pasts"  type="email"    prepend-inner-icon="mdi-email"    variant="outlined" class="mb-3" />
                <v-text-field v-model="loginForm.password" label="Parole"   type="password" prepend-inner-icon="mdi-lock"     variant="outlined" class="mb-3" />
                <v-btn type="submit" color="primary" block size="large" rounded="lg" :loading="authLoading">
                  Pieslēgties
                </v-btn>
                <v-alert v-if="authError" type="error" class="mt-3" density="compact">{{ authError }}</v-alert>
              </v-form>
            </v-window-item>

            <!-- REGISTER -->
            <v-window-item value="register">
              <v-form @submit.prevent="handleRegister">
                <v-text-field v-model="regForm.name"              label="Vārds"           prepend-inner-icon="mdi-account"  variant="outlined" class="mb-3" />
                <v-text-field v-model="regForm.email"             label="E-pasts"         prepend-inner-icon="mdi-email"    variant="outlined" class="mb-3" type="email" />
                <v-text-field v-model="regForm.password"          label="Parole"          prepend-inner-icon="mdi-lock"     variant="outlined" class="mb-3" type="password" />
                <v-text-field v-model="regForm.password_confirmation" label="Atkārtot paroli" prepend-inner-icon="mdi-lock-check" variant="outlined" class="mb-3" type="password" />
                <v-btn type="submit" color="primary" block size="large" rounded="lg" :loading="authLoading">
                  Reģistrēties
                </v-btn>
                <v-alert v-if="authError" type="error" class="mt-3" density="compact">{{ authError }}</v-alert>
              </v-form>
            </v-window-item>

          </v-window>
        </v-card-text>

        <v-card-actions class="px-6 pb-4">
          <v-spacer />
          <v-btn variant="text" @click="loginDialog = false">Aizvērt</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </v-app>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const theme       = ref(localStorage.getItem('theme') || 'light')
const drawer      = ref(false)
const loginDialog = ref(false)
const authTab     = ref('login')
const authLoading = ref(false)
const authError   = ref('')

const loginForm = ref({ email: '', password: '' })
const regForm   = ref({ name: '', email: '', password: '', password_confirmation: '' })

function toggleTheme() {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
  localStorage.setItem('theme', theme.value)
}

async function handleLogin() {
  authLoading.value = true
  authError.value   = ''
  try {
    const { data } = await axios.post('/api/auth/login', loginForm.value)
    localStorage.setItem('token', data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    loginDialog.value = false
  } catch (e) {
    authError.value = e.response?.data?.message || 'Pieslēgšanās kļūda.'
  } finally {
    authLoading.value = false
  }
}

async function handleRegister() {
  authLoading.value = true
  authError.value   = ''
  try {
    const { data } = await axios.post('/api/auth/register', regForm.value)
    localStorage.setItem('token', data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    loginDialog.value = false
  } catch (e) {
    authError.value = e.response?.data?.message || 'Reģistrācijas kļūda.'
  } finally {
    authLoading.value = false
  }
}

// Restore token on reload
const isLoggedIn = ref(!!localStorage.getItem("token"))
const savedToken = localStorage.getItem('token')
if (savedToken) axios.defaults.headers.common['Authorization'] = `Bearer ${savedToken}`
</script>

<style>
* { font-family: 'DM Sans', sans-serif; }
.logo-cross { color: #22c55e; font-size: 1.3rem; }
.logo-name  { font-family: 'Sora', sans-serif; font-weight: 700; color: white; margin-left: 6px; }
</style>
