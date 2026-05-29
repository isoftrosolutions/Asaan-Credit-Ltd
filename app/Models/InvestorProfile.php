<?php
namespace App\Models;

use App\Core\Database;

class InvestorProfile
{
    public static function findByUserId($userId): ?object
    {
        return Database::fetch('SELECT * FROM investor_profiles WHERE user_id = :user_id', [
            'user_id' => $userId
        ]);
    }

    public static function createOrUpdate($userId, array $data): void
    {
        $existing = self::findByUserId($userId);
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        if ($existing) {
            Database::update('investor_profiles', $data, 'user_id = :user_id', ['user_id' => $userId]);
        } else {
            $data['user_id'] = $userId;
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert('investor_profiles', $data);
        }
    }

    public static function allInvestors(): array
    {
        return Database::fetchAll(
            'SELECT u.*, ip.past_investments, ip.total_capital_deployed,
                    ip.preferred_sectors, ip.ticket_min, ip.ticket_max,
                    ip.preferred_geography, ip.portfolio_companies
             FROM users u
             JOIN investor_profiles ip ON u.id = ip.user_id
             WHERE u.role = :role AND u.verification_status = :status
             ORDER BY u.created_at DESC',
            ['role' => 'investor', 'status' => 'verified']
        );
    }
}
