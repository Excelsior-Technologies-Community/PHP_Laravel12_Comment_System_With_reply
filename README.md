# PHP_Laravel12_Comment_System_With_reply

A complete Laravel 12 application featuring a threaded comment system with infinite nesting capabilities. This project demonstrates hierarchical data relationships, user authentication, and modern Laravel best practices.

---

## Features

* Hierarchical Comment System with unlimited nested replies
* User Authentication with secure login and registration
* CRUD Operations for comments (create, read, delete)
* Real-time UI using Bootstrap modal for replies without page reload
* Responsive and mobile-friendly interface
* Authorization rules to ensure users can delete only their own comments
* Clean and maintainable Laravel architecture

---

## Prerequisites

* PHP 8.1 or higher
* Composer
* MySQL 5.7 or higher
* Node.js and NPM (for frontend assets)

---

## Screenshot

<img width="1326" height="970" alt="image" src="https://github.com/user-attachments/assets/8f0b33ea-6a9b-45bb-ae0a-6f45e6560592" />



## Installation

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

### 5. Run Development Server

```bash
php artisan serve
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## Project Structure

```
laravel-comment-system/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CommentController.php
│   │       └── HomeController.php
│   └── Models/
│       └── Comment.php
├── database/
│   ├── migrations/
│   │   └── create_comments_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── home.blade.php
│       └── partials/
│           └── comment.blade.php
├── routes/
│   └── web.php
└── README.md
```

---

## Database Schema

### Comments Table

```sql
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
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

## API Endpoints

| Method | Endpoint             | Description          |
| ------ | -------------------- | -------------------- |
| GET    | /                    | Display all comments |
| POST   | /comments            | Store a new comment  |
| POST   | /comments/{id}/reply | Reply to a comment   |
| DELETE | /comments/{id}       | Delete a comment     |

---

## Testing Account

Default seeded user:

* Email: [test@example.com](mailto:test@example.com)
* Password: password

---

## Key Concepts Implemented

### 1. Recursive Relationships

* Comments support parent-child relationships
* Self-referential relationship defined in the Comment model

### 2. Eager Loading

* Optimized database queries using `with()`
* Efficient loading of user and reply relationships

### 3. Blade Templates

* Reusable comment partials
* Recursive Blade rendering for nested replies

### 4. Authorization

* Middleware-protected routes
* Users can delete only their own comments

---

## Customization

### Change Maximum Reply Depth

Edit `HomeController.php`:

```php
$comments = Comment::whereNull('parent_id')
    ->with(['user', 'replies.user', 'replies.replies.user'])
    ->get();
```

### Add Comment Validation Rules

Modify validation in `CommentController.php`:

```php
$request->validate([
    'body' => 'required|string|min:3|max:2000',
]);
```

### Change Comment Sorting

```php
// Oldest first
->orderBy('created_at', 'asc')

// Most replies first
->withCount('replies')->orderBy('replies_count', 'desc')
```

---

## Troubleshooting

### Common Issues

* **Class 'Comment' not found**

  * Run `composer dump-autoload`
  * Verify namespace in the Comment model

* **Database connection error**

  * Check `.env` credentials
  * Ensure MySQL service is running

* **CSS or JS not loading**

  * Run `npm run build`
  * Check public directory permissions

* **Authentication issues**

  * Run `php artisan cache:clear`
  * Clear browser cookies

---

## Performance Considerations

* Prevents N+1 queries using eager loading
* Add database indexes for optimization:

```php
$table->index(['parent_id', 'created_at']);
```

* Use pagination for large datasets:

```php
$comments = Comment::whereNull('parent_id')->paginate(20);
```

---

## Deployment

### Production Configuration

```bash
APP_ENV=production
APP_DEBUG=false
```

### Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Optional Queue Setup

* Configure queues for email notifications
* Use Supervisor to manage queue workers

---

## Maintenance

### Clear Cache

```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

### Reset Database

```bash
php artisan migrate:fresh --seed
```

---

## License

This project is open-source and available under the MIT License.
