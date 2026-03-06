# ISSUE TEMPLATE

Plantilla para reportar bugs del proyecto **POS Laravel**.

> Copia esta estructura al crear un issue y completa todos los campos posibles.

---

## 1) Resumen del problema

**Título sugerido:** `[BUG] <módulo>: descripción corta`

**Descripción breve:**
Explica en 2–4 líneas qué comportamiento es incorrecto y por qué impacta la operación.

---

## 2) Módulo afectado

Marca o escribe el/los módulo(s) involucrado(s):

- [ ] Autenticación / usuarios / permisos
- [ ] Productos / categorías / presentaciones
- [ ] Inventario / kardex
- [ ] Compras
- [ ] Ventas
- [ ] Pedidos / apartados
- [ ] Caja / movimientos
- [ ] Reportes / exportaciones
- [ ] Facturación / CFDI
- [ ] Otro: ______________________

---

## 3) Precondiciones

Describe el estado previo necesario para reproducir el error:

- Usuario/rol utilizado:
- Datos cargados (productos, stock, cliente, etc.):
- Configuración relevante (empresa, moneda, método de pago, etc.):

---

## 4) Pasos para reproducir

1. 
2. 
3. 
4. 

> Sé específico con rutas, botones, filtros y valores capturados.

---

## 5) Resultado esperado

Describe qué debería ocurrir funcionalmente.

---

## 6) Resultado actual

Describe qué ocurre realmente:

- Mensaje mostrado:
- Pantalla afectada:
- ¿Es intermitente o siempre ocurre?:

---

## 7) Evidencia

Adjunta todo lo posible:

- Capturas de pantalla / video.
- Logs de Laravel (`storage/logs/laravel.log`).
- SQL relevante (si aplica).
- Traza de error completa.

---

## 8) Severidad e impacto

**Severidad:**
- [ ] Crítica (bloquea operación)
- [ ] Alta (afecta proceso principal)
- [ ] Media (hay workaround)
- [ ] Baja (impacto menor)

**Impacto de negocio:**
- [ ] Caja detenida
- [ ] No permite vender
- [ ] Error de inventario
- [ ] Riesgo fiscal
- [ ] Afecta reportes
- [ ] Otro: ______________________

---

## 9) Entorno

- Ambiente: [ ] Local [ ] QA [ ] Producción
- Versión de la app:
- Commit/branch (si se conoce):
- PHP:
- Laravel:
- Base de datos (MySQL/MariaDB + versión):
- Navegador y versión:
- Sistema operativo:

---

## 10) Frecuencia

- [ ] Siempre
- [ ] Frecuente
- [ ] Ocasional
- [ ] Solo una vez

Número aproximado de veces reproducido: ______

---

## 11) Workaround temporal

Describe cómo se está resolviendo temporalmente (si existe).

---

## 12) Información adicional

Incluye contexto extra útil para diagnóstico:

- Cambios recientes antes del bug.
- Relación con otros issues.
- Usuarios o sucursales afectadas.

---

## Checklist antes de enviar

- [ ] Confirmé que el bug no está duplicado.
- [ ] Incluí pasos claros para reproducir.
- [ ] Adjunté evidencia (captura/log/traza).
- [ ] Especificqué severidad e impacto.
- [ ] Registré datos de entorno.
