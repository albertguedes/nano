--- nano_admin.pgsql - postgresql script to create tables for nano admin app.
---
--- author: albert r. carnier guedes (albert@teko.net.br)
--- 

-- Drop existing tables.
DROP TABLE IF EXISTS nano_admin_users;

---
--- Create the table for users.
---
CREATE TABLE nano_admin_users (
    id         SERIAL PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     
    name       VARCHAR(255) NOT NULL,    
    username   VARCHAR(255) UNIQUE NOT NULL,
    email      VARCHAR(255) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    is_active  BOOLEAN NOT NULL DEFAULT 't'
);

-- 
-- Create sample fake users ( and a true admin user ).
-- 
INSERT INTO nano_admin_users (created_at,updated_at,name,username,email,password,is_active) VALUES 
(NOW(),NOW(),'Administrator','admin','admin@fakemail.com',MD5('admin'),true),
(NOW(),NOW(),'John Smith','john','johnsmith@fakemail.com',MD5('john'),true);
