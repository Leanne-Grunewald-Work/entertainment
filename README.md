# Entertainment Web App

A responsive Laravel-powered entertainment platform that allows users to explore trending and recommended movies and TV series using real-time data from [TMDb](https://www.themoviedb.org/).

**Live Site:**  
[https://entertainment.leannegrunewald.co.za](https://entertainment.leannegrunewald.co.za)

---

## Preview

![Screenshot of Entertainment Site](https://leannegrunewald.co.za/assets/img/project-entertainment.jpg)

---

## Features

- Browse trending and recommended media (via TMDb API)
- Logged-in users can bookmark movies and TV series — bookmarks are saved to the database and persist across devices
- Toggle between Movies and TV Series
- Fully responsive layout with dark theme
- User authentication (Laravel Breeze)
- Avatar upload via profile page
- Secure file handling with Laravel Storage
- Clean routing using Laravel 11 and Vite

---

## Tech Stack

- **Laravel 11** with Laravel Breeze (Blade)
- **TMDb API** for live media data
- **TailwindCSS** for styling
- **Vite** for asset bundling
- **PHP 8.2**, **MySQL**, **Live deployment** on Afrihost shared hosting

---

## Setup Instructions

```bash
git clone https://github.com/Leanne-Grunewald-Work/entertainment.git
cd entertainment

composer install
npm install
cp .env.example .env
php artisan key:generate

# Set your DB credentials in .env

php artisan migrate
npm run build
php artisan serve

#To fetch data from TMDb, you’ll need a free API key from https://www.themoviedb.org, and add it to your .env file:

TMDB_API_KEY=your_tmdb_key

```

## Future Enhancements (Ideas)

- Genre/category filters
- Media detail pages with cast/trailers
- Save user preferences (dark/light mode)

## About the Developer

Built by [Leanne Grunewald](https://leannegrunewald.co.za), a full-stack web developer with a love for creative, clean code and modern web experiences.

## Feedback & Contact

Feel free to [reach out](https://leannegrunewald.co.za/#contact), or message via the contact form on my portfolio.

