# 💊 AptiekasMap

> Tīmekļa platforma zāļu pieejamības noteikšanai tuvākajās aptiekās

** Timurs Kvačovs  
** Rīgas Valsts Tehnikums — Datorikas nodaļa  
** 2025/2026

---

## 🌐 Publiskā piekļuve

| Resurss | URL |
|---------|-----|
| 🖥️ Tīmekļa vietne | [aptiekas-map.netlify.app](https://aptiekas-map.netlify.app) |
| ⚙️ Backend API | [majaslapa3kurss-production.up.railway.app](https://majaslapa3kurss-production.up.railway.app) |
| 📁 GitHub | [github.com/23DP3TKvac/Majaslapa_3kurss](https://github.com/23DP3TKvac/Majaslapa_3kurss) |

---

## 📋 Par projektu

AptiekasMap ir tīmekļa platforma, kas ļauj lietotājiem ātri atrast nepieciešamās zāles tuvākajās Rīgas aptiekās, salīdzināt to cenas un pieejamību reāllaikā.

### Problēma
Latvijā nav vienotas platformas, kas apvienotu dažādu aptieku datus vienā vietā. Esošie risinājumi (Zāles.lv, Apteka.lv, BENU) piedāvā tikai sava tīkla informāciju.

---

## �� Tehnoloģiju steks

| Slānis | Tehnoloģija |
|--------|-------------|
| Frontend | Vue.js 3 + Vuetify 3 + Pinia |
| Backend | Laravel 11 (PHP 8.4) |
| Datubāze | MySQL 8.0 |
| Autentifikācija | Laravel Sanctum (bcrypt) |
| Frontend hosting | Netlify |
| Backend hosting | Railway.app |

---

## ✨ Funkcionalitāte

- 🔍 Zāļu meklēšana pēc nosaukuma vai aktīvās vielas
- 🎛️ Paplašinātā filtrēšana pēc 6 kritērijiem vienlaikus
- 📍 Tuvākās aptiekas ar Google Maps saiti
- 💰 Cenu salīdzināšana pa aptiekām
- 🗺️ Reālās aptiekas no OpenStreetMap API
- 👤 Lietotāju reģistrācija ar lomu pārvaldību (admin/user/pharmacy_rep)
- 🔐 Aizsargāti maršruti — saturs tikai reģistrētiem lietotājiem
- 🛠️ Administrācijas panelis — CRUD zāļu pārvaldībai (tikai admin)
- 📊 Statistikas lapa ar datu vizualizāciju un lietotāju sarakstu (tikai admin)
- 🌙 Dark/Light mode

---

## 🗄️ Datubāze (MySQL 8.0)

6 tabulas: `users`, `medicines`, `pharmacies`, `availability`, `search_history`, `notifications`

---

## 📡 Galvenie API endpointi

| Metode | URL | Apraksts |
|--------|-----|----------|
| GET | `/api/medicines` | Zāļu saraksts ar filtrēšanu |
| GET | `/api/medicines/forms` | Pieejamās zāļu formas |
| GET | `/api/pharmacies` | Aptieku saraksts |
| GET | `/api/availability/{id}` | Pieejamība konkrētām zālēm |
| GET | `/api/statistics` | Statistikas dati |
| POST | `/api/auth/register` | Reģistrācija |
| POST | `/api/auth/login` | Pieslēgšanās |
| POST | `/api/medicines` | Pievienot zāles (admin) |
| PUT | `/api/medicines/{id}` | Labot zāles (admin) |
| DELETE | `/api/medicines/{id}` | Dzēst zāles (admin) |
| GET | `/api/admin/users` | Lietotāju saraksts (admin) |

---

© 2025/2026 Timurs Kvačovs — Rīgas Valsts Tehnikums
