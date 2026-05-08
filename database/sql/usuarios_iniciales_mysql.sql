/*
 Script de usuarios iniciales para MySQL/MariaDB.
 Contraseña temporal para todos los usuarios: password
*/

INSERT INTO departments (name, code, is_active, created_at, updated_at)
VALUES
('Administracion', 'ADM', 1, NOW(), NOW()),
('Informatica', 'INF', 1, NOW(), NOW()),
('Sanidad', 'SAN', 1, NOW(), NOW()),
('Coordinacion FFE', 'FFE', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
name = VALUES(name),
code = VALUES(code),
is_active = VALUES(is_active),
updated_at = NOW();

SET @dep_adm = (SELECT id FROM departments WHERE code = 'ADM' LIMIT 1);
SET @dep_inf = (SELECT id FROM departments WHERE code = 'INF' LIMIT 1);
SET @dep_san = (SELECT id FROM departments WHERE code = 'SAN' LIMIT 1);
SET @dep_ffe = (SELECT id FROM departments WHERE code = 'FFE' LIMIT 1);
SET @password_hash = '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze';

INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
VALUES
(@dep_adm, 'administrador', 'Administrador Sistema', 'admin.sistema@centro.local', '600100001', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_adm, 'direccion', 'Direccion del Centro', 'direccion.centro@centro.local', '600100002', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_ffe, 'coordinadorFFE', 'Coordinacion FFE', 'coordinacion.ffe@centro.local', '600100003', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_inf, 'tutor', 'Tutor DAM', 'tutor.dam@centro.local', '600100004', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_inf, 'profesor', 'Profesor DAW', 'profesor.daw@centro.local', '600100005', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_adm, 'secretaria', 'Secretaria Centro', 'secretaria.centro@centro.local', '600100006', @password_hash, 1, NOW(), NOW(), NOW()),
(@dep_san, 'empresa', 'Empresa Colaboradora', 'empresa.contacto@externa.local', '600100007', @password_hash, 1, NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE
name = VALUES(name),
role = VALUES(role),
department_id = VALUES(department_id),
phone = VALUES(phone),
password = VALUES(password),
is_active = VALUES(is_active),
email_verified_at = VALUES(email_verified_at),
updated_at = NOW();
