-- ============================================================
-- WikiClaude database schema - SQL Server (T-SQL) version
-- Converted from src/assets/db/tables.sql (MariaDB/MySQL dump)
-- ============================================================

-- CREATE DATABASE wiki;
-- GO
-- USE wiki;
-- GO

-- ------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------

CREATE TABLE users (
    id       INT IDENTITY(1,1) NOT NULL,
    email    VARCHAR(60) NOT NULL,
    password VARCHAR(40) NOT NULL,
    name     VARCHAR(60) NOT NULL,
    CONSTRAINT PK_users PRIMARY KEY (id)
);
GO

SET IDENTITY_INSERT users ON;
INSERT INTO users (id, email, password, name) VALUES
(1, 'rens@mail.com', 'password123', 'Rens van Eck'),
(2, 'claude@mail.com', 'clanker123', 'Claude Shannon'),
(4, 'iwrotec', 'ritchie@mail.com', 'Dennis Ritchie');
SET IDENTITY_INSERT users OFF;
GO

-- ------------------------------------------------------------
-- Table: articles
-- ------------------------------------------------------------

CREATE TABLE articles (
    id          INT IDENTITY(1,1) NOT NULL,
    name        VARCHAR(60) NOT NULL,
    description VARCHAR(1023) NOT NULL,
    [text]      VARCHAR(MAX) NOT NULL,
    code        VARCHAR(MAX) NOT NULL,
    user_id     INT NOT NULL,
    last_edit   DATETIME2(6) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT PK_articles PRIMARY KEY (id),
    CONSTRAINT FK_articles_users FOREIGN KEY (user_id) REFERENCES users (id)
);
GO

CREATE INDEX IX_articles_user_id ON articles (user_id);
GO

-- MySQL's "ON UPDATE current_timestamp" auto-refresh has no direct T-SQL
-- equivalent; a trigger reproduces the same behavior on UPDATE.
CREATE TRIGGER TRG_articles_last_edit
ON articles
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE a
    SET a.last_edit = SYSUTCDATETIME()
    FROM articles a
    INNER JOIN inserted i ON a.id = i.id;
END;
GO

SET IDENTITY_INSERT articles ON;
INSERT INTO articles (id, name, description, [text], code, user_id, last_edit) VALUES
(1, 'Getting Started with Docker', 'A beginner-friendly guide to running containers with Docker.', 'Full article body text goes here...', 'docker run -d -p 80:80 nginx', 1, '2026-04-22 12:49:25.194750'),
(2, 'Testing Creation', 'Description test', 'Text test', 'Code test', 2, '2026-04-28 14:00:08.561119'),
(3, 'Test Adding Article to DB', 'Description Lorem Ipsum', 'Explaining Lorem Ipsum', 'echo ''hello'';', 1, '2026-05-08 08:41:13.964292'),
(11, 'Installing OpenClaw', 'How to install OpenClaw', 'Bit more text about installing OpenClaw', 'sudo run openclaw', 1, '2026-05-13 13:31:10.106020');
SET IDENTITY_INSERT articles OFF;
GO

-- ------------------------------------------------------------
-- Table: images
-- ------------------------------------------------------------

CREATE TABLE images (
    id            INT IDENTITY(1,1) NOT NULL,
    description   VARCHAR(255) NOT NULL,
    last_edit     DATETIME2(6) NOT NULL DEFAULT SYSUTCDATETIME(),
    path          VARCHAR(255) NOT NULL,
    article_id    INT NOT NULL,
    display_order INT NOT NULL,
    CONSTRAINT PK_images PRIMARY KEY (id),
    CONSTRAINT FK_images_articles FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE
);
GO

CREATE INDEX IX_images_article_id ON images (article_id);
GO

CREATE TRIGGER TRG_images_last_edit
ON images
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE im
    SET im.last_edit = SYSUTCDATETIME()
    FROM images im
    INNER JOIN inserted i ON im.id = i.id;
END;
GO

SET IDENTITY_INSERT images ON;
INSERT INTO images (id, description, last_edit, path, article_id, display_order) VALUES
(1, 'First description', '2026-05-12 09:53:26.890435', '"\Images\limit.png"', 1, 1),
(2, 'Second description', '2026-05-12 09:53:44.433934', '"\Images\olifant.jpg"', 1, 2);
SET IDENTITY_INSERT images OFF;
GO

-- ------------------------------------------------------------
-- Table: tags
-- ------------------------------------------------------------

CREATE TABLE tags (
    id  INT IDENTITY(1,1) NOT NULL,
    tag VARCHAR(255) NOT NULL,
    CONSTRAINT PK_tags PRIMARY KEY (id)
);
GO

SET IDENTITY_INSERT tags ON;
INSERT INTO tags (id, tag) VALUES
(1, 'php'),
(2, 'oop'),
(3, 'docker'),
(4, 'devops'),
(5, 'containers'),
(6, 'python');
SET IDENTITY_INSERT tags OFF;
GO

-- ------------------------------------------------------------
-- Table: join_articles_tags
-- ------------------------------------------------------------

CREATE TABLE join_articles_tags (
    article_id INT NOT NULL,
    tag_id     INT NOT NULL,
    CONSTRAINT UQ_join_articles_tags UNIQUE (article_id, tag_id),
    CONSTRAINT FK_join_articles_tags_articles FOREIGN KEY (article_id) REFERENCES articles (id),
    CONSTRAINT FK_join_articles_tags_tags FOREIGN KEY (tag_id) REFERENCES tags (id)
);
GO

CREATE INDEX IX_join_articles_tags_tag_id ON join_articles_tags (tag_id);
GO

