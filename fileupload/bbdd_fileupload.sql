CREATE DATABASE IF NOT EXISTS entregas;
USE entregas;

CREATE TABLE entregas(
id INT PRIMARY KEY AUTO_INCREMENT,
nombre VARCHAR(100) UNIQUE NOT NULL,
ruta_directorio VARCHAR(200) NOT NULL,
max_size FLOAT UNSIGNED DEFAULT 3.2);

CREATE TABLE uploads(
id INT PRIMARY KEY AUTO_INCREMENT,
codigo_alumno VARCHAR(10),
fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
nombre_fichero VARCHAR(100),
id_entrega INT,
FOREIGN KEY (id_entrega) REFERENCES entregas(id) ON DELETE RESTRICT
);

CREATE TABLE uploads_eliminados(
id INT PRIMARY KEY AUTO_INCREMENT,
codigo_alumno VARCHAR(10),
alumno VARCHAR(90),
fecha_envio TIMESTAMP,
nombre_fichero VARCHAR(100),
ruta_directorio VARCHAR(200),
fecha_borrado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
nombre_entrega VARCHAR(100)
);

CREATE TABLE admins(
id INT PRIMARY KEY AUTO_INCREMENT,
login VARCHAR(20) UNIQUE,
pass VARCHAR(255)
);

CREATE TABLE configs(
id INT PRIMARY KEY AUTO_INCREMENT,
permitir_envios BOOLEAN DEFAULT FALSE,
control_alumnos BOOLEAN DEFAULT FALSE,
entrega_unica BOOLEAN DEFAULT TRUE,
extensiones_permitidas VARCHAR(200) DEFAULT 'zip,rar,txt,sql,java,pdf,docx',
fecha_fin_entrega DATETIME,
id_entrega_actual INT,
FOREIGN KEY (id_entrega_actual) REFERENCES entregas(id)
);

CREATE TABLE alumnos(
id INT PRIMARY KEY AUTO_INCREMENT,
codigo VARCHAR(10) UNIQUE,
nombre VARCHAR(40),
apellidos VARCHAR(50),
curso VARCHAR(30),
entregas_realizadas INT DEFAULT 0
);

DELIMITER $$

CREATE FUNCTION envios_activados()
RETURNS BOOLEAN
BEGIN
	
RETURN (SELECT permitir_envios FROM configs ORDER BY id DESC LIMIT 1);

END; $$

CREATE OR REPLACE PROCEDURE set_envios_activados(p_flag BOOLEAN)
BEGIN
	UPDATE configs
    SET permitir_envios = p_flag
    WHERE id = (SELECT id FROM configs ORDER BY id DESC LIMIT 1);
END; $$


CREATE FUNCTION control_alumnos_activado()
RETURNS BOOLEAN
BEGIN
	
RETURN (SELECT control_alumnos FROM configs ORDER BY id DESC LIMIT 1);

END; $$

CREATE OR REPLACE PROCEDURE set_control_alumnos(p_flag BOOLEAN)
BEGIN
	UPDATE configs
    SET control_alumnos = p_flag
    WHERE id = (SELECT id FROM configs ORDER BY id DESC LIMIT 1);
END; $$

CREATE OR REPLACE FUNCTION existe_codigo_alumno(p_codigo VARCHAR(10))
RETURNS BOOLEAN
BEGIN
	IF LOWER(p_codigo) IN (SELECT codigo FROM alumnos) THEN
		RETURN TRUE;
	END IF;
    
    RETURN FALSE;
END; $$

CREATE FUNCTION entrega_unica_activada()
RETURNS BOOLEAN
BEGIN
	
RETURN (SELECT entrega_unica FROM configs ORDER BY id DESC LIMIT 1);

END; $$

CREATE OR REPLACE FUNCTION extensiones_permitidas()
RETURNS VARCHAR(200)
BEGIN
	
RETURN (SELECT extensiones_permitidas FROM configs ORDER BY id DESC LIMIT 1);

