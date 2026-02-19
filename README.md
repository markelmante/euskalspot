# 🌊 EuskalSpot - Full-Stack Project

**EuskalSpot** es una plataforma interactiva diseñada para descubrir y gestionar los mejores spots de surf y rutas de trekking en Euskadi. Incluye mapas interactivos, datos meteorológicos en tiempo real y un panel de administración completo.

---

## 🏗️ 1. Arquitectura y Flujo del Sistema

La aplicación utiliza una arquitectura de **microservicios orquestada con Docker**, lo que garantiza un entorno aislado, seguro y escalable.


### Flujo de peticiones:
1.  **Navegador (Cliente):** Realiza una petición a `http://localhost`.
2.  **Proxy Inverso (Nginx):** Recibe la petición en el puerto 80.
3.  **Enrutamiento:**
    * Si la ruta es `/`, el Proxy sirve el contenido estático desde el contenedor **Frontend**.
    * Si la ruta es `/api`, `/admin`, `/login` o cualquier otra dinámica, el Proxy delega la petición al contenedor **Backend**.
4.  **Servicios Privados:** El contenedor de la **Base de Datos (MySQL)** no tiene puertos abiertos al exterior; solo es accesible por el Backend a través de la red interna de Docker.

---

## 📦 2. Servicios del Despliegue

| Servicio | Contenedor | Función | Puerto Externo |
| :--- | :--- | :--- | :--- |
| **Proxy** | `euskalspot-proxy` | Proxy inverso (Nginx) y enrutador. | 80 / 443 |
| **Frontend**| `euskalspot-front` | Servidor estático para la Landing Page. | Interno |
| **Backend** | `euskalspot-app` | Lógica de negocio (Laravel / PHP-FPM). | Interno |
| **DB** | `euskalspot-db` | Persistencia de datos (MySQL 8.0). | Interno |
| **PMA** | `phpmyadmin` | Gestión visual de la base de datos. | 8080 |

---

## 🚀 3. Guía de Despliegue Rápido

### Requisitos Previos
* Docker  instalado.
* Git para clonación.

### Pasos para el arranque
1.  **Clonar y Configurar:**
    ```bash
    git clone [https://github.com/markelmante/euskalspot](https://github.com/markelmante/euskalspot)
    cd euskalspot
    creamos .env y pegamos lo siguiente:

APP_NAME=EuskalSpot
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost


LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug


# ==========================================
# CONFIGURACIÓN DE BASE DE DATOS 
# ==========================================
# DB_HOST debe ser el nombre del servicio en docker-compose ('db')
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=admin
DB_PASSWORD=2daw3


# Drivers de sesión y caché
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120


# Configuración de correo
MAIL_MAILER=log
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hola@euskalspot.com"
MAIL_FROM_NAME="${APP_NAME}"


# Configuración de AWS/S3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false


# Vite (Frontend)
VITE_APP_NAME="${APP_NAME}"
    ```
2.  **Levantar Infraestructura:**
    ```bash
    docker compose up -d --build
    ```
3.  **Configuración de Aplicación (Post-instalación):**
    ```bash
    # Instalar dependencias y generar clave
    docker compose exec backend composer install
    docker compose exec backend php artisan key:generate

    # Ejecutar base de datos y Storage
    docker compose exec backend php artisan migrate --seed
    docker compose exec backend php artisan storage:link
    
    # Compilar assets de Frontend
    docker compose exec node npm install && npm run build
    ```

---

## 🔐 4. Seguridad y Entornos

### Variables de Env (.env)
El sistema utiliza un archivo `.env` para gestionar credenciales sensibles. Las más críticas para el despliegue son:
* `DB_HOST=db`: Apunta al nombre del servicio en el `docker-compose.yml`.
* `APP_ENV`: Cambiar de `local` a `production` en despliegues reales.

### HTTPS (Seguridad)
El Proxy está configurado para soportar tráfico cifrado mediante un **certificado autofirmado** gestionado en el contenedor Nginx.

---

## 🛠️ 5. Manual de Rutas Principales

### Rutas Web (Blade Views)
* `GET /`: Landing Page (Frontend Estático).
* `GET /explorar`: Mapa interactivo y buscador de spots.
* `GET /admin/panel`: Panel de control de administrador.
* `GET /tutorial`: Vídeo guía para administradores (HTML5 Video).

### Rutas API (JSON)
* `GET /api/reviews`: Devuelve 3 reseñas aleatorias.

---

**Autor:** Markel Manterola  
**Proyecto:** Final 2º DAW - Desarrollo de Aplicaciones Web.