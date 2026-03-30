# Manual de Usuario del Sistema POS Laravel

**Versión del manual:** 1.0  
**Fecha:** 30 de marzo de 2026  
**Dirigido a:** Usuarios finales (Administrador y Usuario operativo)

---

## 1. Introducción

### Descripción general del sistema
El sistema POS Laravel es una plataforma de gestión comercial para tiendas, orientada a controlar:

- Catálogo de productos (categorías, presentaciones y productos)
- Inventario y movimientos de existencias (incluyendo kardex y ajustes)
- Ventas con comprobante y múltiples métodos de pago
- Pedidos y compras internas
- Caja (apertura, movimientos y cierre)
- Gestión de clientes, proveedores, empleados, usuarios y roles
- Reportes y exportaciones (PDF y Excel)

[Agregar imagen aquí] *(Pantalla de inicio / panel principal)*

### Objetivo
Facilitar la operación diaria del negocio, centralizando en una sola herramienta los procesos de venta, control de stock, pedidos y administración básica.

### Tipos de usuarios
- **Administrador:** Configura el sistema, administra usuarios/roles y supervisa operaciones.
- **Usuario:** Ejecuta tareas operativas del día a día según los permisos asignados (ventas, pedidos, caja, consulta de inventario, etc.).

---

## 2. Requisitos del sistema

### Navegador / sistema operativo
- Navegadores recomendados:
  - Google Chrome (versión reciente)
  - Microsoft Edge (versión reciente)
  - Mozilla Firefox (versión reciente)
- Sistemas operativos compatibles para uso web:
  - Windows 10/11
  - macOS (versiones recientes)
  - Linux (distribuciones actuales)

### Requisitos técnicos mínimos
- Conexión estable a internet o red local (según despliegue)
- Resolución mínima recomendada: 1366 x 768
- JavaScript habilitado en el navegador
- Cookies habilitadas para mantener sesión activa

---

## 3. Acceso al sistema

### URL (si aplica)
- **Acceso principal:** `http://TU_DOMINIO/`
- **Inicio de sesión:** `http://TU_DOMINIO/login`

> Nota: Reemplaza `TU_DOMINIO` por la dirección real proporcionada por tu empresa.

### Inicio de sesión
1. Abra la URL de acceso.
2. Ingrese su **correo electrónico**.
3. Ingrese su **contraseña**.
4. Haga clic en **Ingresar**.
5. Si las credenciales son correctas, entrará al panel principal.

[Agregar imagen aquí] *(Formulario de inicio de sesión)*

### Recuperación de contraseña
En la interfaz actual no se muestra una opción automática de “Olvidé mi contraseña”.

Procedimiento recomendado:
1. Contacte al **Administrador del sistema**.
2. Solicite restablecimiento de credenciales.
3. Inicie sesión con la nueva contraseña y cámbiela desde **Perfil**.

---

## 4. Descripción general de la interfaz

### Menú principal
El menú lateral organiza módulos como:

- Inicio (Principal)
- Categorías, Presentaciones, Productos
- Kardex y Ajustes de inventario
- Clientes, Proveedores
- Cajas y Movimientos
- Producción/Compras
- Ventas
- Pedidos
- Empresa, Empleados, Usuarios, Roles

> Los módulos visibles dependen del rol y permisos del usuario.

[Agregar imagen aquí] *(Menú lateral del sistema)*

### Dashboard (panel principal)
El panel muestra indicadores operativos, por ejemplo:
- Productos registrados
- Existencia total
- Pedidos apartados
- Ventas del día
- Gráfica de producción interna (últimos 7 días)

[Agregar imagen aquí] *(Dashboard con indicadores)*

### Navegación general
- Use el menú lateral para abrir módulos.
- Use los botones **Nuevo / Añadir / Crear** para registrar información.
- Use columnas de **Acciones** para ver, editar, cancelar o eliminar registros.
- Use buscadores/tablas para localizar datos rápidamente.

---

## 5. Roles de usuario

## 5.1 Administrador

### Descripción del rol
Responsable de la configuración global, seguridad operativa y administración de catálogos/usuarios.

### Permisos
Generalmente cuenta con permisos completos para ver, crear, editar, eliminar y configurar.

### Acceso a módulos
Puede acceder a módulos de administración y operación (usuarios, roles, empresa, reportes, catálogo, ventas, inventario, caja, etc.).

