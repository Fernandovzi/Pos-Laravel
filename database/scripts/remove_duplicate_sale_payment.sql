-- Corrige los pagos duplicados de una venta conservando el registro más reciente
-- de cada método de pago. Cambie el número de comprobante antes de ejecutar.
--
-- La tabla venta_pagos no tiene una columna de estado o borrado lógico, por lo
-- que dar de baja un pago requiere eliminarlo físicamente.

SET @numero_comprobante = 'T00372';

START TRANSACTION;

-- Revise los registros que se conservarán/eliminarán antes del DELETE.
SELECT
    vp.id,
    v.numero_comprobante,
    vp.metodo_pago,
    vp.monto,
    vp.referencia,
    vp.created_at,
    CASE
        WHEN vp.id = MAX(vp.id) OVER (PARTITION BY vp.venta_id, vp.metodo_pago)
            THEN 'CONSERVAR'
        ELSE 'ELIMINAR'
    END AS accion
FROM venta_pagos AS vp
INNER JOIN ventas AS v ON v.id = vp.venta_id
WHERE v.numero_comprobante = @numero_comprobante
ORDER BY vp.metodo_pago, vp.id;

-- Conserva el pago con el id mayor y elimina únicamente las repeticiones del
-- mismo método dentro de la venta indicada.
DELETE vp
FROM venta_pagos AS vp
INNER JOIN ventas AS v ON v.id = vp.venta_id
INNER JOIN (
    SELECT
        venta_id,
        metodo_pago,
        MAX(id) AS pago_id_conservar
    FROM venta_pagos
    GROUP BY venta_id, metodo_pago
    HAVING COUNT(*) > 1
) AS duplicados
    ON duplicados.venta_id = vp.venta_id
    AND duplicados.metodo_pago = vp.metodo_pago
WHERE v.numero_comprobante = @numero_comprobante
  AND vp.id <> duplicados.pago_id_conservar;

-- Los movimientos de caja no tienen una llave foránea hacia venta_pagos. Por
-- eso, eliminar solamente el pago no corrige el cierre de caja. Se reconstruyen
-- los ingresos de esta venta a partir del desglose que acaba de depurarse.
DELETE m
FROM movimientos AS m
INNER JOIN ventas AS v
    ON v.caja_id = m.caja_id
    AND m.tipo = 'VENTA'
    AND m.descripcion = CONCAT('Venta n° ', v.numero_comprobante)
WHERE v.numero_comprobante = @numero_comprobante;

INSERT INTO movimientos (
    tipo,
    descripcion,
    monto,
    metodo_pago,
    caja_id,
    created_at,
    updated_at
)
SELECT
    'VENTA',
    CONCAT('Venta n° ', v.numero_comprobante),
    vp.monto,
    vp.metodo_pago,
    v.caja_id,
    COALESCE(vp.created_at, v.fecha_hora),
    COALESCE(vp.updated_at, v.fecha_hora)
FROM venta_pagos AS vp
INNER JOIN ventas AS v ON v.id = vp.venta_id
WHERE v.numero_comprobante = @numero_comprobante;

-- Confirme que ahora existe un solo registro por método tanto en el desglose
-- de la venta como en los movimientos utilizados para el cierre de caja.
SELECT
    v.numero_comprobante,
    vp.metodo_pago,
    COUNT(*) AS registros,
    SUM(vp.monto) AS monto_registrado
FROM venta_pagos AS vp
INNER JOIN ventas AS v ON v.id = vp.venta_id
WHERE v.numero_comprobante = @numero_comprobante
GROUP BY v.numero_comprobante, vp.metodo_pago;

SELECT
    v.numero_comprobante,
    m.metodo_pago,
    COUNT(*) AS movimientos,
    SUM(m.monto) AS ingreso_caja
FROM movimientos AS m
INNER JOIN ventas AS v
    ON v.caja_id = m.caja_id
    AND m.tipo = 'VENTA'
    AND m.descripcion = CONCAT('Venta n° ', v.numero_comprobante)
WHERE v.numero_comprobante = @numero_comprobante
GROUP BY v.numero_comprobante, m.metodo_pago;

COMMIT;
