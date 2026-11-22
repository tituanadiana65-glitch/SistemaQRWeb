-- =====================================================================
-- Sistema de Lectura y Procesamiento de Codigos QR (version web)
-- Base de datos MySQL (XAMPP - phpMyAdmin)
-- Es la misma base que usa el sistema de escritorio en Java
-- =====================================================================
CREATE DATABASE IF NOT EXISTS bd_equipos
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_equipos;

-- ---------------------------------------------------------------------
-- Equipos: el ID (id_equipo) es lo que va dentro del codigo QR
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS equipo (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(40) NOT NULL,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    numero_serie VARCHAR(60),
    ubicacion VARCHAR(100),
    responsable VARCHAR(100),
    direccion_ip VARCHAR(15),
    direccion_mac VARCHAR(17),
    sistema_operativo VARCHAR(60),
    configuracion TEXT,
    estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_baja DATETIME NULL,
    motivo_baja VARCHAR(200) NULL
);

-- ---------------------------------------------------------------------
-- Historial de lecturas de codigos QR
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lectura_qr (
    id_lectura INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    fecha_lectura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lectura_equipo FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo)
);

-- ---------------------------------------------------------------------
-- Historial de configuracion: al editar un equipo se guardan sus datos
-- ANTERIORES y que campos cambiaron
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS historial_configuracion (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    fecha_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    campos_cambiados VARCHAR(255),
    nombre VARCHAR(100),
    tipo VARCHAR(40),
    marca VARCHAR(50),
    modelo VARCHAR(50),
    numero_serie VARCHAR(60),
    ubicacion VARCHAR(100),
    responsable VARCHAR(100),
    direccion_ip VARCHAR(15),
    direccion_mac VARCHAR(17),
    sistema_operativo VARCHAR(60),
    configuracion TEXT,
    CONSTRAINT fk_historial_equipo FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo)
);