### Funcionalidades del Administrador

#### A) Gestión de usuarios
**Descripción:** Alta, edición y activación/desactivación de usuarios del sistema.

**Paso a paso:**
1. Ir a **Usuarios**.
2. Clic en **Añadir nuevo usuario**.
3. Capturar empleado, alias, correo, contraseña y rol.
4. Guardar.
5. Para cambios, usar acción **Editar**.
6. Para desactivar/reactivar, usar el botón de eliminar/restaurar.

**Ejemplo práctico:**
- Crear usuario “cajero1” para el turno de tarde, con rol operativo y correo corporativo.

[Agregar imagen aquí] *(Listado y alta de usuarios)*

---

#### B) Configuración del sistema
**Descripción:** Actualiza datos de la empresa y parámetros como impuesto y moneda.

**Paso a paso:**
1. Entrar a **Empresa**.
2. Editar razón social, datos fiscales/contacto y configuración de impuesto.
3. Seleccionar moneda.
4. Clic en **Actualizar**.

**Ejemplo práctico:**
- Cambiar el porcentaje de impuesto y abreviatura para reflejar una nueva política fiscal.

[Agregar imagen aquí] *(Pantalla de configuración de empresa)*

---

#### C) Administración de catálogos
**Descripción:** Mantiene la base operativa del negocio (categorías, presentaciones, productos, clientes y proveedores).

**Paso a paso (general):**
1. Abrir módulo deseado (ej. **Categorías** o **Productos**).
2. Clic en **Nuevo** o **Añadir**.
3. Capturar información requerida.
4. Guardar registro.
5. Editar cuando sea necesario desde acciones.

**Ejemplo práctico:**
- Crear categoría “Anillos”, presentación “Par”, y luego registrar un producto con código, precio y stock inicial.

[Agregar imagen aquí] *(Alta de categorías/presentaciones/productos)*

---

#### D) Reportes
**Descripción:** Genera documentos para control y auditoría operativa.

**Paso a paso:**
1. Ir al módulo de **Ventas** o **Pedidos**.
2. Seleccionar registro.
3. Usar opción **Descargar PDF**.
4. Para ventas, también usar **Exportar Excel** cuando aplique.

**Ejemplo práctico:**
- Exportar ventas del periodo para revisión administrativa mensual.

[Agregar imagen aquí] *(Exportación PDF/Excel)*

---

#### E) Otros módulos relevantes
- **Roles:** crear y ajustar permisos por perfil.
- **Empleados:** registrar personal y datos base.
- **Registro de actividad:** monitorear acciones del sistema.
- **Caja:** apertura/cierre y control de movimientos.

---

## 5.2 Usuario

### Descripción del rol
Perfil operativo que ejecuta procesos diarios (ventas, pedidos, caja y consulta de inventario), con alcance limitado por permisos.

### Permisos
Dependen del rol asignado. Puede tener permisos de consulta o captura en módulos específicos.

### Acceso a módulos
Normalmente: Ventas, Pedidos, Caja/Movimientos, Clientes y consulta de inventario/kardex (según política interna).

### Funcionalidades del Usuario

#### A) Operaciones principales del sistema
**Descripción:** Registro de ventas y pedidos; consulta de existencias.

**Paso a paso (venta):**
1. Ir a **Ventas > Crear**.
2. Seleccionar cliente y comprobante.
3. Agregar productos y cantidades.
4. Registrar método(s) de pago.
5. Confirmar con **Cobrar venta**.

**Ejemplo práctico:**
- Venta de 2 productos, pago mixto (efectivo + tarjeta), emisión de comprobante.

[Agregar imagen aquí] *(Flujo de creación de venta)*

---

#### B) Registro de información
**Descripción:** Captura de datos operativos de pedidos, movimientos de caja y actualizaciones permitidas.

**Paso a paso (pedido):**
1. Ir a **Pedidos > Crear**.
2. Elegir proveedor y persona de recojo.
3. Agregar productos y cantidades.
4. Revisar subtotal/impuesto/total.
5. Guardar pedido.

**Ejemplo práctico:**
- Registrar pedido para reposición de stock de temporada.

[Agregar imagen aquí] *(Formulario de pedido y detalle)*

---

