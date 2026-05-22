-- Add missing payment_status column to the bookings table
ALTER TABLE bookings 
ADD COLUMN payment_status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid';

-- If status is also missing, uncomment below:
-- ALTER TABLE bookings ADD COLUMN status ENUM('Pending', 'Approved', 'Completed') DEFAULT 'Pending';