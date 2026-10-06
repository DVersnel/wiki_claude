-- ============================================================
-- Role-based article access (SQL Server)
--
-- Roles are bit flags (must match ArticleReviewApp/Models/Role.cs):
--   1 = Reader, 2 = Reviewer, 4 = Editor, 8 = Admin   (15 = all)
--
-- users.roles          = the roles a user holds (OR the bits together)
-- articles.access_mask = the roles allowed to access that article
--
-- A user can access an article when the masks share at least one bit:
--   WHERE articles.access_mask & @user_roles <> 0
--
-- Admins only see an article if its mask includes the Admin bit (8);
-- the default of 15 includes it.
-- ============================================================

ALTER TABLE users
    ADD roles INT NOT NULL CONSTRAINT DF_users_roles DEFAULT 1;   -- Reader
GO

ALTER TABLE articles
    ADD access_mask INT NOT NULL CONSTRAINT DF_articles_access_mask DEFAULT 15;   -- everyone
GO

-- ------------------------------------------------------------
-- Example assignments - adjust to your own users/articles.
-- ------------------------------------------------------------
-- UPDATE users SET roles = 8     WHERE email = 'rens@mail.com';    -- Admin
-- UPDATE users SET roles = 2 | 4 WHERE email = 'claude@mail.com';  -- Reviewer + Editor
--
-- UPDATE articles SET access_mask = 4 | 8 WHERE id = 11;           -- Editors and Admins only
--
-- Grant / revoke a single role without touching the others:
-- UPDATE users SET roles = roles | 2  WHERE id = 1;                -- add Reviewer
-- UPDATE users SET roles = roles & ~2 WHERE id = 1;                -- remove Reviewer
