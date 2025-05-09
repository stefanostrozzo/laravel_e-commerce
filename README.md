<h1>E-commerce Platform Built with Laravel 11</h1>
<br>
Welcome to this Laravel 11 E-commerce shop. This platform is built with Laravel 11 framework, offering a clean architecture, fast performance, and robust features for managing products, brands, customers.
<br><br>

<h3>🚀 Admin Panel</h3>
    
- Manage categories, products, and brands with ease.
- Customize home page sliders to showcase special products.
- User-friendly interface for managing orders and creating coupon codes.
![image](https://github.com/user-attachments/assets/272b570c-29f9-4fce-8d29-62cd1d93880a)

<hr>

<h3>💼 Customer Management</h3>

- A smooth and intuitive shopping experience for users.
- Account management, including login, registration, and profile picture editing.
- Users can browse, search, and filter products by category, price, and more.
![image](https://github.com/user-attachments/assets/52f5c65f-1240-4f6a-9640-43db3f3c37ed)

<hr>

<h3>🛒 Cart & Checkout</h3>

- Seamless cart and whishlist management.
- Checkout with possibility to add coupons with shipping management
![image](https://github.com/user-attachments/assets/10309d66-103b-498c-931e-f17b165f3767)

<hr>

<h3>🛠 Tech Stack</h3>

- Backend: Laravel 11
- Frontend: HTML, CSS, JavaScript
- Database: MySQL
- Tools: Bootstrap, jQuery, SweetAlert, ApexCharts

<hr>

<h3>📦 Installazione</h3>

<h4>Clone repository:</h4>

```sh
    git clone https://github.com/stefanostrozzo/laravel_e-commerce.git
    cd laravel_e-commerce
```

<h4>Install dependencies:</h4>

```sh
    composer install
    npm install
```

<h4>Configure the environment file:</h4>

```sh
    cp .env.example .env
    php artisan key:generate
```

<h4>Set up the database and run migrations:</h4>

```sh
    php artisan migrate
    php artisan db:seed
```

<h4>Start the server:</h4>

```sh
    npm run dev
    php artisan serve
```

<h3> Docker</h3>

After editing the docker-compose.yaml based on your .env and eventually the nginx.conf run the following commands:

```sh
    docker-compose up -d --build
    docker-compose run --rm composer install
    docker-compose run --rm node npm install
    docker-compose exec php php artisan key:generate
    docker-compose exec php chown -R www-data:www-data /var/www/html/storage
    docker-compose exec php chmod -R 775 /var/www/html/storage
    docker-compose exec php php artisan migrate
    docker-compose exec php php artisan db:seed
```

## **Usage**

-   Add items to wishlist/shop
-   Explore categories
-   Write commets on the Contact us page
-   View live updates of order
-   Make an admin user by setting in the user table the field "type" to "ADM" and manage products/slides/categories/coupons
