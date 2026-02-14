CREATE INDEX IF NOT EXISTS idx_users_email ON users (email);
CREATE INDEX IF NOT EXISTS idx_users_created_at ON users (created_at);
CREATE INDEX IF NOT EXISTS idx_users_name ON users (name);

CREATE INDEX IF NOT EXISTS idx_queries_user_id ON queries (user_id);
CREATE INDEX IF NOT EXISTS idx_queries_created_at ON queries (created_at);
CREATE INDEX IF NOT EXISTS idx_queries_updated_at ON queries (updated_at);
CREATE INDEX IF NOT EXISTS idx_queries_title ON queries (title(50));
CREATE INDEX IF NOT EXISTS idx_queries_user_created ON queries (user_id, created_at);

ALTER TABLE queries
    ADD FULLTEXT INDEX IF NOT EXISTS ft_queries_sql_text (sql_text);
