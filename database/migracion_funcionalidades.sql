-- ============================================================================
--  Chamba Ya - Migración de funcionalidades (Paso 2)
--  Ejecutar una sola vez:
--      mysql -u root bd_chamba_ya < database/migracion_funcionalidades.sql
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. Modo activo del usuario.
--
--    Chamba Ya es de doble rol: la misma persona ofrece servicios y contrata.
--    En vez de partir las cuentas en "cliente" o "freelancer" (que dejaría
--    inservibles los anuncios ya publicados), se guarda el modo en el que la
--    persona está ahora mismo. El modo decide qué panel ve, qué menú aparece y
--    qué resultados se le muestran primero, pero no le quita ninguna capacidad.
--
--      trabajador -> busca chamba: ve ofertas de trabajo y sus postulaciones
--      cliente    -> busca quien trabaje: ve trabajadores y las solicitudes que recibe
-- ----------------------------------------------------------------------------
ALTER TABLE `usuario`
    ADD COLUMN `modo` ENUM('trabajador','cliente') NOT NULL DEFAULT 'trabajador'
    AFTER `rol`;

-- ----------------------------------------------------------------------------
-- 2. Portafolio de trabajos anteriores.
--
--    Es lo que faltaba del perfil: la descripción y las habilidades ya existían,
--    pero no había forma de enseñar trabajos ya hechos.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `portafolio` (
    `idPortafolio`  INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `idUsuario`     INT(10) UNSIGNED NOT NULL,
    `titulo`        VARCHAR(120) NOT NULL,
    `descripcion`   TEXT DEFAULT NULL,
    `imagen`        VARCHAR(255) DEFAULT NULL,
    `idCategoria`   INT(10) UNSIGNED DEFAULT NULL,
    `fecha`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idPortafolio`),
    KEY `idx_portafolio_usuario` (`idUsuario`),
    CONSTRAINT `fk_portafolio_usuario` FOREIGN KEY (`idUsuario`)
        REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE,
    CONSTRAINT `fk_portafolio_categoria` FOREIGN KEY (`idCategoria`)
        REFERENCES `categoria` (`idCategoria`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 3. Estado "Completado" en las solicitudes.
--
--    El ciclo era Pendiente -> Aceptado / Rechazado y ahí se quedaba: no había
--    manera de cerrar un trabajo. Se añade Completado, que además es el requisito
--    para poder calificar (ver punto 4).
-- ----------------------------------------------------------------------------
ALTER TABLE `postulacion`
    MODIFY COLUMN `estado` ENUM('Pendiente','Aceptado','Rechazado','Completado')
    NOT NULL DEFAULT 'Pendiente';

ALTER TABLE `postulacion`
    ADD COLUMN `fechaCompletado` DATETIME DEFAULT NULL AFTER `fecha`;

-- ----------------------------------------------------------------------------
-- 4. Las calificaciones quedan ligadas al trabajo que las origina.
--
--    Antes cualquier usuario podía calificar a cualquier otro sin haber
--    trabajado nunca con él, lo que dejaba la puerta abierta a reseñas falsas.
--    Ahora cada calificación apunta a la postulación completada que la justifica.
--
--    La columna admite NULL para no perder las calificaciones ya existentes,
--    que se crearon cuando esa regla no existía.
-- ----------------------------------------------------------------------------
ALTER TABLE `calificacion`
    ADD COLUMN `idPostulacion` INT(10) UNSIGNED DEFAULT NULL AFTER `idUsuarioCalificador`,
    ADD KEY `idx_calificacion_postulacion` (`idPostulacion`),
    ADD CONSTRAINT `fk_calificacion_postulacion` FOREIGN KEY (`idPostulacion`)
        REFERENCES `postulacion` (`idPostulacion`) ON DELETE SET NULL;
