SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS lineas_pedidos;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `usuarios` (
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `nombre`      VARCHAR(60)      NOT NULL,
    `apellidos`   VARCHAR(60)      NOT NULL,
    `email`       VARCHAR(255)     NOT NULL,
    `password`    VARCHAR(255)     NOT NULL,
    `rol`         ENUM('admin','usuario') NOT NULL DEFAULT 'usuario',
    `confirmado`  BOOLEAN          NOT NULL DEFAULT FALSE,
    `token`       VARCHAR(255)     DEFAULT NULL,
    `token_exp`   DATETIME         DEFAULT NULL,
    `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_usuarios_email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    nombre       VARCHAR(100)     NOT NULL,
    descripcion  TEXT,
    created_at   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cat_nombre (nombre)
) ENGINE=InnoDB;

CREATE TABLE productos (
    id             INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    categoria_id   INT UNSIGNED     NOT NULL,
    nombre         VARCHAR(150)     NOT NULL,
    descripcion    TEXT,
    precio         DECIMAL(10,2)    NOT NULL,
    precio_oferta  DECIMAL(10,2)    DEFAULT NULL,
    stock          INT UNSIGNED     NOT NULL DEFAULT 0,
    activo         TINYINT(1)       NOT NULL DEFAULT 1,
    imagen         VARCHAR(255),
    created_at     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (categoria_id) REFERENCES categorias (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pedidos (
    id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    usuario_id    INT UNSIGNED     NOT NULL,
    provincia     VARCHAR(100)     NOT NULL,
    localidad     VARCHAR(100)     NOT NULL,
    direccion     VARCHAR(255)     NOT NULL,
    subtotal      DECIMAL(12,2)    NOT NULL DEFAULT 0.00,
    impuestos     DECIMAL(12,2)    NOT NULL DEFAULT 0.00,
    coste_total   DECIMAL(12,2)    NOT NULL DEFAULT 0.00,
    estado        ENUM('pendiente','confirmado','pagado','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
    fecha_pedido  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE lineas_pedidos (
    id               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    pedido_id        INT UNSIGNED  NOT NULL,
    producto_id      INT UNSIGNED  NOT NULL,
    unidades         SMALLINT UNSIGNED NOT NULL,
    precio_unitario  DECIMAL(10,2) NOT NULL,
    subtotal_linea   DECIMAL(12,2) NOT NULL, 
    PRIMARY KEY (id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos (id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos (id) ON DELETE CASCADE
) ENGINE=InnoDB;