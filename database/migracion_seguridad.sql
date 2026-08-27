-- ============================================================================
--  Chamba Ya - Migración de seguridad
--  Ejecutar una sola vez sobre la base de datos existente:
--      mysql -u root bd_chamba_ya < database/migracion_seguridad.sql
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. Recuperación de contraseña por token de un solo uso.
--    Sustituye al método anterior (correo + teléfono), que permitía tomar
--    cualquier cuenta porque el teléfono es público en los anuncios.
--    En la tabla se guarda el HASH del token, nunca el token en claro: si
--    alguien lee la base de datos, no puede usarlo para restablecer nada.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset` (
    `idReset`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `idUsuario`  INT(10) UNSIGNED NOT NULL,
    `token_hash` VARCHAR(64)  NOT NULL,
    `expira`     DATETIME     NOT NULL,
    `usado`      TINYINT(1)   NOT NULL DEFAULT 0,
    `fechaCreacion` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idReset`),
    UNIQUE KEY `uk_token_hash` (`token_hash`),
    KEY `idx_usuario` (`idUsuario`),
    CONSTRAINT `fk_reset_usuario` FOREIGN KEY (`idUsuario`)
        REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------------------------------------------------------
-- 2. Registro de intentos de inicio de sesión.
--    Antes el contador vivía en $_SESSION, es decir, en el lado del atacante:
--    bastaba borrar la cookie para tener 3 intentos nuevos, infinitas veces.
--    Guardándolo en la base de datos el bloqueo ya no se puede evadir.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `intento_login` (
    `idIntento` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `correo`    VARCHAR(80)  NOT NULL,
    `ip`        VARCHAR(45)  NOT NULL,   -- 45 caracteres: cabe una IPv6
    `exito`     TINYINT(1)   NOT NULL DEFAULT 0,
    `fecha`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idIntento`),
    KEY `idx_correo_fecha` (`correo`, `fecha`),
    KEY `idx_ip_fecha` (`ip`, `fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
