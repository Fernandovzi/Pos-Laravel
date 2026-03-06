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

## Módulo de pedidos (apartados)

Incluye un módulo para apartar productos con control de inventario temporal y trazabilidad:

- Folio automático diario (`PED-YYYYMMDD-####`).
- Registro de persona que recogerá el pedido.
- Descuento temporal de inventario al crear pedido.
- Registro automático de movimiento en kardex con tipo `PEDIDO`.
- Cancelación de pedidos para liberar stock.
- Generación de PDF con detalle del pedido.

### Rutas principales

- `GET /admin/pedidos`
- `GET /admin/pedidos/create`
- `POST /admin/pedidos`
- `GET /admin/pedidos/{pedido}`
- `DELETE /admin/pedidos/{pedido}`
- `GET /admin/pedidos/{pedido}/pdf`

## Licencia

Este proyecto está licenciado bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
