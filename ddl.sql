USE practica_tareas;
 

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  clave VARCHAR(255) NOT NULL,
  fecha_nacimiento DATE,
  genero ENUM('masculino', 'femenino', 'otro'),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS estados(
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Nombre  ENUM('En Progreso', 'Bloqueada', 'Pendiente', 'Completada', 'Imcompleta'),
    PRIMARY KEY (ID)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tareaUsuario(
  ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
  UsuarioID INT UNSIGNED NOT NULL,
  TareaNombre VARCHAR(230) NOT NULL,
  Descripcion VARCHAR(250) NOT NULL,
  Estado INT UNSIGNED NOT NULL,
  urlImagen VARCHAR(250) NULL,
  FechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FechaActualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (ID),
  FOREIGN KEY (UsuarioID) REFERENCES usuarios(id),
  FOREIGN KEY (Estado) REFERENCES estados(ID)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

  INSERT INTO `estados` (`ID`, `Nombre`) VALUES (1, 'En Progreso'), (2, 'Bloqueada'), (3, 'Pendiente'), (4, 'Imcompleta');