#### C) Consultas
**Descripción:** Revisión de información histórica y de estado.

**Paso a paso:**
1. Abrir el módulo requerido (Ventas, Pedidos, Kardex, Inventario, Caja).
2. Usar la tabla para buscar/filtrar.
3. Entrar al detalle o descargar comprobante cuando aplique.

**Ejemplo práctico:**
- Consultar una venta cancelada y verificar su comprobante PDF.

---

#### D) Procesos diarios
**Descripción:** Secuencia típica de operación en jornada laboral.

**Paso a paso sugerido:**
1. Abrir caja (si tiene permiso).
2. Registrar ventas y/o pedidos del día.
3. Hacer retiros de caja cuando corresponda.
4. Revisar inventario/kardex en cierres parciales.
5. Cerrar caja al finalizar turno.

**Ejemplo práctico:**
- Turno matutino: apertura con saldo inicial, 12 ventas, 1 retiro operativo, cierre con conciliación.

---

## 6. Flujos de trabajo comunes

### Flujo 1: Crear, editar y desactivar un registro (ej. cliente)
1. Crear desde módulo **Clientes**.
2. Editar en la opción **Editar**.
3. Desactivar o eliminar según las políticas del sistema.

### Flujo 2: Generar reportes
1. Entrar a **Ventas** o **Pedidos**.
2. Seleccionar la operación.
3. Descargar PDF o exportar Excel (ventas).

### Flujo 3: Proceso frecuente de venta
1. Verificar caja abierta.
2. Crear venta y agregar productos.
3. Registrar pagos.
4. Confirmar cobro.
5. Entregar comprobante.

### Flujo 4: Cancelación controlada
1. Entrar al listado (ventas/pedidos).
2. Ubicar el registro.
3. Confirmar cancelación en modal.
4. Verificar actualización en stock/caja/estado.

[Agregar imagen aquí] *(Flujograma operativo simple)*

---

## 7. Mensajes de error y solución de problemas

| Error o situación | Posible causa | Solución recomendada |
|---|---|---|
| “Credenciales incorrectas” | Correo/contraseña inválidos | Verificar escritura y volver a intentar. |
| Usuario sin acceso a módulo | Falta de permisos | Solicitar ajuste de rol al Administrador. |
| “Debe aperturar una caja” | Se intenta operar sin caja abierta | Ir a módulo **Cajas** y aperturar. |
| No permite guardar venta/pedido | Campos obligatorios incompletos | Revisar formulario y completar datos faltantes. |
| Cantidad mayor al stock | Se supera inventario disponible | Reducir cantidad o reabastecer stock. |
| No aparece botón de acción | Permiso no asignado o rol restringido | Consultar con Administrador. |
| Error al exportar PDF/Excel | Falla temporal de conexión o datos | Reintentar; si persiste, reportar al área de soporte. |

---

## 8. Preguntas frecuentes (FAQ)

**1) ¿Por qué no veo todos los módulos?**  
Porque el sistema muestra opciones según permisos del rol.

**2) ¿Puedo recuperar mi contraseña desde el login?**  
En la versión actual, normalmente lo gestiona un Administrador.

**3) ¿Qué hago si no puedo registrar una venta?**  
Verifique que la caja esté abierta, que exista stock y que el pago cubra el total.

**4) ¿Cómo imprimo o guardo comprobantes?**  
Desde el listado de ventas/pedidos use la opción **Descargar PDF**.

**5) ¿Se puede exportar información masiva?**  
Sí, en ventas existe exportación a Excel.

---

## 9. Glosario

- **POS:** Punto de venta.
- **Kardex:** Historial de movimientos de inventario por producto.
- **Comprobante:** Documento de respaldo de una venta.
- **Pedido apartado:** Solicitud registrada pendiente de cierre/cancelación.
- **Caja aperturada:** Caja activa para registrar cobros y movimientos.
- **Rol:** Conjunto de permisos asignados a un usuario.

---

## Recomendaciones de uso

- Mantenga sus datos de acceso protegidos.
- Cierre sesión al finalizar su turno.
- Registre operaciones en tiempo real para evitar inconsistencias.
- No comparta cuentas entre empleados.
- Solicite capacitación breve al Administrador antes de operar en producción.

[Agregar imagen aquí] *(Checklist de buenas prácticas de uso)*
