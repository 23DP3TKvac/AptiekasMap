# 💊 AptiekasMap

> Tīmekļa platforma zāļu pieejamības noteikšanai tuvākajās aptiekās

**Autors:** Timurs Kvačovs  
**Skola:** Rīgas Valsts Tehnikums — Datorikas nodaļa  
**Modulis:** Datu bāzu programmēšana  
**Mācību gads:** 2025/2026

---

## 🌐 Publiskā piekļuve

| Resurss | URL |
|---------|-----|
| 🖥️ Tīmekļa vietne | [aptiekas-map.vercel.app](https://aptiekas-map.vercel.app) |
| ⚙️ Backend API | [majaslapa3kurss-production.up.railway.app](https://majaslapa3kurss-production.up.railway.app) |
| 📁 GitHub | [github.com/23DP3TKvac/AptiekasMap](https://github.com/23DP3TKvac/AptiekasMap) |

---

## 📋 Par projektu

AptiekasMap ir tīmekļa platforma, kas ļauj lietotājiem ātri atrast nepieciešamās zāles tuvākajās Rīgas aptiekās, salīdzināt to cenas un pieejamību reāllaikā.

### Problēma
Latvijā nav vienotas platformas, kas apvienotu dažādu aptieku datus vienā vietā. Esošie risinājumi (Zāles.lv, Apteka.lv, BENU) piedāvā tikai sava tīkla informāciju.

---

## 🚀 Tehnoloģiju steks

| Slānis | Tehnoloģija |
|--------|-------------|
| Frontend | Vue.js 3 + Vuetify 3 + Pinia |
| Backend | Laravel 11 (PHP 8.4) |
| Datubāze | MySQL 8.0 |
| Autentifikācija | Laravel Sanctum (bcrypt) |
| Ārējais API | OpenStreetMap Nominatim |
| Frontend hosting | Vercel |
| Backend hosting | Railway.app |

---

## ✨ Funkcionalitāte

- 🔍 Zāļu meklēšana pēc nosaukuma vai aktīvās vielas (pieejams arī viesiem)
- 🎛️ Paplašinātā filtrēšana pēc 6 kritērijiem vienlaikus
- 📍 Tuvākās aptiekas ar Google Maps saiti
- 💰 Cenu salīdzināšana pa aptiekām
- 🗺️ Reālās aptiekas no OpenStreetMap Nominatim API
- ❤️ Favorītu saraksts reģistrētiem lietotājiem
- 📦 Zāļu komplekti — izveidot un pārvaldīt savus komplektus
- 🛵 Pasūtīt uz mājām (drīzumā)
- 👤 Lietotāju reģistrācija ar lomu pārvaldību (admin/user/pharmacy_rep)
- 🔐 Aizsargāti maršruti
- 🛠️ Administrācijas panelis — CRUD zāļu pārvaldībai (tikai admin)
- 📊 Statistikas lapa ar datu vizualizāciju un lietotāju sarakstu (tikai admin)
- �� Responsīvs dizains — darbojas gan datorā, gan mobilajā ierīcē
- 🌙 Dark/Light mode

---

## 🗄️ Datubāze (MySQL 8.0)

8 tabulas: `users`, `medicines`, `pharmacies`, `availability`, `search_history`, `notifications`, `favorites`, `medicine_sets`, `medicine_set_items`

---

## 👤 Testa lietotāji

| Loma | E-pasts | Parole |
|------|---------|--------|
| Admin | admin@aptiekasmap.lv | admin123 |
| Lietotājs | janis@gmail.com | user123 |
| Aptieka | benu@benu.lv | pharm123 |

---

## 📁 Projekta struktūra
aptiekasmap/
├── frontend/              # Vue.js 3 + Vuetify 3
│   ├── src/
│   │   ├── views/         # HomeView, PharmaciesView, ProfileView, AdminView, StatisticsView
│   │   ├── stores/        # Pinia auth store
│   │   └── App.vue        # Navigācija ar lomu pārvaldību
│   ├── public/
│   │   └── _redirects     # Netlify/Vercel SPA redirects
│   ├── .env.production    # Production API URL
│   └── vite.config.js
└── backend/               # Laravel 11
├── app/
│   ├── Http/Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/api.php

---

## ⚙️ Lokālā palaišana (GitHub Codespaces)

```bash
# 1. MySQL
sudo pkill -9 mysqld 2>/dev/null; sleep 1
sudo mkdir -p /var/run/mysqld && sudo chown mysql:mysql /var/run/mysqld
sudo mysqld_safe --user=mysql --skip-syslog &
sleep 5

# 2. Backend (Terminal 1)
cd aptiekasmap/backend
php artisan serve --host=0.0.0.0 --port=8001

# 3. Frontend (Terminal 2)
cd aptiekasmap/frontend
npm run dev
```

---

## �� Galvenie API endpointi

| Metode | URL | Apraksts |
|--------|-----|----------|
| GET | `/api/medicines` | Zāļu saraksts ar filtrēšanu |
| GET | `/api/pharmacies` | Aptieku saraksts |
| GET | `/api/availability/{id}` | Pieejamība konkrētām zālēm |
| GET | `/api/statistics` | Statistikas dati |
| POST | `/api/auth/register` | Reģistrācija |
| POST | `/api/auth/login` | Pieslēgšanās |
| GET | `/api/user/favorites` | Favorītu saraksts |
| POST | `/api/user/favorites` | Pievienot favorītam |
| GET | `/api/user/sets` | Komplektu saraksts |
| POST | `/api/user/sets` | Izveidot komplektu |
| POST | `/api/medicines` | Pievienot zāles (admin) |
| GET | `/api/admin/users` | Lietotāju saraksts (admin) |

---

© 2025/2026 Timurs Kvačovs — Rīgas Valsts Tehnikums
