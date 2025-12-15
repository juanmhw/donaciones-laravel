📑 DOCUMENTACIÓN DEL PROYECTO: SISTEMA DE GESTIÓN Y TRAZABILIDAD DE DONACIONES (SGTD)
🌟 1. Introducción y Alcance
El Sistema de Gestión y Trazabilidad de Donaciones (SGTD), desarrollado en Laravel, es una plataforma integral diseñada para administrar campañas, registrar donaciones (monetarias y en especie), y asegurar la transparencia total en la asignación y uso de los fondos.
El sistema se distingue por su robusta capacidad de integración con Gateways de API externos para la sincronización de campañas, la gestión de donaciones en especie y la trazabilidad logística de los bienes donados, desde su entrada al almacén hasta su destino final.
💻 2. Pila Tecnológica y Dependencias
Componente
Tecnología/Versión
Propósito
Framework
PHP Laravel
Backend principal, arquitectura MVC y lógica de negocio.
Base de Datos
PostgreSQL (Postgres)
Persistencia de datos transaccionales de alta integridad.
Contenedores
Docker / Docker Compose
Entorno de despliegue estandarizado y reproducible (Nginx, PHP-FPM, DB).
Autenticación/Roles
Spatie Laravel Permission
Manejo granular de roles (RoleController.php) y permisos de usuario.
Sincronización
Guzzle HTTP Client
Integración con servicios externos (APIs de Gateway).
Reportes
Maatwebsite Excel, Barryvdh DomPDF
Generación de reportes de cierres de caja y trazabilidad en formatos XLSX y PDF.

✨ 3. Módulos y Funcionalidades Clave
El SGTD está diseñado para cubrir el ciclo completo de la donación y su uso, incluyendo funcionalidades específicas para la transparencia y la logística de inventario.
3.1. Gestión Financiera y Campañas
Campañas: Creación, edición y control de campañas con metas de recaudación y fechas específicas (CampaniaController.php).
Donaciones: Registro de donaciones (DonacionController.php), diferenciando entre tipo DINERO y ESPECIE.
Saldos y Asignación: Control estricto de los saldos disponibles por cada donación (SaldosDonacionController.php). Permite asignar montos específicos a gastos o usos registrados (DonacionesAsignacionController.php).
Reportes: Generación de Reportes de Cierre de Caja (ReporteCierreCajaController.php) y Reportes Generales de Campañas.
3.2. Sincronización e Integración Externa
El sistema depende de comandos de consola programados para obtener datos de sistemas externos. Estos comandos son ejecutados por el Scheduler (app/Console/Kernel.php).
Recurso Sincronizado
Comando de Consola
Controlador API Receptor
Campañas
sync:campanias
ApiCampaniaSyncController.php
Donaciones (Dinero)
sync:donaciones-dinero
ApiDonacionSyncController.php
Logística/Almacén
sync:datos-externos
TrazabilidadSyncController.php
Paquetes/Trazabilidad
sync:gateway-paquetes
N/A (Consumo Directo)

3.3. Logística y Trazabilidad (Inventario en Especie)
Estructura de Almacenes: Sincroniza la estructura jerárquica de almacenes, estantes y espacios (Ext/AlmacenesEstructuraController.php).
Trazabilidad de Ítems: Permite consultar y generar reportes del ciclo de vida de los artículos donados en especie (Ext/TrazabilidadController.php), vinculando ítems a campañas y asignaciones.
3.4. Administración del Sistema
Control de Acceso: Gestión de roles y permisos a través de Spatie.
Centro de Mensajes: Módulo de comunicación interna para notificaciones y soporte (CentroMensajesController.php).
⚙️ 4. Guía de Puesta en Marcha
Se recomienda fuertemente el uso de Docker para el despliegue en producción y desarrollo para garantizar la uniformidad del entorno.
4.1. Despliegue Estándar (Usando Docker Compose)
Este método levanta todos los servicios (Nginx, PHP-FPM, DB, Scheduler) en contenedores aislados.
Requisitos: Docker y Docker Compose (v2+).
Clonar Repositorio:
Bash
git clone [URL_DEL_REPOSITORIO] donaciones-laravel
cd donaciones-laravel


Configuración del Entorno (.env):
Copie el archivo de ejemplo (cp .env.example .env). Asegúrese de configurar las URLs de los Gateways API (API_DONACIONES_URL y API_GATEWAY_URL).
Fragmento de código
DB_CONNECTION=pgsql
DB_HOST=db  # Debe coincidir con el nombre del servicio en docker-compose.yml
# ... otros parámetros de BD
API_DONACIONES_URL="http://[SU_GATEWAY_DONACIONES]"
API_GATEWAY_URL="http://[SU_GATEWAY_ALMACEN]"


Ejecutar Servicios:
Bash
docker compose up -d --build


Instalar Dependencias e Inicializar BD:
Ejecute los comandos dentro del contenedor laravel.
Bash
docker compose exec laravel composer install
docker compose exec laravel php artisan key:generate
docker compose exec laravel php artisan migrate
docker compose exec laravel php artisan db:seed --force
docker compose exec laravel php artisan optimize:clear
docker compose restart laravel


URL de Acceso: La aplicación Nginx está expuesta a través de un proxy externo.
4.2. Despliegue Local (Sin Docker Compose)
Este método es para desarrollo local rápido.
Requisitos: PHP (8.2+), Composer, Servidor Web (Apache/Nginx o Artisan Serve), PostgreSQL (Servicio corriendo localmente).
Clonar y Dependencias:
Bash
git clone [URL_DEL_REPOSITORIO] donaciones-laravel
cd donaciones-laravel
composer install


Configuración del Entorno (.env):
Ajuste las variables DB_HOST, DB_USERNAME, y DB_PASSWORD para conectar a su servidor PostgreSQL local (DB_HOST=127.0.0.1).
Inicializar la Base de Datos Local:
Bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan optimize:clear


Ejecutar Servidor de Desarrollo:
Bash
php artisan serve
# Acceso: http://127.0.0.1:8000


🔄 6. Tareas Programadas (Scheduler)
Para mantener la información sincronizada con los Gateways externos, debe asegurarse de que el Scheduler se ejecute continuamente:
Entorno
Método de Ejecución
Comando
Docker
Contenedor scheduler (Automático)
php artisan schedule:work
Nativo (Producción)
Cron Job del Sistema Operativo
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1


