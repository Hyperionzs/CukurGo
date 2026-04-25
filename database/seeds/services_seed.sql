-- ============================================================
-- CukurGo - Services Seeder
-- Layanan barbershop umum di Indonesia
-- Jalankan: mysql -h 127.0.0.1 -P 3308 -u root cukurgo < database/seeds/services_seed.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE services;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO services (name, price) VALUES
('Potong Rambut Reguler',    35000),
('Potong Rambut + Cuci',     50000),
('Cukur Jenggot / Jambang',  25000),
('Potong + Cukur Jenggot',   55000),
('Creambath / Hair Spa',      65000),
('Pewarnaan Rambut (Cat)',   120000),
('Smoothing / Rebonding',   200000),
('Pomade Styling',            15000);
