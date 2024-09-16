CREATE DATABASE descargas;

USE descargas;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    filename VARCHAR(255) NOT NULL
);


DELIMITER $$
CREATE OR REPLACE PROCEDURE nuevo_admin(p_login VARCHAR(20), p_pass VARCHAR(255))
BEGIN
    INSERT INTO admins(username, password) VALUES( LOWER(p_login), p_pass);
END $$

CREATE OR REPLACE PROCEDURE borrar_admin(p_id INT)
BEGIN
    IF (SELECT COUNT(*) FROM admins) > 1 THEN
        DELETE FROM admins WHERE id = p_id;
    END IF;
END; $$

DELIMITER ;

