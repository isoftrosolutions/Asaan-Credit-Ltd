<?php
namespace App\Http\Controllers;

use App\Core\Database;
use App\Models\Pitch;
use App\Models\Faq;
use App\Models\InvestorProfile;

class HomepageController extends Controller
{
    public function index()
    {
        $featuredPitches = Pitch::all(['limit' => 6]);
        $featuredPitches = array_slice($featuredPitches, 0, 6);

        $stats = [
            'totalPitches' => Pitch::getActiveCount(),
            'totalInvestors' => count(Database::fetchAll(
                'SELECT id FROM users WHERE role = :role AND verification_status = :status',
                ['role' => 'investor', 'status' => 'verified']
            )),
            'totalMatches' => Database::fetch(
                'SELECT COUNT(*) as count FROM interest_requests WHERE status = :status',
                ['status' => 'accepted']
            ),
            'totalUsers' => Database::fetch('SELECT COUNT(*) as count FROM users'),
        ];

        $faqs = Faq::all();
        $faqs = array_slice($faqs, 0, 5);

        $sectors = \App\Models\Sector::all();

        return $this->render('home', [
            'featuredPitches' => $featuredPitches,
            'totalPitches' => (int)($stats['totalPitches'] ?? 0),
            'totalInvestors' => $stats['totalInvestors'],
            'totalMatches' => $stats['totalMatches'] ? (int)$stats['totalMatches']->count : 0,
            'totalUsers' => $stats['totalUsers'] ? (int)$stats['totalUsers']->count : 0,
            'faqs' => $faqs,
            'sectors' => $sectors,
        ]);
    }
}
