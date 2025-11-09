## Blog API

A RESTful API built with Laravel that provides endpoints for managing user, blog posts, categories, tags, and comments. This API supports full CRUD operations and includes authentication and authorization to ensure access to permitted actions only.

---
**Project Setup**

 - Clone the repository
   
    `git clone https://github.com/vbuddhacharya/laravel-blog-api.git`
 - Change directory
   
    `cd laravel-blog-api`
 - Install dependencies
   
	`composer install`
 - Create .env file
   
	`cp .env.example .env`
 - Generate app key
   
    `php artisan key:generate`
 - Run migrations
   
	`php artisan migrate`
 - Run seeders
   
	`php artisan db:seed`
 - Start the server
   
    `php artisan serve`

---
**API Documentation**

You can explore and test endpoints using Postman.

Public docs available [here](https://documenter.getpostman.com/view/34460698/2sB3WsQzyn)

---
**Assumptions made**

 - Authorization managed using roles and policies. Basic roles implemented using enums. Routes are restricted using the policy middleware along with auth checks in each controller method.
 - Added soft deletes for future possibility of implementing audit trails.
 - Added global search for posts along with search by filtering each relationship title attribute.
 - Seeder data included for all tables for demonstration purposes.
