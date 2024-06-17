-- MySQL dump 10.13  Distrib 8.3.0, for Linux (x86_64)
--
-- Host: localhost    Database: alejandro
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `enemigos`
--

DROP TABLE IF EXISTS `enemigos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enemigos` (
  `id` int NOT NULL AUTO_INCREMENT, /*id unico del enemigo */
  `nombre` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, /* Nombre del enemigo   */
  `ataque` int NOT NULL, /* Ataque del  enemigo  */
  `defensa` int NOT NULL, /* Defensa del enemigo  */
  `ataquemagico` int NOT NULL, /* Ataque magicodel enemigo  */
  `defensamagica` int NOT NULL, /* Defensa magica del enemigo  */
  `Vida` int NOT NULL, /* Vida del enemigo  */
  `ImagenEnemigo` blob NOT NULL, /* Imagen del enemigo  */
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enemigos`
--

LOCK TABLES `enemigos` WRITE;
/*!40000 ALTER TABLE `enemigos` DISABLE KEYS */;
INSERT INTO `enemigos` VALUES (4,'Lagarto arboreo',5,3,1,1,5,_binary 'imagenes/lagarto.jpg'),(5,'Destructor de mundos',1000,1000,1,1,1000,_binary 'imagenes/tortuga.jpg'),(6,'Rana flecha',5,1,1,1,1,_binary 'imagenes/rana.jpg'),(7,'Dragon',30,25,1,1,20,_binary 'imagenes/dragon.jpg'),(10,'cocodrilo',15,15,1,1,10,_binary 'imagenes/coco.jpg'),(11,'cocodrilo2',15,10,1,1,15,_binary 'imagenes/coco.jpg');
/*!40000 ALTER TABLE `enemigos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo`
--

DROP TABLE IF EXISTS `equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipo` (
  `id` int NOT NULL AUTO_INCREMENT, /* Id unico del equipo */
  `nombre` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, /* Nombre del equipo */
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL, /* Descripcion del equipo */
  `habilidad` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, /* Nombre de la habilidad del equipo */
  `ataque_f` int NOT NULL, /*Ataque del equipo */
  `ataque_m` int NOT NULL, /*Ataque magico del equipo */
  `defensa_f` int NOT NULL, /*Defensa del equipo */
  `defensa_m` int NOT NULL, /*Defensa magica del equipo */
  `posicion` int NOT NULL, /*Posicion donde se equipara el objeto siendo 1 casco 2 pechera 3 pantalones 4 arma */
  `precio` int NOT NULL, /*Precio de venta del equipo */
  `skill` varchar(20) COLLATE utf8mb4_general_ci NOT NULL, /*Formula que refleja el daño que realizara la habilidad del equipo */
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES (1,'casco metalico','Casco que impide que el usuario sufra daos fisico grave, protege levenmente del dao magico','cornada',2,0,5,2,1,8,'$defmf +5'),(2,'peto metalicos','asdasdasdasd sasd asda das dass das  d','fortaleza',3,0,1,3,2,8,'($deff + 3) * 0.5'),(3,'pantalones metalicos','Pantalones que protegen de posibles heridas','patada',2,0,2,3,3,8,'$defmf +5'),(4,'espada de acero','Espada grande y afilada','corte',6,1,2,1,4,8,'$ataf + 8'),(5,'casco de brujo','te hace parecer mas listo','bola de fuego',0,4,1,3,1,8,'0'),(6,'toga magica','Para aparentar ser erudito','contramagia',1,3,1,4,2,8,'$atamf + 10'),(7,'pantalones magicos','Estilosos a la par que defensivos','misiles arcanos',0,3,1,4,3,8,'$defmf + 9'),(8,'varita','Combate al mal con ella','varitazo',1,7,1,3,4,8,'$atamf');
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historialcombate`
--

DROP TABLE IF EXISTS `historialcombate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historialcombate` (
  `idcombate` int NOT NULL AUTO_INCREMENT, /*Id unico */
  `idenemigo` int NOT NULL, /* Id del enemigo al que se enfrento el usuario  */
  `idpersonaje` int NOT NULL, /* Id del personaje del usuario*/
  `estado` varchar(7) COLLATE utf8mb4_spanish_ci NOT NULL, /* Estado de finalizacion del comabate victoria o derrota */
  `recompensa1` int DEFAULT NULL, /* Posible recompensa monetaria obtenida del combate*/
  `recompensa2` int DEFAULT NULL,/* Posible objeto obtenido tras el combate*/
  `fecha` date NOT NULL, /* Posible objeto obtenido tras el combate*/ 
  PRIMARY KEY (`idcombate`),
  KEY `idenemigo` (`idenemigo`),
  KEY `idpersonaje` (`idpersonaje`),
  CONSTRAINT `historialcombate_ibfk_1` FOREIGN KEY (`idenemigo`) REFERENCES `enemigos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historialcombate_ibfk_2` FOREIGN KEY (`idpersonaje`) REFERENCES `personaje` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historialcombate`
--

LOCK TABLES `historialcombate` WRITE;
/*!40000 ALTER TABLE `historialcombate` DISABLE KEYS */;
INSERT INTO `historialcombate` VALUES (15,4,11,'victori',1,1,'2023-12-13'),(16,4,11,'victori',1,1,'2023-12-13'),(17,4,11,'victori',1,1,'2023-12-13'),(18,4,11,'victori',2,1,'2023-12-13'),(19,4,11,'victori',1,1,'2023-12-13'),(20,4,11,'victori',2,1,'2023-12-13'),(21,4,11,'victori',3,1,'2023-12-13'),(22,4,11,'victori',1,1,'2023-12-13'),(23,4,11,'victori',2,1,'2023-12-14'),(47,5,1001,'derr',0,0,'2024-06-09'),(48,10,1001,'vic',3,5,'2024-06-09'),(49,6,1001,'vic',1,0,'2024-06-09'),(50,10,1001,'vic',2,0,'2024-06-09'),(51,6,1001,'vic',0,2,'2024-06-09'),(52,4,1001,'vic',6,6,'2024-06-09'),(53,7,1001,'derr',0,0,'2024-06-09'),(54,4,1001,'vic',2,3,'2024-06-09'),(55,10,1001,'vic',9,0,'2024-06-09'),(56,7,1001,'vic',0,5,'2024-06-09'),(57,11,1001,'vic',2,0,'2024-06-09'),(58,10,1001,'vic',6,0,'2024-06-09'),(59,5,1001,'derr',0,0,'2024-06-09'),(60,11,1001,'vic',0,1,'2024-06-09'),(61,6,1001,'vic',2,0,'2024-06-09'),(62,11,1001,'vic',0,8,'2024-06-09'),(63,6,1001,'vic',0,1,'2024-06-09'),(64,5,1001,'derr',0,0,'2024-06-09'),(65,10,1001,'vic',3,0,'2024-06-09');
/*!40000 ALTER TABLE `historialcombate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id` int NOT NULL AUTO_INCREMENT, /*Id unico del eqipo que posee un jugador*/
  `idjugador` int NOT NULL, /* Id del jugador poseedor del objeto*/
  `idequipo` int NOT NULL, /* Id del objeto de la tabla equipo*/
  `equipado` tinyint(1) NOT NULL DEFAULT '0', /* Valor booleano que indica si el objeto esta equipado*/
  PRIMARY KEY (`id`),
  KEY `idjugador` (`idjugador`),
  KEY `idequipo` (`idequipo`),
  CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`idjugador`) REFERENCES `personaje` (`id`),
  CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`idequipo`) REFERENCES `equipo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (6,1000,1,0),(7,1000,8,0),(8,1000,1,0);
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personaje`
--

DROP TABLE IF EXISTS `personaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personaje` (
  `id` int NOT NULL, /* Id del jugador sacado del uidNumber del servidor Openldap */
  `nombre` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL, /*Nombre del personaje y de usuario*/
  `raza` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL, /* Raza seleccionada por el usuario*/
  `oro` int NOT NULL DEFAULT '0', /* Oro en posesion del usuario*/
  `ataque` int NOT NULL, /*Ataque del personaje*/
  `defensa` int NOT NULL, /*Defensa del personaje*/
  `defensamagica` int NOT NULL, /*Defensa magica del personaje*/
  `ataquemagico` int NOT NULL, /*Ataque magico del personaje*/
  `perfil` blob NOT NULL, /*Foto del personaje*/
  `exp` int NOT NULL DEFAULT '0', /*Esperiencia obtenida por el personaje*/
  `nivel` int NOT NULL DEFAULT '1', /* Nivel del personaje*/
  `aumentodeff` int NOT NULL DEFAULT '3', /*Precio de aumentar las estadisticas defensa*/
  `aumentoataf` int NOT NULL DEFAULT '3', /*Precio de aumentar las estadisticas ataque*/
  `aumentoam` int NOT NULL DEFAULT '3', /*Precio de aumentar las estadisticas atauqe magico*/
  `aumentodm` int NOT NULL DEFAULT '3', /*Precio de aumentar las estadisticas defensa magica*/
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personaje`
--

LOCK TABLES `personaje` WRITE;
/*!40000 ALTER TABLE `personaje` DISABLE KEYS */;
INSERT INTO `personaje` VALUES (11,'','elfo',0,24,16,0,0,'',0,0,0,0,0,0),(14,'','orco',1,19,6,0,0,'',0,0,0,0,0,0),(16,'','humano',0,13,11,0,0,'',0,0,0,0,0,0),(18,'','enano',0,5,15,0,0,'',0,0,0,0,0,0),(1000,'admin','admin',0,5,15,0,0,'',0,0,0,0,0,0);
/*!40000 ALTER TABLE `personaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subasta`
--

DROP TABLE IF EXISTS `subasta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subasta` (
  `id_subasta` int NOT NULL AUTO_INCREMENT, /* Id unico del objeto a subastar*/
  `id_vendedor` int NOT NULL,/* Id del jugador que vende el objeto*/
  `precio_min` int NOT NULL,/* Precio de puja del objeto vendido*/
  `precio_max` int NOT NULL,/* Precio compra directa*/
  `id_objeto` int NOT NULL,/* id unico de la tabla inventario*/
  `id_comprador` int NOT NULL,/* id del comprador o del usuario con la puja mas alta*/
  `tiempo` datetime NOT NULL,/* Tiempo hasta que finalice la puja*/
  PRIMARY KEY (`id_subasta`),
  KEY `id_vendedor` (`id_vendedor`),
  KEY `subasta_ibfk_2` (`id_objeto`),
  CONSTRAINT `subasta_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `personaje` (`id`),
  CONSTRAINT `subasta_ibfk_2` FOREIGN KEY (`id_objeto`) REFERENCES `inventario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subasta`
--

LOCK TABLES `subasta` WRITE;
/*!40000 ALTER TABLE `subasta` DISABLE KEYS */;
INSERT INTO `subasta` VALUES (1,1001,12,2,6,1000,'2024-06-10 15:00:21'),(2,1001,12,10,6,1000,'2024-06-10 15:04:06'),(3,1001,12,10,6,1000,'2024-06-10 15:07:22'),(4,1001,5,3,7,1000,'2024-06-10 15:09:24'),(5,1001,12,15,8,1000,'2024-06-10 19:01:25');
/*!40000 ALTER TABLE `subasta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacciones`
--

DROP TABLE IF EXISTS `transacciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transacciones` (
  `idtransaccion` int NOT NULL AUTO_INCREMENT, /* Id unico de la transaccion*/
  `itemid` int NOT NULL, /* id del item de la tabla equipo*/
  `id_vendedor` int NOT NULL, /*id del personaje que realizo la venta*/
  `fecha` datetime NOT NULL, /*fecha en la que se realizo la compra*/
  `precio` int NOT NULL, /*Precio pagado en la transacion*/
  `id_comprador` int NOT NULL,/*id del personaje que realizo la compra*/
  PRIMARY KEY (`idtransaccion`),
  KEY `id_vendedor` (`id_vendedor`),
  CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `personaje` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacciones`
--

LOCK TABLES `transacciones` WRITE;
/*!40000 ALTER TABLE `transacciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `transacciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'alejandro'
--

--
-- Dumping routines for database 'alejandro'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-09 23:11:59
