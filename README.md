
## To start the project:

### Backend:

> Please change current folder: `cd backend`

First, run the development server:

`composer install`
Input database|mail config to ".env"

  ```bash
  php artisan key:generate
  php artisan jwt:secret
  php artisan migrate
  php artisan db:seed
  php artisan serve --host=localhost --port=8080
  ```
To start the project with Docker use ".env.docker" and "\config\database.docker.php" files

### Frontend:

> Please change current folder: `cd frontend`

```bash
   npm install
   npm run dev
```
