--- nano_user.pgsql - postgresql script to create tables for nano user app.
---
--- author: albert r. carnier guedes (albert@teko.net.br)
--- 

-- Drop existing tables.
DROP TABLE IF EXISTS nano_user_users;

---
--- Create the table for users.
---
CREATE TABLE nano_user_users (
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
INSERT INTO nano_user_users (created_at,updated_at,name,username,email,password,is_active) VALUES 
(NOW(),NOW(),'John Smith','john','johnsmith@fakemail.com',MD5('john'),true),
(NOW(),NOW(),'José da Silva','jose','jose@fakemail.com',MD5('jose'),true),
(NOW(),NOW(),'Maria da Silva','maria','maria@fakemail.com',MD5('maria'),true);
