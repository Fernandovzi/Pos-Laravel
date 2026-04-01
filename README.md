# POS Laravel

Sistema de punto de venta desarrollado con Laravel para la gestión de inventario, ventas, pedidos y control operativo de tienda.

## Características principales

- Gestión de productos y categorías.
- Control de inventario con movimientos en kardex.
- Módulo de ventas.
- Módulo de pedidos/apartados con folio diario automático.
- Generación de comprobantes en PDF.
- Administración de usuarios y permisos.

## Requisitos

- PHP 8.2 o superior.
- Composer.
- MySQL o MariaDB.
- Node.js y npm (si se compilan assets frontend).

## Instalación en entorno local

1. Clonar el repositorio:

```bash
git clone <url-del-repositorio>
cd Pos-Laravel
```

2. Instalar dependencias de PHP:

```bash
composer install
```

3. Copiar archivo de entorno y configurar variables:

```bash
cp .env.example .env
```

Editar `.env` con los datos de base de datos, por ejemplo:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_laravel
DB_USERNAME=root
DB_PASSWORD=
```

4. Generar clave de aplicación:

```bash
php artisan key:generate
```

5. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

6. Crear enlace simbólico de storage:

```bash
php artisan storage:link
```

7. Iniciar servidor de desarrollo:

```bash
php artisan serve
```

8. (Opcional) Ejecutar worker de colas en otra terminal:

```bash
php artisan queue:listen
```

## Licencia

Este proyecto está licenciado bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

<img width="1899" height="939" alt="image" src="https://github.com/user-attachments/assets/43d4428b-a7a2-4728-9092-16c14282c3c7" />





- POS_TICKET_WIDTH_MM: ancho del ticket POS en mm (58 u 80).
