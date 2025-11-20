-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: bdcarritocompras
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compra` (
  `idcompra` bigint(20) NOT NULL AUTO_INCREMENT,
  `cofecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idusuario` bigint(20) NOT NULL,
  PRIMARY KEY (`idcompra`),
  UNIQUE KEY `idcompra` (`idcompra`),
  KEY `fkcompra_1` (`idusuario`),
  CONSTRAINT `fkcompra_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
INSERT INTO `compra` VALUES (9,'2025-11-21 01:19:51',1),(10,'2025-11-21 01:21:18',1),(11,'2025-11-21 01:24:46',1);
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compraestado`
--

DROP TABLE IF EXISTS `compraestado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compraestado` (
  `idcompraestado` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idcompra` bigint(11) NOT NULL,
  `idcompraestadotipo` int(11) NOT NULL,
  `cefechaini` timestamp NOT NULL DEFAULT current_timestamp(),
  `cefechafin` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcompraestado`),
  UNIQUE KEY `idcompraestado` (`idcompraestado`),
  KEY `fkcompraestado_1` (`idcompra`),
  KEY `fkcompraestado_2` (`idcompraestadotipo`),
  CONSTRAINT `fkcompraestado_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraestado_2` FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compraestado`
--

LOCK TABLES `compraestado` WRITE;
/*!40000 ALTER TABLE `compraestado` DISABLE KEYS */;
INSERT INTO `compraestado` VALUES (9,9,1,'2025-11-21 01:20:09','2025-11-21 01:20:09'),(10,9,2,'2025-11-21 01:20:09','2025-11-21 01:20:36'),(11,9,3,'2025-11-21 01:20:36','2025-11-21 01:20:46'),(12,10,1,'2025-11-21 01:21:28','2025-11-21 01:21:28'),(13,10,4,'2025-11-21 01:21:28','2025-11-21 01:21:38'),(14,11,1,'2025-11-21 01:24:55','2025-11-21 01:24:55'),(15,11,2,'2025-11-21 01:24:55','2025-11-21 01:25:39'),(16,11,3,'2025-11-21 01:25:39','2025-11-21 01:26:03');
/*!40000 ALTER TABLE `compraestado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compraestadotipo`
--

DROP TABLE IF EXISTS `compraestadotipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` int(11) NOT NULL,
  `cetdescripcion` varchar(50) NOT NULL,
  `cetdetalle` varchar(256) NOT NULL,
  PRIMARY KEY (`idcompraestadotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compraestadotipo`
--

LOCK TABLES `compraestadotipo` WRITE;
/*!40000 ALTER TABLE `compraestadotipo` DISABLE KEYS */;
INSERT INTO `compraestadotipo` VALUES (1,'iniciada','cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'),(2,'aceptada','cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '),(3,'enviada','cuando el usuario administrador envia a uno de las compras en estado =2 '),(4,'cancelada','un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 ');
/*!40000 ALTER TABLE `compraestadotipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compraitem`
--

DROP TABLE IF EXISTS `compraitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compraitem` (
  `idcompraitem` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idproducto` bigint(20) NOT NULL,
  `idcompra` bigint(20) NOT NULL,
  `cicantidad` int(11) NOT NULL,
  PRIMARY KEY (`idcompraitem`),
  UNIQUE KEY `idcompraitem` (`idcompraitem`),
  KEY `fkcompraitem_1` (`idcompra`),
  KEY `fkcompraitem_2` (`idproducto`),
  CONSTRAINT `fkcompraitem_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraitem_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compraitem`
--

LOCK TABLES `compraitem` WRITE;
/*!40000 ALTER TABLE `compraitem` DISABLE KEYS */;
INSERT INTO `compraitem` VALUES (11,11,9,3),(12,8,9,1),(13,11,10,1),(14,9,10,1),(15,11,11,1),(16,2,11,1);
/*!40000 ALTER TABLE `compraitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `idmenu` bigint(20) NOT NULL AUTO_INCREMENT,
  `menombre` varchar(50) NOT NULL COMMENT 'Nombre del item del menu',
  `medescripcion` varchar(124) NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  `idpadre` bigint(20) DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  `medeshabilitado` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez',
  PRIMARY KEY (`idmenu`),
  UNIQUE KEY `idmenu` (`idmenu`),
  KEY `fkmenu_1` (`idpadre`),
  CONSTRAINT `fkmenu_1` FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Administracion','#',NULL,NULL),(2,'Cliente','#',NULL,NULL),(3,'Modificar productos','/tpfinaldinamica/vista/Admin/productos.php',1,NULL),(26,'Modificar menus','/tpfinaldinamica/vista/Admin/administrarMenus.php',1,NULL),(62,'Administrar usuarios','/tpfinaldinamica/vista/Admin/usuarios.php',1,NULL),(63,'Administrar compras','/tpfinaldinamica/vista/Admin/administrarCompras.php',1,NULL),(64,'Carrito','/tpfinaldinamica/vista/Cliente/carrito.php',2,NULL),(65,'Modificar datos de usuario','/tpfinaldinamica/vista/Cliente/modificarUsuario.php',2,NULL),(66,'Administrar roles','/tpfinaldinamica/vista/Admin/administrarRoles.php',1,NULL),(67,'Administracion permisos menu','/tpfinaldinamica/vista/Admin/administrarRolMenu.php',1,NULL),(68,'Prueba Rol','#',NULL,NULL),(69,'Pruebita submenu','#',68,NULL),(70,'Mis compras','/tpfinaldinamica/vista/Cliente/misCompras.php',2,NULL);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menurol`
--

DROP TABLE IF EXISTS `menurol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menurol` (
  `idmenu` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL,
  PRIMARY KEY (`idmenu`,`idrol`),
  KEY `fkmenurol_2` (`idrol`),
  CONSTRAINT `fkmenurol_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  CONSTRAINT `fkmenurol_2` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menurol`
--

LOCK TABLES `menurol` WRITE;
/*!40000 ALTER TABLE `menurol` DISABLE KEYS */;
INSERT INTO `menurol` VALUES (1,1),(2,2),(68,5);
/*!40000 ALTER TABLE `menurol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `idproducto` bigint(20) NOT NULL AUTO_INCREMENT,
  `pronombre` varchar(50) NOT NULL,
  `prodetalle` varchar(512) NOT NULL,
  `procantstock` int(11) NOT NULL,
  PRIMARY KEY (`idproducto`),
  UNIQUE KEY `idproducto` (`idproducto`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (2,'Arma oraciones','Libro móvil arma oraciones. De una forma divertida el niño podrá armar oraciones a partir de una infinidad de palabras dadas',-1),(8,'Anotadores','Anotadores tamaño A6. 40 hojas blancas. Tapas plastificadas al calor qué podemos personalizar a tu gusto',1),(9,'Arma palabras con sílabas. ','Realizado en mdf de 3 mm en caja de madera. Deberán formar utilizando las sílabas los nombres de las ilustraciones',2),(10,'Arma palabras','Las ilustraciones ayudan a formar los nombres. Solo tienen que buscarlas y ordenarlas',2),(11,'Libro móvil de palabras.','Podrán armar los nombres de los dibujitos usando las coloridas letras pero también cualquier nombre que imaginen',12),(12,'Tiras silábica','Podrán armar múltiples palabras con tiras que contienen todas las sílabas simples que se pueden formar con el alfabeto',-1),(14,'Armando y desarmando palabras. ','Cuenta con muchas fichas ilustradas. Deberán escribir la palabra separada en sílabas y luego armarla nuevamente. La pizarra está plastificada al calor para poder ser reutilizada cuántas veces quieras. Viene un marcador de pizarra',2),(26,'Libro móvil. ','Deberán armar los conjuntos de dibujo, inicial y nombre.',2),(28,'Celular loco','2',-1);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `idrol` bigint(20) NOT NULL AUTO_INCREMENT,
  `rodescripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idrol`),
  UNIQUE KEY `idrol` (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Admin'),(2,'Cliente'),(3,'Persona no logeada'),(5,'Prueba');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `idusuario` bigint(20) NOT NULL AUTO_INCREMENT,
  `usnombre` varchar(50) NOT NULL,
  `uspass` varchar(150) NOT NULL,
  `usmail` varchar(50) NOT NULL,
  `usdeshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Naxo','Hola123','joaq@admin.com',NULL),(2,'Juan','Hola123','juan@cliente.com',NULL),(3,'Pancho','Hola123','pancho@cliente.com',NULL),(4,'Naxitoo','Hola123','admin2@admin.com','2025-11-21 01:29:47');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariorol`
--

DROP TABLE IF EXISTS `usuariorol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuariorol` (
  `idusuario` bigint(20) NOT NULL,
  `idrol` bigint(20) NOT NULL,
  PRIMARY KEY (`idusuario`,`idrol`),
  KEY `idusuario` (`idusuario`),
  KEY `idrol` (`idrol`),
  CONSTRAINT `fkmovimiento_1` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariorol`
--

LOCK TABLES `usuariorol` WRITE;
/*!40000 ALTER TABLE `usuariorol` DISABLE KEYS */;
INSERT INTO `usuariorol` VALUES (1,1),(2,2),(3,2),(4,2);
/*!40000 ALTER TABLE `usuariorol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'bdcarritocompras'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-20 18:37:48
