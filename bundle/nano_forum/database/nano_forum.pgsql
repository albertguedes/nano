--- nano_forum.pgsql - postgresql script to create tables for nano forum app.
---
--- author: albert r. c. guedes (albert@teko.net.br)
--- 

-- Drop existing tables.
DROP TABLE IF EXISTS nano_forum_posts;
DROP TABLE IF EXISTS nano_forum_topics;
DROP TABLE IF EXISTS nano_forum_forums;
DROP TABLE IF EXISTS nano_forum_users;

---
--- Create the table for users.
---
CREATE TABLE nano_forum_users (
    id         SERIAL PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     
    name       VARCHAR(255) NOT NULL,    
    username   VARCHAR(255) UNIQUE NOT NULL,
    email      VARCHAR(255) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    is_active  BOOLEAN NOT NULL DEFAULT 't'
);

---
--- Create the table for forums.
---
CREATE TABLE nano_forum_forums (
    id          SERIAL PRIMARY KEY,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     
	author_id   INTEGER NOT NULL,
    title       VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    published   BOOLEAN NOT NULL DEFAULT 't',
    CONSTRAINT  fk_author FOREIGN KEY("author_id") REFERENCES nano_forum_users("id")
);

---
--- Create the table for topics.
---
CREATE TABLE nano_forum_topics (
    id          SERIAL PRIMARY KEY,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     
	author_id   INTEGER NOT NULL,
  	forum_id    INTEGER NOT NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    published   BOOLEAN NOT NULL DEFAULT 't',
    CONSTRAINT  fk_author FOREIGN KEY("author_id") REFERENCES nano_forum_users("id"),
    CONSTRAINT  fk_forum FOREIGN KEY("forum_id") REFERENCES nano_forum_forums("id"),
    UNIQUE ("forum_id","title")
);

---
--- Create the table for posts.
---
CREATE TABLE nano_forum_posts (
    id         SERIAL PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     
	author_id  INTEGER NOT NULL,
  	topic_id   INTEGER NOT NULL,
  	reply_id   INTEGER NULL,
    title      VARCHAR(255) NOT NULL,
    content    TEXT NOT NULL,
    published  BOOLEAN NOT NULL DEFAULT 't',
    CONSTRAINT fk_author FOREIGN KEY("author_id") REFERENCES nano_forum_users("id"),
    CONSTRAINT fk_topic FOREIGN KEY("topic_id") REFERENCES nano_forum_topics("id"),
    CONSTRAINT fk_reply FOREIGN KEY("reply_id") REFERENCES nano_forum_posts("id"),    
    UNIQUE ("topic_id","title")
);

-- 
-- Create sample fake users ( and a true admin user ).
--
INSERT INTO nano_forum_users (created_at,updated_at,name,username,email,password,is_active) VALUES 
(NOW(),NOW(),'Administrator','admin','admin@fakemail.com',MD5('admin'),true),
(NOW(),NOW(),'John Smith','john','johnsmith@fakemail.com',MD5('john'),true);


--- 
--- Create some fake forums.
---
INSERT INTO nano_forum_forums (created_at,updated_at,author_id,title,description,published) 
VALUES 
(NOW(),NOW(),2,'first forum','this is the first forum',true),
(NOW(),NOW(),2,'second forum','this is the second forum',true),
(NOW(),NOW(),2,'thirth forum','this is the thirth forum',true);

--- 
--- Create some fake topics.
---
INSERT INTO nano_forum_topics (created_at,updated_at,author_id,forum_id,title,description,published) 
VALUES 
(NOW(),NOW(),2,1,'first topic of first forum','this is the first topic of first forum',true),
(NOW(),NOW(),2,1,'second topic of first forum','this is the second topic of first forum',true),
(NOW(),NOW(),2,1,'thirth topic of first forum','this is the second topic of first forum',true),

(NOW(),NOW(),2,2,'first topic of second forum','this is the first topic of second forum',true),
(NOW(),NOW(),2,2,'second topic of second forum','this is the second topic of second forum',true),
(NOW(),NOW(),2,2,'thirth topic of second forum','this is the second topic of second forum',true),

