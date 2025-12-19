# PHP_Laravel12_Comment_System_With_Reply

A complete Laravel 12 application demonstrating a **threaded comment system with unlimited nested replies**. This project focuses on hierarchical database relationships, authentication, authorization, and clean Laravel architecture following modern best practices.

---

## Project Overview

This project showcases how to build a real-world comment and reply system similar to blogs, forums, or discussion platforms. Users can post comments, reply to comments infinitely, and manage their own content securely.

The application is suitable for:

* Learning recursive relationships in Laravel
* Understanding self-referencing database tables
* Practicing authentication and authorization
* Interview and academic demonstrations

---

## Features

* Hierarchical comment system with unlimited nested replies
* Secure user authentication (login & registration)
* Create, view, reply, and delete comments
* Bootstrap modal-based reply UI (no page reload)
* Authorization rules (users can delete only their own comments)
* Recursive Blade templates for nested replies
* Responsive and mobile-friendly UI
* Clean and maintainable Laravel 12 structure

---

## Prerequisites

* PHP 8.1 or higher
* Composer
* MySQL 5.7 or higher
* Node.js and NPM

---

## Screenshot

<img width="1326" height="970" alt="Comment System Screenshot" src="https://github.com/user-attachments/assets/8f0b33ea-6a9b-45bb-ae0a-6f45e6560592" />

---

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-comment-system.git
cd laravel-comment-system
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_comment_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Development Server

```bash
php artisan serve
```

Visit:

```
http://localhost:8000
```

---

## Project Structure

```
laravel-comment-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── CommentController.php
│   │   └── HomeController.php
│   └── Models/
│       └── Comment.php
├── database/
│   ├── migrations/
│   │   └── create_comments_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── home.blade.php
│   └── partials/
│       └── comment.blade.php
├── routes/web.php
└── README.md
```

---

## Database Schema

### Comments Table

```sql
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    parent_id INT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
);
```

---

## Routes & Endpoints

| Method | Endpoint             | Description          |
| ------ | -------------------- | -------------------- |
| GET    | /                    | Display all comments |
| POST   | /comments            | Store a new comment  |
| POST   | /comments/{id}/reply | Reply to a comment   |
| DELETE | /comments/{id}       | Delete a comment     |

---

## Test Login Account

Default seeded user:

* Email: [test@example.com](mailto:test@example.com)
* Password: password

---

## Key Concepts Implemented

### Recursive Relationships

* Self-referencing `parent_id` column
* `Comment` model defines `replies()` relationship

### Eager Loading

* Prevents N+1 query issues
* Efficient loading of users and nested replies

### Blade Recursion

* Reusable partial views
* Recursive rendering of child comments

### Authorization

* Route protection using middleware
* Users can delete only their own comments

---

## Customization

### Change Reply Depth Logic

Edit `HomeController.php`:

```php
$comments = Comment::whereNull('parent_id')
    ->with(['user', 'replies.user', 'replies.replies.user'])
    ->get();
```

### Add Validation Rules

In `CommentController.php`:

```php
$request->validate([
    'body' => 'required|string|min:3|max:2000',
]);
```

### Change Comment Sorting

```php
// Oldest first
->orderBy('created_at', 'asc');

// Most replied comments first
->withCount('replies')->orderBy('replies_count', 'desc');
```

---

## Performance Tips

* Use eager loading to avoid N+1 queries
* Add indexes for better performance:

```php
$table->index(['parent_id', 'created_at']);
```

* Enable pagination for large datasets:

```php
$comments = Comment::whereNull('parent_id')->paginate(20);
```

---

## Troubleshooting

* **Model not found**: Run `composer dump-autoload`
* **Database error**: Verify `.env` credentials
* **Assets not loading**: Run `npm run build`
* **Auth issues**: Clear cache using `php artisan optimize:clear`

---

## Deployment Notes

```bash
APP_ENV=production
APP_DEBUG=false
```

Optimize application:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Maintenance Commands

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

Reset database:

```bash
php artisan migrate:fresh --seed
```

available under the MIT License.
