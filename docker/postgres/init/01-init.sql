-- Initialize PostgreSQL database for InternLink
-- This script runs when the database container is first created

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-- Set timezone
SET timezone = 'UTC';

-- Create additional users if needed (optional)
-- CREATE USER internlink_readonly WITH PASSWORD 'readonly_password';
-- GRANT CONNECT ON DATABASE internlink TO internlink_readonly;
-- GRANT USAGE ON SCHEMA public TO internlink_readonly;
-- GRANT SELECT ON ALL TABLES IN SCHEMA public TO internlink_readonly;
-- ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT ON TABLES TO internlink_readonly;