(NOW(),NOW(),2,3,'first topic of thirth forum','this is the first topic of thirth forum',true),
(NOW(),NOW(),2,3,'second topic of thirth forum','this is the second topic of thirth forum',true),
(NOW(),NOW(),2,3,'thirth topic of thirth forum','this is the thirth topic of thirth forum',true);

--- 
--- Create some fake posts.
---
INSERT INTO nano_forum_posts (created_at,updated_at,author_id,topic_id,reply_id,title,content,published) 
VALUES 
(NOW(),NOW(),2,1,null,'first  post of first  topic of first forum', 'this is the first post of first topic of first forum',true),
(NOW(),NOW(),2,1,null,'second post of first  topic of first forum', 'this is the second post of first topic of first forum',true),
(NOW(),NOW(),2,1,null,'thirth post of first  topic of first forum', 'this is the thirth post of first topic of first forum',true),
(NOW(),NOW(),2,2,null,'first  post of second topic of first forum', 'this is the first post of second topic of first forum',true),
(NOW(),NOW(),2,2,null,'second post of second topic of first forum', 'this is the second post of second topic of first forum',true),
(NOW(),NOW(),2,2,null,'thirth post of second topic of first forum', 'this is the thirth post of second topic of first forum',true),
(NOW(),NOW(),2,3,null,'first  post of thirth topic of first forum', 'this is the first post of thirth topic of first forum',true),
(NOW(),NOW(),2,3,null,'second post of thirth topic of first forum', 'this is the second post of thirth topic of first forum',true),
(NOW(),NOW(),2,3,null,'thirth post of thirth topic of first forum', 'this is the thirth post of thirth topic of first forum',true),
(NOW(),NOW(),2,4,null,'first  post of first  topic of second forum','this is the first post of first topic of second forum',true),
(NOW(),NOW(),2,4,null,'second post of first  topic of second forum','this is the second post of first topic of second forum',true),
(NOW(),NOW(),2,4,null,'thirth post of first  topic of second forum','this is the thirth post of first topic of second forum',true),
(NOW(),NOW(),2,5,null,'first  post of second topic of second forum','this is the first post of second topic of second forum',true),
(NOW(),NOW(),2,5,null,'second post of second topic of second forum','this is the second post of second topic of second forum',true),
(NOW(),NOW(),2,5,null,'thirth post of second topic of second forum','this is the thirth post of second topic of second forum',true),
(NOW(),NOW(),2,6,null,'first  post of thirth topic of second forum','this is the first post of thirth topic of second forum',true),
(NOW(),NOW(),2,6,null,'second post of thirth topic of second forum','this is the second post of thirth topic of second forum',true),
(NOW(),NOW(),2,6,null,'thirth post of thirth topic of second forum','this is the thirth post of thirth topic of second forum',true),
(NOW(),NOW(),2,7,null,'first  post of first  topic of thirth forum','this is the first post of first topic of thirth forum',true),
(NOW(),NOW(),2,7,null,'second post of first  topic of thirth forum','this is the second post of first topic of thirth forum',true),
(NOW(),NOW(),2,7,null,'thirth post of first  topic of thirth forum','this is the thirth post of first topic of thirth forum',true),
(NOW(),NOW(),2,8,null,'first  post of second topic of thirth forum','this is the first post of second topic of thirth forum',true),
(NOW(),NOW(),2,8,null,'second post of second topic of thirth forum','this is the second post of second topic of thirth forum',true),
(NOW(),NOW(),2,8,null,'thirth post of second topic of thirth forum','this is the thirth post of second topic of thirth forum',true),
(NOW(),NOW(),2,9,null,'first  post of thirth topic of thirth forum','this is the first post of thirth topic of thirth forum',true),
(NOW(),NOW(),2,9,null,'second post of thirth topic of thirth forum','this is the second post of thirth topic of thirth forum',true),
(NOW(),NOW(),2,9,null,'thirth post of thirth topic of thirth forum','this is the thirth post of thirth topic of thirth forum',true);
