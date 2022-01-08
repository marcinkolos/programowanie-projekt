## O Programowanie-projekt

Jest to projekt wykonany przez Marcina Kołosa i Marcina Laseckiego w ramach zaliczenia przedmiotu Programowanie
zaawansowane. Projektem jest strona internetowa wykonana, zgodnie z założeniami, w laravelu.

## Instalacja

Utwórz bazę danych programowanie

```
mysql -u <username> -p
create database programowanie;
exit
```

Wewnątrz projektu wykonaj komendy

```
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

### Informacje odnośnie seed'a

Ostatnia komenda (migrate:fresh --seed) uzupełni nam bazę danymi. Hasło do użytkowników z bazy to "password"

## Uruchomienie

Uruchom serwer lokalny

```
php artisan serve
```
