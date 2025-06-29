-- Add location fields to drivers table
ALTER TABLE drivers ADD COLUMN latitude DECIMAL(10,8) NULL AFTER address;
ALTER TABLE drivers ADD COLUMN longitude DECIMAL(11,8) NULL AFTER latitude;

-- Add online status to users table
ALTER TABLE users ADD COLUMN is_online BOOLEAN DEFAULT FALSE AFTER role;

-- Insert some sample online drivers for testing
UPDATE users SET is_online = TRUE WHERE role = 'driver' LIMIT 3;

-- Insert sample location data for drivers (Pakistan coordinates)
UPDATE drivers SET 
    latitude = 30.3753 + (RAND() - 0.5) * 0.01,
    longitude = 69.3451 + (RAND() - 0.5) * 0.01
WHERE id IN (SELECT id FROM users WHERE role = 'driver' LIMIT 3); 