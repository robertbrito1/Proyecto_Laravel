/*
 Script de usuarios iniciales para SQLite.
 Contraseña temporal para todos los usuarios: password
*/

INSERT OR IGNORE INTO departments (name, code, is_active, created_at, updated_at)
VALUES
('Administracion', 'ADM', 1, datetime('now'), datetime('now')),
('Informatica', 'INF', 1, datetime('now'), datetime('now')),
('Sanidad', 'SAN', 1, datetime('now'), datetime('now')),
('Coordinacion FFE', 'FFE', 1, datetime('now'), datetime('now'));

-- Insertar usuarios con los correos solicitados por el usuario
INSERT INTO users (department_id, role, name, email, phone, password, is_active, email_verified_at, created_at, updated_at)
VALUES
((SELECT id FROM departments WHERE code = 'ADM' LIMIT 1), 'administrador', 'Administrador Sistema', 'admin@ffe.local', '600100001', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'ADM' LIMIT 1), 'direccion', 'Direccion del Centro', 'direccion@ffe.local', '600100002', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'FFE' LIMIT 1), 'coordinadorFFE', 'Coordinacion FFE', 'coordinacion@ffe.local', '600100003', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'INF' LIMIT 1), 'tutor', 'Tutor DAM', 'tutor@ffe.local', '600100004', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'INF' LIMIT 1), 'profesor', 'Profesor DAW', 'profesor@ffe.local', '600100005', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'ADM' LIMIT 1), 'secretaria', 'Secretaria Centro', 'secretaria@ffe.local', '600100006', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now')),
((SELECT id FROM departments WHERE code = 'SAN' LIMIT 1), 'empresa', 'Empresa Colaboradora', 'empresa@ffe.local', '600100007', '$2y$10$KvlrB8likzH7/YDQG3wRnuncws6jwDVQ66dMevVLFoGg1D9eD4hze', 1, datetime('now'), datetime('now'), datetime('now'))
ON CONFLICT(email) DO UPDATE SET
name = excluded.name,
role = excluded.role,
department_id = excluded.department_id,
phone = excluded.phone,
password = excluded.password,
is_active = excluded.is_active,
email_verified_at = excluded.email_verified_at,
updated_at = datetime('now');
