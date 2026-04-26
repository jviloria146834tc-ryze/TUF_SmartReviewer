<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Project Setup Guide

Follow these steps to clone and run the project locally.


### 1. Install Dependencies

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

### 2. Setup Environment File

Copy the example environment file:

```bash
copy .env.example .env
```

Then open `.env` and configure your database and other settings if needed.

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations (if applicable)

```bash
php artisan migrate
```

### 5. Build Frontend Assets

```bash
npm run dev
```

### 6. Prepare the Application (for Tailwind CSS)

```bash
npm run build
```

### 7. Run the Application

```bash
php artisan serve
```

Open your browser and go to:

```
http://127.0.0.1:8000
```


---