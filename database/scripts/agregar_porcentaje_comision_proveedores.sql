-- Ejecución manual alternativa a la migración de Laravel.
ALTER TABLE proveedores
    ADD COLUMN porcentaje_comision DECIMAL(5, 2) NOT NULL DEFAULT 0.00
    AFTER persona_id;
