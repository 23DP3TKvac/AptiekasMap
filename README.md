# 💊 AptiekasMap

> Tīmekļa platforma zāļu pieejamības noteikšanai tuvākajās aptiekās

**Autors:** Timurs Kvačovs  
**Skola:** Rīgas Valsts Tehnikums — Datorikas nodaļa  
**Modulis:** Datu bāzu programmēšana  
**Mācību gads:** 2025/2026

---

## 📋 Par projektu

AptiekasMap ir tīmekļa platforma, kas ļauj lietotājiem ērti un ātri atrast nepieciešamās zāles tuvākajās Rīgas aptiekās, salīdzināt cenas un pieejamību reāllaikā.

### Problēma
Latvijā nav vienotas platformas, kas apvienotu dažādu aptieku datus vienā vietā. Esošie risinājumi (Zāles.lv, Apteka.lv, BENU) piedāvā tikai daļēju informāciju.

### Risinājums
Vienota platforma ar reāllaika datiem, kartes integrāciju un cenu salīdzināšanu.

---

## 🚀 Tehnoloģiju steks

| Slānis | Tehnoloģija |
|--------|-------------|
| Frontend | Vue.js 3 + Vuetify 3 |
| Backend | Laravel 11 (PHP 8.2) |
| Datubāze | MySQL 8 |
| API | RESTful JSON API |
| Autentifikācija | Laravel Sanctum |

---

## ✨ Funkcionalitāte

- 🔍 **Zāļu meklēšana** — meklēšana pēc nosaukuma ar filtriem
- 📍 **Tuvākās aptiekas** — atrašana pēc koordinātēm
- 💰 **Cenu salīdzināšana** — visas aptiekas vienā skatā
- 🗺️ **Kartes integrācija** — Google Maps saites
- 👤 **Lietotāju reģistrācija** — profili un meklēšanas vēsture
- 🔔 **Paziņojumi** — brīdinājumi par pieejamības izmaiņām
- 🌙 **Dark/Light mode** — tēmas pārslēgšana

---

## 📁 Projekta struktūra

```
aptiekasmap/
├── backend/                  # Laravel projekts
│   ├── app/
│   │   ├── Http/Controllers/ # API kontrolieri
│   │   └── Models/           # Eloquent modeļi
│   ├── database/
│   │   └── migrations/       # DB migrācijas
│   ├── routes/
│   │   └── api.php           # API maršruti
│   └── .env                  # Konfigurācija
└── frontend/                 # Vue.js projekts
    ├── src/
    │   ├── components/       # Vue komponenti
    │   ├── views/            # Lapas
    │   ├── router/           # Vue Router
    │   └── stores/           # Pinia stores
    └── package.json
```

---

## ⚙️ Instalācija

### Prasības
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8 / XAMPP

### 1. Klonē repozitoriju
```bash
git clone https://github.com/YOUR_USERNAME/aptiekasmap.git
cd aptiekasmap
```

### 2. Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Konfigurē `.env`:
```
DB_DATABASE=aptiekas_db
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
php artisan serve
```
Backend: `http://localhost:8000`

### 3. Frontend (Vue + Vuetify)
```bash
cd ../frontend
npm install
npm run dev
```
Frontend: `http://localhost:5173`

---

## 🗄️ Datubāzes struktūra

| Tabula | Apraksts |
|--------|----------|
| `users` | Sistēmas lietotāji |
| `medicines` | Medikamenti |
| `pharmacies` | Aptiekas ar koordinātēm |
| `availability` | Zāļu pieejamība aptiekās |
| `search_history` | Meklēšanas vēsture |
| `notifications` | Paziņojumi lietotājiem |

---

## 📡 API Endpointi

| Metode | URL | Apraksts |
|--------|-----|----------|
| GET | `/api/medicines` | Visu zāļu saraksts |
| GET | `/api/medicines/search?q=name` | Meklēšana |
| GET | `/api/pharmacies` | Visu aptieku saraksts |
| GET | `/api/availability/{id}` | Pieejamība aptiekās |
| POST | `/api/auth/register` | Reģistrācija |
| POST | `/api/auth/login` | Pieslēgšanās |
| GET | `/api/user/history` | Meklēšanas vēsture |
