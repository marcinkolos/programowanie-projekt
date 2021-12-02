## O Programowanie-projekt

Jest to projekt wykonany przez Marcina Kołosa i Marcina Laseckiego w ramach zaliczenia przedmiotu Programowanie zaawansowane.
Projektem jest strona internetowa wykonana, zgodnie z założeniami, w laravelu.

## Instalacja

Dodaj bazę danych o nazwie programowanie

```
mysql -u username -p
create database programowanie;
exit
```

Zaimportuj do niej plik baza.sql

```
mysql -u username -p programowanie < scieżka/programowanie.sql
```

Wewnątrz projektu wykonaj komendy

```
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Uruchomienie

Uruchom serwer lokalny

```
php artisan serve
```
