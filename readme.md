# VUE-COMMERCE
A simple e-commerce project example made with Laravel 5.8 and Vue.js 2

## Installation

### 1. Step
Clone this repository to a folder in your documents
```shell
git clone https://github.com/iagoqueiroz/vue-commerce.git && cd vue-commerce
```

### 2. Step
Installing dependences
```shell
composer install
npm install
```

### 3. Step
Create a database of your choice and set the variables in `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vuecommerce
DB_USERNAME=root
DB_PASSWORD=secret
```

### 4. Step
Create a symbolic link of the storage folder
```shell
php artisan storage:link
```

### 5. Step
Generate the JWT secret that will be used to encrypt the application token
```shell
php artisan jwt:secret
```

### 6. Step
Execute the migrations and seed the database
```shell
php artisan migrate --seed
```

### 7. Step
Run the local server and access the url of the project `http://localhost:8000`
```shell
php artisan serve
```