INSERT INTO join_articles_tags (article_id, tag_id) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(11, 4),
(11, 5),
(11, 6);
GO

-- ------------------------------------------------------------
-- Table: ratings
-- ------------------------------------------------------------

CREATE TABLE ratings (
    user_id    INT NOT NULL,
    article_id INT NOT NULL,
    rating     INT NOT NULL,
    [timestamp] DATETIME2(0) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT UQ_ratings UNIQUE (user_id, article_id),
    CONSTRAINT FK_ratings_articles FOREIGN KEY (article_id) REFERENCES articles (id),
    CONSTRAINT FK_ratings_users FOREIGN KEY (user_id) REFERENCES users (id)
);
GO

CREATE INDEX IX_ratings_article_id ON ratings (article_id);
GO

CREATE TRIGGER TRG_ratings_timestamp
ON ratings
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE r
    SET r.[timestamp] = SYSUTCDATETIME()
    FROM ratings r
    INNER JOIN inserted i ON r.user_id = i.user_id AND r.article_id = i.article_id;
END;
GO

-- ------------------------------------------------------------
-- Table: _form_fields
-- ------------------------------------------------------------

CREATE TABLE _form_fields (
    page_id     INT NOT NULL,
    label       VARCHAR(255) NOT NULL,
    [type]      VARCHAR(255) NOT NULL,
    name        VARCHAR(255) NOT NULL,
    placeholder VARCHAR(255) NOT NULL,
    sort_order  INT NULL DEFAULT 0
);
GO

INSERT INTO _form_fields (page_id, label, [type], name, placeholder, sort_order) VALUES
(3, 'name', 'text', 'Name:', 'Enter your name', 1),
(3, 'email', 'email', 'Email address:', 'Enter your email address', 2),
(3, 'message', 'textarea', 'Message:', 'Enter your message', 3),
(4, 'email', 'email', 'Email address:', 'Enter your email address', 1),
(4, 'password', 'password', 'Password:', 'Enter your password', 2),
(5, 'name', 'text', 'Name:', 'Enter your name', 1),
(5, 'email', 'email', 'Email address:', 'Enter your email address', 2),
(5, 'password', 'password', 'Password:', 'Enter your password', 3),
(5, 'password_repeat', 'password', 'Repeat password:', 'Repeat your password', 4),
(7, 'text_edit', 'textarea', 'Article text:', 'Write your article text here', 1),
(7, 'code_edit', 'textarea', 'Code:', 'Write your code example here', 2),
(7, 'tag', 'text', 'Add tag:', 'Select tag or add a new one', 3);
GO

-- ------------------------------------------------------------
-- Table: _hamburger_items
-- ------------------------------------------------------------

CREATE TABLE _hamburger_items (
    page     VARCHAR(100) NOT NULL,
    name     VARCHAR(100) NOT NULL,
    li_class VARCHAR(100) NOT NULL,
    a_class  VARCHAR(100) NOT NULL
);
GO

INSERT INTO _hamburger_items (page, name, li_class, a_class) VALUES
('home', 'Home', 'nav-item', 'nav-link'),
('about', 'About', 'nav-item', 'nav-link'),
('contact', 'Contact', 'nav-item', 'nav-link');
GO

-- ------------------------------------------------------------
-- Table: _nav_items
-- ------------------------------------------------------------

CREATE TABLE _nav_items (
    page      VARCHAR(100) NOT NULL,
    name      VARCHAR(100) NOT NULL,
    li_class  VARCHAR(100) NOT NULL,
    a_class   VARCHAR(100) NOT NULL,
    logged_in BIT NOT NULL
);
GO

INSERT INTO _nav_items (page, name, li_class, a_class, logged_in) VALUES
('my_articles', 'My Articles', 'nav-item', 'nav-link', 1),
('logout', 'Logout', 'nav-item', 'nav-link', 1),
('login', 'Login', 'nav-item', 'nav-link', 0),
('register', 'Register', 'nav-item', 'nav-link', 0);
GO

-- ------------------------------------------------------------
-- Table: _pages
-- ------------------------------------------------------------

CREATE TABLE _pages (
    id   INT NOT NULL,
    page VARCHAR(255) NOT NULL
);
GO

INSERT INTO _pages (id, page) VALUES
(1, 'home'),
(2, 'about'),
(3, 'contact'),
(4, 'login'),
(5, 'register'),
(6, 'article'),
(7, 'edit'),
(8, 'search'),
(9, 'search_results'),
(10, 'author_page');
GO

-- ------------------------------------------------------------
-- Table: _page_footer_logo_path
-- ------------------------------------------------------------

CREATE TABLE _page_footer_logo_path (
    logo_path VARCHAR(255) NOT NULL,
    footer    INT NOT NULL
);
GO

INSERT INTO _page_footer_logo_path (logo_path, footer) VALUES
('Images/mdj_logo.png', 0);
GO

-- ------------------------------------------------------------
-- Table: _page_title_description
-- ------------------------------------------------------------

CREATE TABLE _page_title_description (
    page             VARCHAR(100) NOT NULL,
    page_title       VARCHAR(100) NOT NULL,
    page_description VARCHAR(1000) NOT NULL
);
GO

INSERT INTO _page_title_description (page, page_title, page_description) VALUES
('home', 'Home', 'Home description'),
('about', 'About', 'About description'),
('contact', 'Contact', 'Contact description'),
('login', 'Login', ''),
('register', 'Register', ''),
('edit', 'Edit Article', ''),
('search', 'Search', 'Search articles by author and tag.'),
('my_articles', 'My Articles', 'Manage the articles you have written.');
GO
