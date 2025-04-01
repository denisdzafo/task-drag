# Laravel 11 Project

## Requirements
- **PHP**: 8.3+
- **Laravel**: 11
- **Node.js**: 18+
- **Composer**: Latest version
- **Docker** (if using Docker setup)

## Installation

1. **Clone the Repository**
   ```sh
   git clone https://github.com/denisdzafo/task-drag
   cd task-drag
   ```

2. **Configure Environment**
    - Copy the example environment file:
      ```sh
      cp .env.example .env
      ```
    - Update `.env` with your database and application settings.

3. **Install Dependencies**
   ```sh
   composer install
   ```

4. **Generate Application Key**
   ```sh
   php artisan key:generate
   ```

5. **Run Migrations**
   ```sh
   php artisan migrate
   ```

## Running the Application

### Using Local Environment
```sh
php artisan serve
```

### Using Docker
1. **Build and Start Containers**
   ```sh
   docker-compose up -d --build
   ```
2. **Run Migrations Inside the Container**
   ```sh
   docker-compose exec app php artisan migrate
   ```

The application should now be running at `http://localhost:8000`.


## License
This project is licensed under the MIT License.

