# CHANGELOG

Todas las modificaciones relevantes de este proyecto se documentan en este archivo.

El formato está inspirado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y este proyecto sigue versionado semántico (SemVer) como referencia operativa.

## [Unreleased]

### Agregado
- Archivo `CHANGELOG.md` para registrar de forma centralizada la evolución funcional y técnica del sistema.
- Archivo `ROADMAP.md` con una planificación por fases para orientar el desarrollo del POS.
- Archivo `ISSUE_TEMPLATE.md` para estandarizar el reporte de bugs y mejorar el tiempo de diagnóstico.

---

## [1.4.0] - 2026-03-06

### Agregado
- Módulo de **ajustes de inventario** con estructura de base de datos dedicada.
- Nuevos catálogos SAT para fortalecer la preparación de facturación CFDI 4.0.
- Extensión del flujo de ventas con soporte para **pagos múltiples** por venta.

### Cambiado
- Mejoras de compatibilidad para RFC y relaciones SAT en entidades de personas/clientes/proveedores.
- Ajustes de enums operativos de métodos de pago y movimientos de kardex.

### Corregido
- Correcciones de nulabilidad y consistencia en tablas relacionadas con compras y pedidos.

---

## [1.3.0] - 2026-02-27

### Agregado
- Módulo de **pedidos/apartados** con:
  - folio diario automático,
  - relación de productos por pedido,
  - trazabilidad de inventario en kardex,
  - cancelación con liberación de stock.

### Cambiado
- Integración del tipo `PEDIDO` en el ecosistema de movimientos para control de inventario.

---

## [1.2.0] - 2025-05-24

### Agregado
- Soporte de notificaciones en base de datos para eventos operativos.
- Estructura base de jobs para procesamiento asíncrono.

---

## [1.1.0] - 2025-01-23

### Agregado
- Consolidación de entidades operativas principales:
  - cajas,
  - movimientos,
  - empleados,
  - inventarios,
  - kardex,
  - monedas,
  - empresa y ubicaciones.
- Mejoras al modelo de usuarios para operación administrativa.

### Cambiado
- Afinaciones a columnas de ventas para soportar mejor el flujo comercial.

---

## [1.0.0] - 2023-03-10

### Agregado
- Base funcional inicial del POS:
  - personas/clientes/proveedores,
  - productos, categorías y presentaciones,
  - compras y ventas,
  - comprobantes,
  - relación compra-producto y venta-producto.
- Estructura de autenticación y permisos de usuarios.

---

## [0.1.0] - 2023-03-10

### Agregado
- Inicialización del proyecto Laravel.
- Configuración base de arquitectura MVC y migraciones iniciales.

---

> Nota: Las versiones históricas previas a este archivo se han reconstruido a partir de la evolución del código y migraciones. A partir de ahora, cada release debe actualizar este documento explícitamente.
