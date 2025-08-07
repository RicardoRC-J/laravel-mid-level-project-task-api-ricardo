Laravel Mid-Level Project Task API - RICARDO REGALADO
📋 Requisitos del Sistema
PHP >= 8.1
Composer >= 2.0
MySQL >= 8.0 o PostgreSQL >= 13
Laravel >= 10.0
🛠 Instalación Paso a Paso

1. Clonar el repositorio
   bash
   git clone https://github.com/RicardoRC-J/laravel-mid-level-project-task-api-ricardo.git
   cd laravel-mid-level-project-task-api-ricardo
2. Instalar dependencias
   bash
   composer install
3. Configurar archivo .env
   bash
   cp .env.example .env
   php artisan key:generate
   Configurar base de datos en .env:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_mid_level_project_task_api_ricardo
DB_USERNAME=root
DB_PASSWORD=tu_password

TELESCOPE_ENABLED=true 4. Crear base de datos
sql
CREATE DATABASE project_task_api; 5. Ejecutar migraciones
bash
php artisan migrate 6. Generar documentación Swagger
bash
php artisan l5-swagger:generate 7. Levantar el servidor
bash
php artisan serve
📚 Cómo Levantar Swagger
Asegurar que el servidor esté corriendo: php artisan serve
Generar documentación: php artisan l5-swagger:generate
Visitar: http://localhost:8000/api/documentation
🔭 Cómo Ver Telescope
Visitar: http://localhost:8000/telescope
Monitorear en tiempo real:
Requests HTTP
Queries a la base de datos
Jobs y queues
Exceptions
Cache hits/misses
🎯 Cómo Probar Filtros Dinámicos
Proyectos (/api/projects)
bash

# Filtrar por estado

GET /api/projects?status=active

# Filtrar por nombre (búsqueda parcial)

GET /api/projects?name=Mi

# Filtrar por rango de fechas

GET /api/projects?created_from=2024-01-01&created_to=2024-12-31

# Combinar filtros

GET /api/projects?status=active&name=proyecto&created_from=2024-08-01
Tareas (/api/tasks)
bash

# Filtrar por estado y prioridad

GET /api/tasks?status=pending&priority=high

# Filtrar por proyecto específico

GET /api/tasks?project_id=uuid-del-proyecto

# Filtrar por rango de fechas de vencimiento

GET /api/tasks?due_date_from=2024-08-01&due_date_to=2024-08-31

# Filtrar por título (búsqueda parcial)

GET /api/tasks?title=importante

# Combinar múltiples filtros

GET /api/tasks?status=in_progress&priority=high&project_id=uuid
📊 Cómo Ver Logs de Auditoría
Método 1: Usando Tinker
bash
php artisan tinker

# Ver auditorías de un proyecto

$project = App\Models\Project::first();
$project->audits;

# Ver auditorías de una tarea

$task = App\Models\Task::first();
foreach($task->audits as $audit) {
echo $audit->event . ' - ' . $audit->created_at . PHP_EOL;
}
Método 2: Consulta directa
bash

# En MySQL

SELECT \* FROM audits WHERE auditable_type = 'App\\Models\\Project';
Método 3: En Telescope
Ir a http://localhost:8000/telescope
Sección "Queries" para ver las consultas de auditoría
Sección "Models" para ver eventos de modelos
🚀 Endpoints Disponibles
Proyectos
Método Endpoint Descripción
GET /api/projects Listar proyectos con filtros
POST /api/projects Crear nuevo proyecto
GET /api/projects/{id} Obtener detalles de proyecto
PUT /api/projects/{id} Actualizar proyecto
DELETE /api/projects/{id} Eliminar proyecto
Tareas
Método Endpoint Descripción
GET /api/tasks Listar tareas con filtros
POST /api/tasks Crear nueva tarea
GET /api/tasks/{id} Obtener detalles de tarea
PUT /api/tasks/{id} Actualizar tarea
DELETE /api/tasks/{id} Eliminar tarea
📝 Ejemplos de Uso
Crear Proyecto
bash
curl -X POST http://localhost:8000/api/projects \
 -H "Content-Type: application/json" \
 -d '{
"name": "Mi Proyecto",
"description": "Descripción del proyecto",
"status": "active"
}'
Crear Tarea
bash
curl -X POST http://localhost:8000/api/tasks \
 -H "Content-Type: application/json" \
 -d '{
"project_id": "uuid-del-proyecto",
"title": "Mi Tarea",
"description": "Descripción de la tarea",
"status": "pending",
"priority": "high",
"due_date": "2024-12-31"
}'

📦 Packages Utilizados
owen-it/laravel-auditing: ^13.0 - Auditoría completa de modelos
darkaonline/l5-swagger: ^8.5 - Documentación OpenAPI/Swagger
laravel/telescope: ^4.15 - Monitoreo y debugging avanzado
✨ Características Implementadas
✅ Funcionalidades Core
API RESTful completa
Relación 1:N entre Proyectos y Tareas
UUIDs como primary keys
Validaciones estrictas con Form Requests
Mensajes de error personalizados
✅ Filtros Dinámicos Avanzados
Filtros por estado, nombre, fechas (Proyectos)
Filtros por estado, prioridad, proyecto, fechas (Tareas)
Búsquedas parciales de texto
Combinación de múltiples filtros
✅ Auditoría Completa
Tracking de creación, actualización y eliminación
Timestamps automáticos en auditoría
Configuración granular por modelo
✅ Documentación y Monitoreo
Swagger/OpenAPI con anotaciones
Laravel Telescope configurado
Documentación interactiva
✅ Arquitectura Limpia
Services para lógica de negocio
Resources para transformación de datos
Traits reutilizables
Separación clara de responsabilidades
🧪 Testing
bash

# Ejecutar tests

php artisan test

# Con coverage (si está configurado)

php artisan test --coverage
🚀 Comandos Útiles
bash

# Limpiar cache

php artisan optimize:clear

# Ver rutas registradas

php artisan route:list

# Regenerar documentación

php artisan l5-swagger:generate

# Ver logs en tiempo real

tail -f storage/logs/laravel.log

# Entrar a tinker para pruebas

php artisan tinker
🛡️ Validaciones Implementadas
Proyecto
name: requerido, único, 3-100 caracteres
description: opcional, texto
status: requerido, enum (active, inactive)
Tarea
project_id: requerido, UUID válido, existe en proyectos
title: requerido, 3-100 caracteres
description: opcional, texto
status: requerido, enum (pending, in_progress, done)
priority: requerido, enum (low, medium, high)
due_date: requerido, fecha, posterior a hoy (solo en creación)
📱 URLs Importantes
API Base: http://localhost:8000/api
Documentación Swagger: http://localhost:8000/api/documentation
Telescope: http://localhost:8000/telescope
Proyectos: http://localhost:8000/api/projects
Tareas: http://localhost:8000/api/tasks
👨‍💻 Desarrollado por
RICARDO REGALADO CHAVEZ - Prueba técnica Laravel Mid-Level
Tiempo de desarrollo: 2 horas
Fecha: Agosto 2024

🎯 Notas Adicionales
Todas las tablas usan UUIDs en lugar de IDs incrementales
Soft Deletes implementado en Proyectos
Auditoría completa en ambos modelos
Filtros dinámicos con Eloquent Scopes
Arquitectura modular y escalable
Documentación completa con Swagger
Monitoreo en tiempo real con Telescope
