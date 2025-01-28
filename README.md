# Проект: Publishing House

Веб-приложение для управления данными издательства с разделением на frontend и backend.

## Технологический стек
- **Backend**: PHP с использованием фреймворка Yii2.
- **Frontend**: Nuxt.js (Vue.js).

## Основные модели
- **Authors** – информация об авторах.
- **Books** – данные о книгах.
- **Books_Authors** – связь книг с авторами.
- **Books_Genres** – связь книг с жанрами.
- **Genres** – перечень жанров.
- **User** – пользователи системы.

## Функциональность
1. **API-контроллеры**:
   - `BooksController` – управление данными о книгах.
   - `AuthorsController` – управление данными об авторах.
   - `GenresController` – управление жанрами.
   - `UserController` – управление пользователями.
   - `AuthController` – управление процессами аутентификации и авторизации пользователей.
2. **Авторизация и регистрация**:
   - Используется JWT-токен для защиты доступа.
3. **Пагинация, сортировка и фильтрация**:
   - Поддерживаются для удобного отображения и управления данными.
4. **Обработка ошибок**:
   - Корректная валидация входных данных.
   - Возврат информативных ошибок и соответствующих HTTP-кодов.


## API документация
https://documenter.getpostman.com/view/33548084/2sA2xnx9xR 
