# CASE STUDY BACKEND

##

- php ^8.2 (8.4.1 recommended)
- laravel ^11

## Instructions

- Install docker
- Install php (refer to the versions above)
- Install laravel (refer to the versions above)

## Clone and run the project

- Navigate your way into your preferred project directory
- git clone <https://github.com/JuliusWinston/case-study-api.git>
- Run the command 'git pull origin main'

## COMMANDS (in the project's root folder)

- docker compose up --build --detach
- docker compose exec app bash
- php artisan migrate
- php artisan news:fetch
