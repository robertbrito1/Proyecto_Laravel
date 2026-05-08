/*
 Script de usuarios iniciales para SQL Server.
 Importa departamentos y usuarios base sin depender de datos fijos definidos en Laravel.

 Contraseña temporal para todos los usuarios:
 password

 Puedes ejecutarlo directamente en SQL Server Management Studio.
*/

SET NOCOUNT ON;

IF NOT EXISTS (SELECT 1 FROM departments WHERE code = 'ADM')
BEGIN
    INSERT INTO departments (name, code, is_active, created_at, updated_at)
    VALUES ('Administracion', 'ADM', 1, GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM departments WHERE code = 'INF')
BEGIN
    INSERT INTO departments (name, code, is_active, created_at, updated_at)
    VALUES ('Informatica', 'INF', 1, GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM departments WHERE code = 'SAN')
BEGIN
    INSERT INTO departments (name, code, is_active, created_at, updated_at)
    VALUES ('Sanidad', 'SAN', 1, GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM departments WHERE code = 'FFE')
BEGIN
    INSERT INTO departments (name, code, is_active, created_at, updated_at)
    VALUES ('Coordinacion FFE', 'FFE', 1, GETDATE(), GETDATE());
END;

DECLARE @dep_adm BIGINT = (SELECT TOP 1 id FROM departments WHERE code = 'ADM');
DECLARE @dep_inf BIGINT = (SELECT TOP 1 id FROM departments WHERE code = 'INF');
DECLARE @dep_san BIGINT = (SELECT TOP 1 id FROM departments WHERE code = 'SAN');
DECLARE @dep_ffe BIGINT = (SELECT TOP 1 id FROM departments WHERE code = 'FFE');
DECLARE @password_hash NVARCHAR(255) = '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze';

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin.sistema@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_adm, 'administrador', 'Administrador Sistema', 'admin.sistema@centro.local', '600100001', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'direccion.centro@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_adm, 'direccion', 'Direccion del Centro', 'direccion.centro@centro.local', '600100002', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'coordinacion.ffe@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_ffe, 'coordinadorFFE', 'Coordinacion FFE', 'coordinacion.ffe@centro.local', '600100003', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'tutor.dam@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_inf, 'tutor', 'Tutor DAM', 'tutor.dam@centro.local', '600100004', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'profesor.daw@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_inf, 'profesor', 'Profesor DAW', 'profesor.daw@centro.local', '600100005', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'secretaria.centro@centro.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_adm, 'secretaria', 'Secretaria Centro', 'secretaria.centro@centro.local', '600100006', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;

IF NOT EXISTS (SELECT 1 FROM users WHERE email = 'empresa.contacto@externa.local')
BEGIN
    INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
    VALUES (@dep_san, 'empresa', 'Empresa Colaboradora', 'empresa.contacto@externa.local', '600100007', @password_hash, 1, GETDATE(), GETDATE(), GETDATE());
END;
