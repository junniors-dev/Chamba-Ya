
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anuncio` (
  `idAnuncio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipoAnuncio` enum('Trabajo','Servicio') NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `direccionEspecifica` varchar(300) DEFAULT NULL,
  `pagoReferencia` decimal(10,2) DEFAULT NULL,
  `modalidad` enum('Presencial','Virtual') DEFAULT NULL,
  `estado` enum('Disponible','En proceso','Finalizado','Cancelado') DEFAULT 'Disponible',
  `fechaPublicacion` datetime DEFAULT current_timestamp(),
  `idUsuario` int(10) unsigned NOT NULL,
  `idDistrito` int(10) unsigned DEFAULT NULL,
  `vistas` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAnuncio`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `anuncio_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anunciosfavoritos` (
  `idFavorito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaGuardado` datetime DEFAULT current_timestamp(),
  `idUsuario` int(10) unsigned NOT NULL,
  `idAnuncio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idFavorito`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idAnuncio`),
  KEY `idAnuncio` (`idAnuncio`),
  CONSTRAINT `anunciosfavoritos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `anunciosfavoritos_ibfk_2` FOREIGN KEY (`idAnuncio`) REFERENCES `anuncio` (`idAnuncio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calificacion` (
  `idCalificacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `puntaje` tinyint(4) NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `idUsuarioCalificado` int(10) unsigned NOT NULL,
  `idUsuarioCalificador` int(10) unsigned NOT NULL,
  `idPostulacion` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idCalificacion`),
  KEY `idUsuarioCalificado` (`idUsuarioCalificado`),
  KEY `idUsuarioCalificador` (`idUsuarioCalificador`),
  KEY `idx_calificacion_postulacion` (`idPostulacion`),
  CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`idUsuarioCalificado`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`idUsuarioCalificador`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_calificacion_postulacion` FOREIGN KEY (`idPostulacion`) REFERENCES `postulacion` (`idPostulacion`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `idCategoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `imagen` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoriasanuncio` (
  `idCategoriasAnuncio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idCategoria` int(10) unsigned NOT NULL,
  `idAnuncio` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idCategoriasAnuncio`),
  UNIQUE KEY `idCategoria` (`idCategoria`,`idAnuncio`),
  KEY `idAnuncio` (`idAnuncio`),
  CONSTRAINT `categoriasanuncio_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `categoriasanuncio_ibfk_2` FOREIGN KEY (`idAnuncio`) REFERENCES `anuncio` (`idAnuncio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamento` (
  `idDepartamento` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idDepartamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distrito` (
  `idDistrito` int(11) unsigned NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `idProvincia` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDistrito`),
  KEY `idProvincia` (`idProvincia`),
  CONSTRAINT `distrito_ibfk_1` FOREIGN KEY (`idProvincia`) REFERENCES `provincia` (`idProvincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habilidad` (
  `idHabilidad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idHabilidad`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intento_login` (
  `idIntento` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `correo` varchar(80) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `exito` tinyint(1) NOT NULL DEFAULT 0,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idIntento`),
  KEY `idx_correo_fecha` (`correo`,`fecha`),
  KEY `idx_ip_fecha` (`ip`,`fecha`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacion` (
  `idNotificacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsuario` int(10) unsigned NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idNotificacion`),
  KEY `idx_usuario_leida` (`idUsuario`,`leida`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset` (
  `idReset` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsuario` int(10) unsigned NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `expira` datetime NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0,
  `fechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idReset`),
  UNIQUE KEY `uk_token_hash` (`token_hash`),
  KEY `idx_usuario` (`idUsuario`),
  CONSTRAINT `fk_reset_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portafolio` (
  `idPortafolio` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsuario` int(10) unsigned NOT NULL,
  `titulo` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `idCategoria` int(10) unsigned DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idPortafolio`),
  KEY `idx_portafolio_usuario` (`idUsuario`),
  KEY `fk_portafolio_categoria` (`idCategoria`),
  CONSTRAINT `fk_portafolio_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE SET NULL,
  CONSTRAINT `fk_portafolio_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postulacion` (
  `idPostulacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estado` enum('Pendiente','Aceptado','Rechazado','Completado') NOT NULL DEFAULT 'Pendiente',
  `fecha` datetime DEFAULT current_timestamp(),
  `fechaCompletado` datetime DEFAULT NULL,
  `idAnuncio` int(10) unsigned NOT NULL,
  `idUsuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idPostulacion`),
  KEY `idAnuncio` (`idAnuncio`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `postulacion_ibfk_1` FOREIGN KEY (`idAnuncio`) REFERENCES `anuncio` (`idAnuncio`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `postulacion_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provincia` (
  `idProvincia` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `idDepartamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idProvincia`),
  KEY `idDepartamento` (`idDepartamento`),
  CONSTRAINT `provincia_ibfk_1` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`idDepartamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte` (
  `idReporte` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsuarioReporta` int(10) unsigned NOT NULL,
  `idAnuncio` int(10) unsigned DEFAULT NULL,
  `idUsuarioReportado` int(10) unsigned DEFAULT NULL,
  `motivo` varchar(50) NOT NULL,
  `detalle` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` enum('Pendiente','Revisado','Descartado') DEFAULT 'Pendiente',
  `fechaResolucion` datetime DEFAULT NULL,
  `notasAdmin` text DEFAULT NULL,
  PRIMARY KEY (`idReporte`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trabajadoresfavoritos` (
  `idTrabajadorFavorito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fechaGuardado` datetime DEFAULT current_timestamp(),
  `idUsuarioCliente` int(10) unsigned NOT NULL,
  `idUsuarioTrabajador` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idTrabajadorFavorito`),
  UNIQUE KEY `idUsuarioCliente` (`idUsuarioCliente`,`idUsuarioTrabajador`),
  KEY `idUsuarioTrabajador` (`idUsuarioTrabajador`),
  CONSTRAINT `trabajadoresfavoritos_ibfk_1` FOREIGN KEY (`idUsuarioCliente`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `trabajadoresfavoritos_ibfk_2` FOREIGN KEY (`idUsuarioTrabajador`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `idUsuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fotoPerfil` varchar(255) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `descripcionPerfil` text DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `direccionDomicilio` varchar(200) DEFAULT NULL,
  `codigoPostal` varchar(5) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `estado` enum('Activo','Inactivo','Suspendido','Bloqueado') DEFAULT 'Activo',
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `modo` enum('trabajador','cliente') NOT NULL DEFAULT 'trabajador',
  `idDistrito` int(10) unsigned DEFAULT NULL,
  `notif_ofertas` tinyint(1) NOT NULL DEFAULT 1,
  `notif_vistas` tinyint(1) NOT NULL DEFAULT 1,
  `notif_boletin` tinyint(1) NOT NULL DEFAULT 0,
  `visibilidad` varchar(20) NOT NULL DEFAULT 'publico',
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_token_expira` datetime DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `idDistrito` (`idDistrito`),
  CONSTRAINT `fk_usuario_distrito` FOREIGN KEY (`idDistrito`) REFERENCES `distrito` (`idDistrito`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuariohabilidad` (
  `idHabilidadUser` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUsuario` int(10) unsigned NOT NULL,
  `idHabilidad` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idHabilidadUser`),
  UNIQUE KEY `idUsuario` (`idUsuario`,`idHabilidad`),
  KEY `idHabilidad` (`idHabilidad`),
  CONSTRAINT `usuariohabilidad_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuariohabilidad_ibfk_2` FOREIGN KEY (`idHabilidad`) REFERENCES `habilidad` (`idHabilidad`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

