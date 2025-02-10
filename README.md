# DanceDiary

##  Opis projektu

**DanceDiary** to aplikacja internetowa pomagająca tancerzom w zarządzaniu harmonogramem lekcji tanecznych. Umożliwia użytkownikom wyszukiwanie trenerów, rezerwację zajęć z trenerami, przeglądanie dostępnych studiów oraz zarządzanie notatkami związanymi z treningami.

##  Funkcjonalności

✅ **Rejestracja i logowanie** - użytkownicy mogą tworzyć konta i logować się do aplikacji.\
✅ **Rezerwacje zajęć** - możliwość zapisu na lekcje z trenerem w wybranym studiu.\
✅ **Wyszukiwanie trenerów** - opcja filtrowania trenerów po imieniu i nazwisku lub nazwie studia.\
✅ **Zarządzanie notatkami** - użytkownicy mogą dodawać notatki dotyczące swoich treningów.\
✅ **Obsługa sesji** - użytkownik pozostaje zalogowany po przejściu między stronami, dopóki nie kliknie „Logout”.

##  Struktura katalogów

```
DanceDiary/
│── backend/                   # Backend aplikacji (PHP + PostgreSQL)
│   ├── api/                   # Endpointy REST API
│   │   ├── users.php          # Obsługa użytkowników (rejestracja, logowanie, pobieranie profili)
│   │   ├── reservations.php   # Obsługa rezerwacji
│   │   ├── trainers.php       # Wyszukiwanie trenerów
│   │   ├── studios.php        # Informacje o studiach
│   │   ├── notes.php          # Zarządzanie notatkami
│   ├── config/                # Pliki konfiguracyjne
│   │   ├── Database.php       # Połączenie z bazą danych
│   ├── models/                # Modele ORM dla aplikacji
│   │   ├── User.php           # Model użytkownika
│   │   ├── Reservation.php    # Model rezerwacji
│   │   ├── Studio.php         # Model studia
│   │   ├── Note.php           # Model notatek
│   ├── index.php              # Główny plik routera backendu
│
│── database/                  # Struktura bazy danych PostgreSQL
│   ├── schema.sql             # Tworzenie tabel
│   ├── functions.sql          # Funkcje SQL
│   ├── views.sql              # Widoki SQL
│   ├── triggers.sql           # Wyzwalacze
│   ├── transactions.sql       # Transakcje SQL
│
│── frontend/                  # Frontend aplikacji (HTML, CSS, JS)
│   ├── api.js                 # Obsługa komunikacji z backendem (Fetch API)
│   ├── app.js                 # Główna logika aplikacji
│   ├── index.html             # Strona główna
│   ├── login.html             # Strona logowania
│   ├── register.html          # Strona rejestracji
│   ├── dashboard.html         # Panel użytkownika
│   ├── reservations.html      # Strona rezerwacji zajęć
│   ├── trainers.html          # Wyszukiwarka trenerów
│   ├── styles.css             # Style aplikacji
```

##  Technologie

- **Frontend:** HTML5, CSS3, JavaScript (Fetch API)
- **Backend:** PHP (bez frameworka)
- **Baza danych:** PostgreSQL

##  Jak uruchomić projekt

### **1. Konfiguracja bazy danych**

1. Uruchom PostgreSQL.
2. Stwórz bazę danych:
   ```sh
   psql -U [twoj_user] -c "CREATE DATABASE dance_diary;"
   ```
3. Załaduj strukturę bazy danych:
   ```sh
   psql -U [twoj_user] -d dance_diary -f database/schema.sql
   psql -U [twoj_user] -d dance_diary -f database/functions.sql
   psql -U [twoj_user] -d dance_diary -f database/views.sql
   psql -U [twoj_user] -d dance_diary -f database/triggers.sql
   psql -U [twoj_user] -d dance_diary -f database/transactions.sql
   ```

### **2. Uruchomienie backendu**

1. Przejdź do katalogu backendu:
   ```sh
   cd backend
   ```
2. Uruchom wbudowany serwer PHP:
   ```sh
   php -S localhost:8000 -t .
   ```
3. Backend powinien być dostępny pod `http://localhost:8000/`

### **3. Uruchomienie frontend-u**

1. Otwórz `index.html` w przeglądarce **(lub uruchom serwer lokalny, np. z Pythonem):**
   ```sh
   cd frontend
   python3 -m http.server 8080
   ```
2. Otwórz `http://localhost:8080/` w przeglądarce.

##  Testowanie API (opcjonalnie)

Jeśli chcesz ręcznie testować API, użyj **Postmana** lub **cURL**.

### **Rejestracja użytkownika**

```sh
curl -X POST "http://localhost:8000/users?action=register" \
     -H "Content-Type: application/json" \
     -d '{"first_name": "Jan", "last_name": "Kowalski", "email": "jan@example.com", "password": "haslo123", "role": "tancerz"}'
```

### **Logowanie użytkownika**

```sh
curl -X POST "http://localhost:8000/users?action=login" \
     -H "Content-Type: application/json" \
     -d '{"email": "jan@example.com", "password": "haslo123"}'
```

##  Licencja

Projekt DanceDiary jest dostępny na licencji MIT.

##  Autorzy

- **Jakub Gędłek** - Główny twórca projektu