END; $$

CREATE OR REPLACE PROCEDURE set_extensiones_permitidas(p_extensiones VARCHAR(200))
BEGIN
	UPDATE configs
    SET extensiones_permitidas = LOWER(p_extensiones)
    WHERE id = (SELECT id FROM configs ORDER BY id DESC LIMIT 1);
END; $$

CREATE OR REPLACE PROCEDURE set_entrega_unica(p_flag BOOLEAN)
BEGIN
	UPDATE configs
    SET entrega_unica = p_flag
    WHERE id = (SELECT id FROM configs ORDER BY id DESC LIMIT 1);
END; $$

CREATE OR REPLACE FUNCTION nombre_entrega_actual()
RETURNS VARCHAR(100)
BEGIN
	RETURN (SELECT nombre FROM entregas, configs
			WHERE configs.id_entrega_actual = entregas.id
            ORDER BY configs.id DESC LIMIT 1);
END; $$


CREATE OR REPLACE FUNCTION codigo_ya_entregado(p_codigo VARCHAR(10))
RETURNS BOOLEAN
BEGIN
	DECLARE v_cantidad INT;
	SET v_cantidad = (SELECT COUNT(*) FROM uploads u, entregas e 
						WHERE u.id_entrega = e.id
                        AND codigo_alumno = LOWER(p_codigo)
                        AND e.nombre = nombre_entrega_actual());
	if v_cantidad > 0 THEN
		RETURN TRUE;
	END IF;
    
    RETURN FALSE;

END; $$

CREATE OR REPLACE FUNCTION nombre_entrega_actual()
RETURNS VARCHAR(100)
BEGIN
	RETURN (SELECT nombre FROM entregas, configs
			WHERE configs.id_entrega_actual = entregas.id
            ORDER BY configs.id DESC LIMIT 1);
END; $$

CREATE OR REPLACE FUNCTION ruta_entrega_actual()
RETURNS VARCHAR(200)
BEGIN
	RETURN (SELECT ruta_directorio FROM entregas, configs
			WHERE configs.id_entrega_actual = entregas.id
            ORDER BY configs.id DESC LIMIT 1);
END; $$

CREATE OR REPLACE FUNCTION maxsize_entrega_actual()
RETURNS FLOAT
BEGIN
	RETURN (SELECT max_size FROM entregas, configs
			WHERE configs.id_entrega_actual = entregas.id
            ORDER BY configs.id DESC LIMIT 1);
END; $$

CREATE OR REPLACE PROCEDURE set_maxsize_entrega_actual(p_size FLOAT)
proc: BEGIN
	IF p_size < 0.1 OR p_size > 5.0 THEN
		leave proc;
	END IF;
    
	UPDATE entregas
    SET max_size = p_size
    WHERE nombre = nombre_entrega_actual();

END proc; $$

CREATE OR REPLACE PROCEDURE nueva_entrega(p_nombre VARCHAR(100), p_ruta VARCHAR(200), p_max_size FLOAT)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
    END;

-- quizás falte la fecha

	START TRANSACTION;
		CALL set_envios_activados(0);
		INSERT INTO entregas(nombre, ruta_directorio, max_size)
        VALUES(p_nombre, p_ruta, p_max_size);
        INSERT INTO configs(id_entrega_actual)
        VALUES(LAST_INSERT_ID());
    COMMIT;

END; $$

CREATE OR REPLACE PROCEDURE eliminar_entrega(p_nombre VARCHAR(100))
BEGIN
	DECLARE v_id_entrega INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
    END;

	SET v_id_entrega = (SELECT id FROM entregas WHERE nombre = p_nombre);

	START TRANSACTION;
		DELETE FROM uploads WHERE id_entrega = v_id_entrega;
        DELETE FROM configs WHERE id_entrega_actual = v_id_entrega;
        DELETE FROM entregas WHERE id = v_id_entrega;
    COMMIT;

