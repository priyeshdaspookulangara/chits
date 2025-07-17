-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'foreman') DEFAULT 'foreman',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Members Table
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20),
    address TEXT,
    pan_card VARCHAR(10) UNIQUE,
    aadhaar_card VARCHAR(12) UNIQUE,
    bank_account_details TEXT, -- Store as JSON or comma-separated if multiple
    nominee_name VARCHAR(100),
    nominee_contact VARCHAR(20),
    introducer_member_id INT, -- Foreign key to members.id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (introducer_member_id) REFERENCES members(id)
);

-- Chit Groups Table
CREATE TABLE chit_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    total_chit_amount DECIMAL(15, 2) NOT NULL,
    duration_months INT NOT NULL,
    monthly_contribution DECIMAL(15, 2) NOT NULL, -- Calculated: total_chit_amount / duration_months
    num_members INT NOT NULL,
    status ENUM('active', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Junction table for Many-to-Many relationship between Members and Chit Groups
CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chit_group_id INT NOT NULL,
    member_id INT NOT NULL,
    join_date DATE,
    FOREIGN KEY (chit_group_id) REFERENCES chit_groups(id),
    FOREIGN KEY (member_id) REFERENCES members(id),
    UNIQUE (chit_group_id, member_id) -- A member can join a group only once
);

-- Auctions Table
CREATE TABLE auctions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chit_group_id INT NOT NULL,
    auction_month INT NOT NULL, -- e.g., 1 to duration_months
    auction_date DATE NOT NULL,
    winning_member_id INT NOT NULL,
    winning_bid_amount DECIMAL(15, 2) NOT NULL,
    discount_offered DECIMAL(15, 2) NOT NULL, -- Gross Chit Amount - Winning Bid Amount
    foreman_commission DECIMAL(15, 2) NOT NULL, -- 5% of total_chit_amount for simplicity
    net_auction_profit DECIMAL(15, 2) NOT NULL, -- Discount Offered - Foreman Commission
    dividend_per_member DECIMAL(15, 2) NOT NULL, -- Net Auction Profit / num_members in group
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chit_group_id) REFERENCES chit_groups(id),
    FOREIGN KEY (winning_member_id) REFERENCES members(id),
    UNIQUE (chit_group_id, auction_month) -- Only one auction per month per group
);

-- Member Payments/Ledger Table (Simplified)
CREATE TABLE member_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    chit_group_id INT NOT NULL,
    payment_month INT NOT NULL, -- Corresponding month in the chit cycle
    amount_paid DECIMAL(15, 2) NOT NULL,
    actual_monthly_contribution DECIMAL(15, 2) NOT NULL, -- Original contribution - dividend for this month
    dividend_received DECIMAL(15, 2) DEFAULT 0.00,
    status ENUM('paid', 'due') DEFAULT 'due',
    payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id),
    FOREIGN KEY (chit_group_id) REFERENCES chit_groups(id),
    UNIQUE (member_id, chit_group_id, payment_month) -- One payment record per member per group per month
);
