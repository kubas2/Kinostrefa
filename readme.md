# 🎬 Kinostrefa

**Kinostrefa** to webowy system zarządzania kinem umożliwiający przeglądanie repertuaru, rezerwację miejsc, ocenianie filmów oraz administracyjne zarządzanie treścią i użytkownikami.

Projekt został zrealizowany w technologii **PHP + MySQL** z wykorzystaniem architektury opartej na relacyjnej bazie danych i mechanizmach kontroli dostępu opartych na rolach.

---

## 📌 Główne funkcjonalności

### 👤 System użytkowników
- Rejestracja i logowanie
- Haszowanie haseł (`password_hash`, `password_verify`)
- Sesje PHP
- Role użytkowników:
  - `Standard User`
  - `Administrator`

---

### 🎥 Zarządzanie filmami
- Dodawanie nowych filmów (tytuł, reżyser, opis, czas trwania)
- Obsługa plakatów
- Dynamiczne wyświetlanie repertuaru
- Sortowanie według średniej oceny użytkowników

---

### 🗓 Zarządzanie seansami
- Planowanie seansów (data, sala, cena)
- Powiązanie filmu z konkretnym wydarzeniem (screening)
- Automatyczne wyświetlanie dostępnych terminów na stronie filmu

---

### 💺 System rezerwacji miejsc
- Graficzna mapa sali
- Dynamiczne oznaczanie miejsc:
  - dostępne
  - zajęte
  - wybrane
- Zabezpieczenie przed podwójną rezerwacją (UNIQUE KEY w bazie)
- Obsługa statusów rezerwacji (`zarezerwowana`, `oplacona`)

---

### ⭐ System ocen i recenzji
- Ocena filmu w skali 1–10
- Tekstowa opinia użytkownika
- Dynamiczne obliczanie średniej (`AVG()`)
- Wyświetlanie wszystkich opinii pod filmem

---

### 🛠 Panel Administratora
- Dodawanie filmów
- Dodawanie seansów
- Przegląd użytkowników
- Usuwanie kont
- Zarządzanie statusami rezerwacji
- Podgląd szczegółów użytkownika

---

## 🧱 Architektura systemu

System oparty jest na relacyjnej bazie danych.

### Główne encje:

- `users`
- `filmy`
- `plakaty`
- `seanse`
- `siedzenia`
- `rezerwacje`
- `recenzje`

---

### Relacje między tabelami



(PLAN)

1. Rezerwacja filmów
2. Wyświetlanie foteli sali kinowej, zajęte i wolne. Podświetlanie aktualnie wybranych
3. Dodawanie filmów ADMIN
4. Zwalnianie filmów 30 min przed seansem
    5. Sekcja polecane filmy
6. Najlepsze filmy, sortowane recenzjami, które będą mogli dodawać użytkownicy
7. Tworzenie, usuwanie konta
8. Panel ADMIN: edytowanie filmów, dodawanie seansów, zarządzanie recenzjami i rezerwacjami
9  Możliwość odwołania rezerwacji