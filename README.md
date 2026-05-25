# BiblioSmart - Sistema de Recomendación de Lectura

Sistema inteligente de recomendación de libros para bibliotecas. Analiza los intereses y nivel de lectura de cada participante para recomendar libros personalizados de una base de datos de **7,784 títulos** organizados en **50 categorías**.

## Características

- **Evaluación Inicial** — Los participantes completan una evaluación de intereses (nivel de lectura, edad, idioma, categorías favoritas)
- **Recomendaciones Personalizadas** — Motor de recomendación que genera 12 libros basados en el perfil del usuario
- **Evaluación de Lectura** — Sistema de evaluación de comprensión lectora con preguntas automáticas y manuales
- **Panel de Administración** — Gestión de preguntas de evaluación por libro
- **Catálogo Completo** — Búsqueda y filtrado por categoría, estado y texto
- **Dashboard** — Resumen de actividad de lectura con estadísticas

## Requisitos Previos

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **pnpm** >= 8.x (o npm)
- **MySQL** >= 8.0

## Instalación Local con MySQL

### 1. Clonar el repositorio

```bash
git clone https://github.com/ioezgamer/biblioteca-sistema.git
cd biblioteca-sistema
```

### 2. Instalar dependencias

```bash
composer install
pnpm install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Crear la base de datos MySQL

Abre tu terminal de MySQL y crea la base de datos:

```sql
CREATE DATABASE biblioteca_sistema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Si tu MySQL requiere usuario y contraseña, edita el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_sistema
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_aqui
```

### 5. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

Esto creará todas las tablas e importará los 7,784 libros con sus 50 categorías.

### 6. Compilar assets (Tailwind CSS)

```bash
pnpm run build
```

### 7. Iniciar el servidor

```bash
php artisan serve
```

Abre tu navegador en: **http://localhost:8000**

## Cuentas de Demostración

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@biblioteca.com | password |
| Participante | demo@biblioteca.com | password |

## Flujo del Sistema

```
Registro → Evaluación Inicial → Recomendaciones → Aceptar Libro → Leer → Evaluación de Comprensión
```

1. **Registro** — El participante se registra y es redirigido a la evaluación inicial
2. **Evaluación Inicial** — Selecciona nivel de lectura, grupo de edad, idioma y 3+ categorías de interés
3. **Recomendaciones** — El sistema genera 12 libros personalizados basados en el perfil
4. **Lectura** — El participante acepta un libro ("Comenzar a leer") y lo marca como completado
5. **Evaluación** — Responde 5 preguntas de comprensión. Necesita 60% para aprobar

## Desarrollo

Para desarrollo con hot-reload de Tailwind:

```bash
pnpm run dev
```

En otra terminal:

```bash
php artisan serve
```

## Stack Tecnológico

- **Backend:** Laravel 13 (PHP 8.3)
- **Frontend:** Blade + Tailwind CSS 4
- **Base de datos:** MySQL 8
- **Package Manager:** pnpm
- **Auth:** Laravel built-in authentication

## Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── Admin/EvaluationQuestionController.php  # Admin: gestión de preguntas
│   ├── Auth/                                    # Login y registro
│   ├── BookController.php                       # Catálogo y detalle de libros
│   ├── DashboardController.php                  # Dashboard del usuario
│   ├── InitialEvaluationController.php          # Evaluación inicial de intereses
│   ├── ReadingEvaluationController.php          # Evaluación de comprensión
│   └── RecommendationController.php             # Recomendaciones personalizadas
├── Models/                                      # Modelos Eloquent
├── Services/RecommendationService.php           # Motor de recomendación
database/
├── data/books.json                              # 7,784 libros de la biblioteca
├── migrations/                                  # Migraciones de la BD
└── seeders/BookSeeder.php                       # Importador de libros
resources/views/                                 # Vistas Blade + Tailwind
```

## Licencia

MIT
