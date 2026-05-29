-- InvestMatch Nepal - Seed Data
-- Run AFTER schema.sql

USE invest_match;

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role, is_admin, verification_status, verified_at, created_at) VALUES
('Admin User', 'admin@investmatch.com', '$2y$12$J.i61qvAi4JwK5buhHHeSeNHHFHx.DBUOXMNkXw8N3uHdeOkZ2/x.', 'entrepreneur', 1, 'verified', NOW(), NOW());

-- Demo users (password: demo2026)
INSERT INTO users (name, email, password, role, account_type, phone, province, district, company_name, bio, verification_status, verified_at, is_admin, created_at) VALUES
('Ramesh Thapa', 'investor@nepal.com', '$2y$12$J.i61qvAi4JwK5buhHHeSeNHHFHx.DBUOXMNkXw8N3uHdeOkZ2/x.', 'investor', 'individual', '+977 9841 234567', 'Bagmati', 'Kathmandu', 'Thapa Capital', 'Angel investor focused on climate and agri-tech. Previously founded two Nepali SaaS companies.', 'verified', NOW(), 0, NOW()),
('Anjali K.C.', 'anjali@aarohan.com', '$2y$12$J.i61qvAi4JwK5buhHHeSeNHHFHx.DBUOXMNkXw8N3uHdeOkZ2/x.', 'entrepreneur', 'company', '+977 9841 765432', 'Bagmati', 'Kathmandu', 'Aarohan Kitchens', 'Founder of Aarohan Kitchens - AI-powered cold storage for Nepali farmers.', 'verified', NOW(), 0, NOW()),
('Sunita Sharma', 'sunita@vc.com', '$2y$12$J.i61qvAi4JwK5buhHHeSeNHHFHx.DBUOXMNkXw8N3uHdeOkZ2/x.', 'investor', 'company', '+977 9841 345678', 'Gandaki', 'Pokhara', 'Himalayan Seed Fund', 'VC firm investing in AgriTech and CleanTech startups across Nepal.', 'verified', NOW(), 0, NOW());

-- Sectors
INSERT INTO sectors (name, slug) VALUES
('AgriTech', 'agritech'),
('CleanTech', 'cleantech'),
('HealthTech', 'healthtech'),
('FinTech', 'fintech'),
('EdTech', 'edtech'),
('Logistics', 'logistics'),
('Manufacturing', 'manufacturing'),
('Retail', 'retail'),
('Hospitality', 'hospitality'),
('RealEstate', 'realestate'),
('Technology', 'technology'),
('Food & Beverage', 'food-beverage'),
('E-commerce', 'ecommerce'),
('Construction', 'construction'),
('Education', 'education');

-- Investor profiles
INSERT INTO investor_profiles (user_id, past_investments, portfolio_companies, total_capital_deployed, preferred_sectors, preferred_stages, ticket_min, ticket_max, preferred_geography, `references`) VALUES
(2, 6, 'Nepal Solar Pvt Ltd, Green Agri Ventures, HealthTech Nepal, EduInnovate', 50000000.00, '["AgriTech","CleanTech","HealthTech"]', '["Early Revenue","Growth"]', 1500000.00, 20000000.00, '["Bagmati","Gandaki"]', 'Mr. Rajesh Hamal - +977 9851 234567'),
(4, 9, 'EcoEnergy, SmartFarm Nepal, CleanWater Tech, WasteToValue, BioAgri', 200000000.00, '["AgriTech","CleanTech","Deep Tech"]', '["MVP","Early Revenue","Growth"]', 4000000.00, 15000000.00, '["Bagmati","Gandaki","Province 1"]', 'Dr. Sagar Acharya - +977 9851 876543');

-- Pitches
INSERT INTO pitches (user_id, tagline, problem_statement, solution, traction, funding_amount, equity_offered, stage, sector_id, is_active) VALUES
(3, 'AI-powered cold storage reducing post-harvest losses for 2,400+ farmers across Nepal.', '34% of Nepal perishable produce is lost before reaching market due to lack of reliable cold storage. Small farmers lose NPR 18,000-40,000 per season.', 'Low-cost, solar-hybrid smart cold rooms with IoT monitoring and AI demand forecasting. Farmers pay per use via mobile.', '2,400 farmers onboarded (Q1 2026)\nNPR 9.2M revenue run-rate\n3 provinces live, 2 more in pipeline\nPartnership with Nepal Agricultural Research Council', 28000000.00, 12.00, 'Early Revenue', 1, 1);

-- FAQs
INSERT INTO faqs (question, answer, sort_order) VALUES
('How does the platform ensure profiles are genuine?', 'Every profile is manually reviewed by our analysts. We verify email, phone, and social accounts. Businesses also undergo document verification (GST, registration certificate, financials).', 1),
('When do contact details get shared?', 'Contact info is revealed only when both parties express mutual interest. This prevents unsolicited outreach and protects your identity until you are ready to connect.', 2),
('What types of transactions are supported?', 'Full business sale, partial stake sale, investment, business loan, asset sale, franchise, and distributorship opportunities across all industries and regions.', 3),
('Is there a fee to use InvestMatch?', 'Basic registration is free. Premium plans start at NPR 25,500. A 1% finders fee applies post deal closure on paid plans. No hidden charges.', 4),
('How long does verification take?', 'Verification typically takes 24-48 hours after you upload your documents. Our admin team reviews each submission manually.', 5);

-- Homepage content
INSERT INTO homepage_contents (`key`, `value`) VALUES
('hero_title', 'Connect with Investors. Sell or Grow Your Business Faster.'),
('hero_subtitle', 'The premium marketplace where verified business owners meet qualified investors, buyers, and franchise partners. Close deals with confidence.'),
('stats_businesses', '67,500+'),
('stats_investors', '44,000+'),
('stats_matches', '12,800+'),
('stats_deal_value', 'NPR 850 Cr+');
