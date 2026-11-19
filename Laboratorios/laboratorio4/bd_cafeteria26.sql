-- =====================================================================
-- SIS-256 - Laboratorio 4 - Base de datos de la Cafeteria USFX
-- Importar desde phpMyAdmin (pestana Importar). Crea la base de datos,
-- las dos tablas y los datos de prueba.
-- =====================================================================

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS bd_cafeteria26;
CREATE DATABASE bd_cafeteria26 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bd_cafeteria26;

CREATE TABLE categorias (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE productos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(60)  NOT NULL,
  categoria_id INT          NOT NULL,
  precio       DECIMAL(6,2) NOT NULL,
  descripcion  VARCHAR(255) NOT NULL DEFAULT '',
  imagen       VARCHAR(100) NOT NULL DEFAULT '',
  disponible   TINYINT(1)   NOT NULL DEFAULT 1,
  CONSTRAINT fk_productos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

INSERT INTO categorias (id, nombre) VALUES
(1, 'Salados'),
(2, 'Platos'),
(3, 'Postres');

INSERT INTO productos (nombre, categoria_id, precio, descripcion, imagen, disponible) VALUES
('Empanadas de queso',   1,  6.00, 'Dos empanadas horneadas, rellenas de queso criollo.',            'empanadas.jpg',   1),
('Papas fritas',         1,  8.00, 'Porción de papas fritas con llajua de la casa.',                  'papas.jpg',       1),
('Croquetas de pollo',   1, 10.00, 'Seis croquetas doradas con salsa golf.',                          'croquetas.jpg',   1),
('Hamburguesa clásica',  1, 18.00, 'Carne de res, queso, lechuga y tomate en pan artesanal.',         'hamburguesa.jpg', 1),
('Sopa de pollo',        2, 12.00, 'Caldo casero con verduras y fideo.',                              'sopa.jpg',        1),
('Pollo al horno',       2, 22.00, 'Presa de pollo al horno con arroz y ensalada.',                   'pollo.jpg',       1),
('Spaghetti carbonara',  2, 20.00, 'Pasta con salsa de huevo, queso y tocino.',                       'espagueti.jpg',   1),
('Ensalada fresca',      2, 15.00, 'Lechuga, tomate, pepino, palta y aderezo de limón.',              'ensalada.jpg',    0),
('Flan casero',          3,  7.00, 'Flan de leche con caramelo.',                                     'flan.jpg',        1),
('Tiramisú',             3, 12.00, 'Capas de crema mascarpone y bizcochuelo con café.',               'tiramisu.jpg',    1),
('Torta de chocolate',   3, 10.00, 'Tajada de torta humeda de chocolate.',                            'chocolate.jpg',   1),
('Brownie',              3,  8.00, 'Brownie de chocolate con nueces.',                                'brownis.jpg',     1),
('Crepes con frutas',    3, 12.00, 'Dos crepes rellenos de frutas de temporada.',                     'crepes.jpg',      1),
('Panqueques con miel',  3, 10.00, 'Tres panqueques con miel y mantequilla.',                         'panqueques.jpg',  0),
('Frutillas con crema',  3,  9.00, 'Frutillas frescas con crema chantilly.',                          'frutillas.jpg',   1),
('Helado de vainilla',   3,  8.00, 'Dos bolas de helado artesanal de vainilla.',                      'vainilla.jpg',    1);
