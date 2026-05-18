# 💊 AptiekasMap

> Tīmekļa platforma zāļu pieejamības noteikšanai tuvākajās aptiekās

** Timurs Kvačovs  
** Rīgas Valsts Tehnikums — Datorikas nodaļa  
** Datu bāzu programmēšana  
** 2025/2026

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
| Ārējais API | Wikipedia Latvian API |
| Frontend hosting | Vercel |
| Backend hosting | Railway.app |

---

## ✨ Funkcionalitāte

- 🔍 Zāļu meklēšana pēc nosaukuma vai aktīvās vielas (pieejams arī viesiem)
- 🎛️ Paplašinātā filtrēšana pēc 6 kritērijiem vienlaikus
- 📍 Tuvākās aptiekas ar Google Maps saiti
- 💰 Cenu salīdzināšana pa aptiekām
- 🗺️ Zāļu apraksts ar Wikipedia api palidzību
- ❤️ Favorītu saraksts reģistrētiem lietotājiem
- 📦 Zāļu komplekti — izveidot un pārvaldīt savus komplektus
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