END; $$

CREATE OR REPLACE PROCEDURE registrar_upload(p_codigo VARCHAR(10), p_fecha TIMESTAMP, p_fichero VARCHAR(100))
BEGIN
	DECLARE v_id_entrega_actual INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
    END;
    
    SET v_id_entrega_actual = (SELECT id_entrega_actual 
								FROM configs 
                                ORDER BY configs.id DESC LIMIT 1);
	START TRANSACTION;
		INSERT INTO uploads(codigo_alumno, fecha_envio, nombre_fichero, id_entrega)
		VALUES(LOWER(p_codigo), p_fecha, p_fichero, v_id_entrega_actual);
		UPDATE alumnos
		SET entregas_realizadas = entregas_realizadas + 1
		WHERE codigo = LOWER(p_codigo);
    COMMIT;

END; $$

CREATE OR REPLACE PROCEDURE nuevo_admin(p_login VARCHAR(20), p_pass VARCHAR(255))
BEGIN
	INSERT INTO admins(login, pass) VALUES( LOWER(p_login), p_pass);
END $$

CREATE OR REPLACE PROCEDURE nuevo_alumno(p_codigo VARCHAR(10), p_nombre VARCHAR(40), p_apellidos VARCHAR(50), p_curso VARCHAR(30))
BEGIN
	INSERT INTO alumnos(codigo, nombre, apellidos, curso) VALUES(LOWER(REPLACE(p_codigo,' ', '' )), p_nombre, p_apellidos, LOWER(REPLACE(p_curso,' ', '' )));
END $$

CREATE OR REPLACE PROCEDURE modificar_alumno(p_codigo VARCHAR(10), p_nombre VARCHAR(40), p_apellidos VARCHAR(50), p_curso VARCHAR(30), p_id INT)
BEGIN
	UPDATE alumnos 
    SET codigo = LOWER(REPLACE(p_codigo,' ', '' )), nombre = p_nombre, 
    apellidos = p_apellidos, curso = LOWER(REPLACE(p_curso,' ', '' ))
    WHERE id = p_id;
END $$

CREATE OR REPLACE PROCEDURE borrar_admin(p_id INT)
BEGIN
	IF (SELECT COUNT(*) FROM admins) > 1 THEN
		DELETE FROM admins WHERE id = p_id;
	END IF;
END; $$

CREATE OR REPLACE TRIGGER upload_eliminado BEFORE DELETE
ON uploads FOR EACH ROW
BEGIN
	DECLARE v_nombre VARCHAR(100);
    DECLARE v_directorio VARCHAR(200);
    DECLARE v_alumno VARCHAR(90) DEFAULT NULL;
    DECLARE v_alumno_registrado INT;
    
    SET v_alumno_registrado = (SELECT COUNT(*) FROM alumnos WHERE codigo = OLD.codigo_alumno);
    IF v_alumno_registrado > 0 THEN
		SET v_alumno = (SELECT CONCAT(nombre, ' ', apellidos) FROM alumnos WHERE codigo = OLD.codigo_alumno);
    END IF;
        
	SELECT nombre, ruta_directorio INTO v_nombre, v_directorio FROM entregas
					WHERE id = OLD.id_entrega;
                    
	INSERT INTO uploads_eliminados(codigo_alumno, alumno, fecha_envio, nombre_fichero, ruta_directorio, nombre_entrega)
    VALUES(OLD.codigo_alumno, v_alumno, OLD.fecha_envio, OLD.nombre_fichero, v_directorio, v_nombre);
END ; $$


CREATE OR REPLACE TRIGGER actualizar_codigos_envios_alumnos AFTER UPDATE
ON alumnos FOR EACH ROW
BEGIN
	
	UPDATE uploads
    SET codigo_alumno = NEW.codigo
    WHERE codigo_alumno = OLD.codigo;
    
END ; $$

DELIMITER ;