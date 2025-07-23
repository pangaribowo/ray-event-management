-- PostgreSQL Migration Script for Supabase
-- Event Management System - Optimized for Production
-- Convert dari MySQL ke PostgreSQL

-- Enable required extensions
CREATE EXTENSION IF NOT EXISTS pgcrypto;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Enable RLS (Row Level Security) untuk semua table
-- Ini adalah best practice untuk Supabase

-- =============================================================================
-- CONTACTS TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS contacts (
    id SERIAL PRIMARY KEY,
    business_block_id INTEGER NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    fax VARCHAR(20),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE contacts ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- BUSINESS BLOCKS TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS tbl_business_blocks (
    id SERIAL PRIMARY KEY,
    block_name VARCHAR(255) NOT NULL,
    account_type VARCHAR(50) CHECK (account_type IN ('Company', 'Travel Agent')) DEFAULT 'Company',
    account_name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    owner_event VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    revenue_room DECIMAL(10,2) DEFAULT 0.00,
    revenue_catering DECIMAL(10,2) DEFAULT 0.00,
    status VARCHAR(10) CHECK (status IN ('ACT', 'TEN', 'DEF', 'CXL')) DEFAULT 'ACT',
    owner_id INTEGER NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE tbl_business_blocks ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- EVENT BOOKINGS TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS event_bookings (
    id SERIAL PRIMARY KEY,
    business_block_id INTEGER NOT NULL,
    event_name VARCHAR(255) NOT NULL,
    function_space VARCHAR(255) NOT NULL,
    start_datetime TIMESTAMP WITH TIME ZONE NOT NULL,
    end_datetime TIMESTAMP WITH TIME ZONE NOT NULL,
    pax INTEGER NOT NULL,
    rental VARCHAR(10) CHECK (rental IN ('Include', 'Exclude')) DEFAULT 'Exclude',
    status VARCHAR(20) CHECK (status IN ('Pending', 'Approved', 'Cancelled')) DEFAULT 'Pending',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE event_bookings ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- EVENT NOTES TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS event_notes (
    id SERIAL PRIMARY KEY,
    event_booking_id INTEGER NOT NULL,
    department VARCHAR(50) CHECK (department IN (
        'Signage', 'FO', 'Engineer', 'FB Banquet & Service', 
        'FB Product', 'Housekeeping', 'HR & Security', 'Accounting'
    )) NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE event_notes ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- USERS TABLE - Optimized
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  uuid UUID UNIQUE DEFAULT uuid_generate_v4(),
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE,
  password TEXT NOT NULL, 
  role VARCHAR(20) CHECK (role IN ('admin', 'sales', 'user')) DEFAULT 'user',
  full_name VARCHAR(255),
  phone VARCHAR(20),
  is_active BOOLEAN DEFAULT true,
  last_login TIMESTAMP WITH TIME ZONE,
  password_reset_token TEXT,
  password_reset_expires TIMESTAMP WITH TIME ZONE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Insert dummy users dengan data lengkap
INSERT INTO users (username, email, password, role, full_name, phone, is_active) VALUES 
('admin', 'admin@eventmanagement.com', crypt('admin_password', gen_salt('bf')), 'admin', 'System Administrator', '021-1234567', true),
('sales1', 'sales1@eventmanagement.com', crypt('sales123', gen_salt('bf')), 'sales', 'Sales Manager 1', '021-7654321', true),
('user1', 'user1@eventmanagement.com', crypt('user123', gen_salt('bf')), 'user', 'Regular User', '021-9876543', true)
ON CONFLICT (username) DO NOTHING;

-- =============================================================================
-- USER LOGS TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS user_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    activity TEXT NOT NULL,
    log_time TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE user_logs ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- FUNCTION SPACE STYLES TABLE
-- =============================================================================

CREATE TABLE IF NOT EXISTS function_space_styles (
    id SERIAL PRIMARY KEY,
    code VARCHAR(10) NOT NULL,
    description VARCHAR(100) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE function_space_styles ENABLE ROW LEVEL SECURITY;

-- =============================================================================
-- FOREIGN KEY CONSTRAINTS
-- =============================================================================

-- Contacts foreign key
ALTER TABLE contacts 
ADD CONSTRAINT fk_contacts_business_block 
FOREIGN KEY (business_block_id) REFERENCES tbl_business_blocks(id) ON DELETE CASCADE;

-- Event bookings foreign key
ALTER TABLE event_bookings 
ADD CONSTRAINT fk_event_bookings_business_block 
FOREIGN KEY (business_block_id) REFERENCES tbl_business_blocks(id) ON DELETE CASCADE;

-- Event notes foreign key
ALTER TABLE event_notes 
ADD CONSTRAINT fk_event_notes_booking 
FOREIGN KEY (event_booking_id) REFERENCES event_bookings(id) ON DELETE CASCADE;

-- Business blocks foreign key
ALTER TABLE tbl_business_blocks 
ADD CONSTRAINT fk_business_blocks_owner 
FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE;

-- User logs foreign key
ALTER TABLE user_logs 
ADD CONSTRAINT fk_user_logs_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- =============================================================================
-- OPTIMIZED INDEXES FOR PERFORMANCE
-- =============================================================================

-- Primary indexes for foreign keys
CREATE INDEX IF NOT EXISTS idx_contacts_business_block ON contacts(business_block_id);
CREATE INDEX IF NOT EXISTS idx_event_bookings_business_block ON event_bookings(business_block_id);
CREATE INDEX IF NOT EXISTS idx_event_notes_booking ON event_notes(event_booking_id);
CREATE INDEX IF NOT EXISTS idx_business_blocks_owner ON tbl_business_blocks(owner_id);
CREATE INDEX IF NOT EXISTS idx_user_logs_user ON user_logs(user_id);

-- Date range indexes untuk queries berdasarkan tanggal
CREATE INDEX IF NOT EXISTS idx_event_bookings_dates ON event_bookings(start_datetime, end_datetime);
CREATE INDEX IF NOT EXISTS idx_business_blocks_dates ON tbl_business_blocks(start_date, end_date);
CREATE INDEX IF NOT EXISTS idx_user_logs_time ON user_logs(log_time DESC);

-- Composite indexes untuk query optimization
CREATE INDEX IF NOT EXISTS idx_event_bookings_status_dates ON event_bookings(status, start_datetime);
CREATE INDEX IF NOT EXISTS idx_business_blocks_status_owner ON tbl_business_blocks(status, owner_id);
CREATE INDEX IF NOT EXISTS idx_users_role_active ON users(role, is_active) WHERE is_active = true;

-- Email dan username indexes
CREATE UNIQUE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE UNIQUE INDEX IF NOT EXISTS idx_users_email ON users(email) WHERE email IS NOT NULL;
CREATE INDEX IF NOT EXISTS idx_users_uuid ON users(uuid);
CREATE INDEX IF NOT EXISTS idx_contacts_email ON contacts(email);

-- Search optimization indexes
CREATE INDEX IF NOT EXISTS idx_event_bookings_name ON event_bookings USING gin(to_tsvector('indonesian', event_name));
CREATE INDEX IF NOT EXISTS idx_business_blocks_name ON tbl_business_blocks USING gin(to_tsvector('indonesian', block_name));
CREATE INDEX IF NOT EXISTS idx_contacts_name ON contacts USING gin(to_tsvector('indonesian', first_name || ' ' || last_name));

-- =============================================================================
-- DATA VALIDATION FUNCTIONS
-- =============================================================================

-- Function untuk validate email format
CREATE OR REPLACE FUNCTION is_valid_email(email TEXT) 
RETURNS BOOLEAN AS $$
BEGIN
    RETURN email ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$';
END;
$$ LANGUAGE plpgsql;

-- Function untuk validate phone number (Indonesian format)
CREATE OR REPLACE FUNCTION is_valid_phone(phone TEXT) 
RETURNS BOOLEAN AS $$
BEGIN
    -- Indonesian phone format: 08xx-xxxx-xxxx or 021-xxxx-xxxx
    RETURN phone ~ '^(08[0-9]{8,11}|0[2-9][0-9]{7,10})$' OR phone ~ '^[0-9\-\+\(\)\s]{8,20}$';
END;
$$ LANGUAGE plpgsql;

-- Function untuk generate secure random password
CREATE OR REPLACE FUNCTION generate_secure_password(length INTEGER DEFAULT 12)
RETURNS TEXT AS $$
DECLARE
    chars TEXT := 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    result TEXT := '';
    i INTEGER;
BEGIN
    FOR i IN 1..length LOOP
        result := result || substr(chars, floor(random() * length(chars) + 1)::int, 1);
    END LOOP;
    RETURN result;
END;
$$ LANGUAGE plpgsql;

-- =============================================================================
-- TABLE CONSTRAINTS FOR DATA INTEGRITY
-- =============================================================================

-- Add email validation constraints
ALTER TABLE users ADD CONSTRAINT check_users_email_format 
    CHECK (email IS NULL OR is_valid_email(email));
    
ALTER TABLE contacts ADD CONSTRAINT check_contacts_email_format 
    CHECK (is_valid_email(email));

-- Add phone validation constraints
ALTER TABLE users ADD CONSTRAINT check_users_phone_format 
    CHECK (phone IS NULL OR is_valid_phone(phone));
    
ALTER TABLE contacts ADD CONSTRAINT check_contacts_phone_format 
    CHECK (is_valid_phone(phone));

-- Add date validation constraints
ALTER TABLE tbl_business_blocks ADD CONSTRAINT check_business_blocks_dates 
    CHECK (end_date >= start_date);
    
ALTER TABLE event_bookings ADD CONSTRAINT check_event_bookings_dates 
    CHECK (end_datetime > start_datetime);

-- Add positive number constraints
ALTER TABLE event_bookings ADD CONSTRAINT check_event_bookings_pax 
    CHECK (pax > 0);
    
ALTER TABLE tbl_business_blocks ADD CONSTRAINT check_business_blocks_revenue 
    CHECK (revenue_room >= 0 AND revenue_catering >= 0);

-- =============================================================================
-- SAMPLE DATA DUMMY
-- =============================================================================

-- Sample business block (diperlukan user ID 1)
INSERT INTO tbl_business_blocks (block_name, account_type, account_name, address, phone, owner_event, start_date, end_date, revenue_room, revenue_catering, status, owner_id) 
VALUES (
    'Grand Conference 2024', 
    'Company', 
    'PT. Maju Bersama', 
    'Jl. Sudirman No. 123, Jakarta', 
    '021-12345678', 
    'Annual Meeting', 
    '2024-04-01', 
    '2024-04-03', 
    15000000.00, 
    8000000.00, 
    'ACT', 
    1
);

-- Sample contact
INSERT INTO contacts (business_block_id, first_name, last_name, position, address, phone, email, fax) 
VALUES (
    1, 
    'John', 
    'Doe', 
    'Event Manager', 
    'Jl. Sudirman No. 123, Jakarta', 
    '08123456789', 
    'john.doe@majubersama.com', 
    '021-87654321'
);

-- Sample event booking
INSERT INTO event_bookings (business_block_id, event_name, function_space, start_datetime, end_datetime, pax, rental, status) 
VALUES (
    1, 
    'Annual Company Meeting', 
    'Grand Ballroom A', 
    '2024-04-01 08:00:00+07', 
    '2024-04-01 17:00:00+07', 
    150, 
    'Include', 
    'Approved'
);

-- Sample event notes
INSERT INTO event_notes (event_booking_id, department, note) 
VALUES 
    (1, 'FO', 'Setup meeting room dengan proyektor dan sound system'),
    (1, 'FB Banquet & Service', 'Coffee break jam 10:00 dan 15:00, lunch buffet jam 12:00'),
    (1, 'Housekeeping', 'Extra cleaning sebelum acara dimulai');

-- Sample function space styles
INSERT INTO function_space_styles (code, description) 
VALUES 
    ('TH', 'Theater Style'),
    ('CL', 'Classroom Style'),
    ('RD', 'Round Table'),
    ('CB', 'Cocktail Style'),
    ('UF', 'U-Shape Formation');

-- Sample user activity log
INSERT INTO user_logs (user_id, activity) 
VALUES 
    (1, 'Login to system'),
    (1, 'Created new business block: Grand Conference 2024'),
    (1, 'Added event booking for Grand Ballroom A');

-- =============================================================================
-- RLS POLICIES (Row Level Security)
-- =============================================================================

-- Policy untuk users: users hanya bisa lihat data mereka sendiri (kecuali admin)
CREATE POLICY "Users can view own data" ON users
    FOR SELECT USING (auth.uid()::text = id::text OR 
                     EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'));

-- Policy untuk business blocks: owner bisa akses data mereka
CREATE POLICY "Users can manage own business blocks" ON tbl_business_blocks
    FOR ALL USING (owner_id::text = auth.uid()::text OR 
                   EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'));

-- Policy untuk event bookings: berdasarkan business block ownership
CREATE POLICY "Users can manage related event bookings" ON event_bookings
    FOR ALL USING (EXISTS(
        SELECT 1 FROM tbl_business_blocks 
        WHERE id = business_block_id 
        AND (owner_id::text = auth.uid()::text OR 
             EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'))
    ));

-- Policy untuk contacts: berdasarkan business block ownership
CREATE POLICY "Users can manage related contacts" ON contacts
    FOR ALL USING (EXISTS(
        SELECT 1 FROM tbl_business_blocks 
        WHERE id = business_block_id 
        AND (owner_id::text = auth.uid()::text OR 
             EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'))
    ));

-- Policy untuk event notes: berdasarkan event booking ownership
CREATE POLICY "Users can manage related event notes" ON event_notes
    FOR ALL USING (EXISTS(
        SELECT 1 FROM event_bookings eb
        JOIN tbl_business_blocks bb ON eb.business_block_id = bb.id
        WHERE eb.id = event_booking_id 
        AND (bb.owner_id::text = auth.uid()::text OR 
             EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'))
    ));

-- Policy untuk user logs: admin dan pemilik data
CREATE POLICY "Users can view related logs" ON user_logs
    FOR SELECT USING (user_id::text = auth.uid()::text OR 
                     EXISTS(SELECT 1 FROM users WHERE id::text = auth.uid()::text AND role = 'Admin'));

-- Policy untuk function space styles: semua authenticated users bisa baca
CREATE POLICY "Authenticated users can read function space styles" ON function_space_styles
    FOR SELECT TO authenticated USING (true);

-- =============================================================================
-- FUNCTIONS FOR UPDATED_AT TIMESTAMPS
-- =============================================================================

-- Function untuk auto-update updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Triggers untuk auto-update updated_at
CREATE TRIGGER update_contacts_updated_at BEFORE UPDATE ON contacts 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_business_blocks_updated_at BEFORE UPDATE ON tbl_business_blocks 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_event_bookings_updated_at BEFORE UPDATE ON event_bookings 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_event_notes_updated_at BEFORE UPDATE ON event_notes 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
