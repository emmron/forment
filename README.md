# Formet

A Formspring clone built with Laravel - an anonymous Q&A social platform.

## Features

- User profiles with unique usernames (`@username`)
- Ask questions anonymously or with your identity
- Answer questions publicly on your profile
- Follow other users
- Activity feed from followed users
- Toggle anonymous questions on/off

## Requirements

- PHP 8.2+
- Composer
- SQLite (or MySQL/PostgreSQL)

## Installation

1. Clone the repository:
```bash
cd formet
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Create the database:
```bash
touch database/database.sqlite
```

6. Run migrations:
```bash
php artisan migrate
```

7. Start the development server:
```bash
php artisan serve
```

8. Visit `http://localhost:8000` in your browser.

## Usage

1. Register a new account with a unique username
2. Share your profile URL (`http://localhost:8000/@yourusername`)
3. Receive questions from others
4. Answer questions from your inbox
5. Follow other users to see their answers in your feed

## Routes

- `/` - Home/landing page
- `/@username` - User profile
- `/inbox` - Your unanswered questions
- `/feed` - Activity from followed users
- `/settings` - Edit your profile
- `/questions/{id}` - Single Q&A view

## License

MIT
