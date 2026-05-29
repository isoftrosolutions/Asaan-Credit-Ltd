<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Database;
use App\Models\User;
use App\Models\InvestorProfile;

class ProfileController extends Controller
{
    public function edit()
    {
        Auth::requireAuth();
        $user = Auth::user();
        $investorProfile = null;

        if ($user->role === 'investor') {
            $investorProfile = InvestorProfile::findByUserId($user->id);
        }

        return $this->render('profile.edit', [
            'user' => $user,
            'investorProfile' => $investorProfile,
        ]);
    }

    public function update()
    {
        Auth::requireAuth();
        $user = Auth::user();

        $data = Request::validate([
            'name' => 'required|min:2|max:100',
            'phone' => 'max:20',
            'bio' => 'max:1000',
            'linkedin_url' => 'max:255',
            'website_url' => 'max:255',
        ]);

        $updateData = [
            'name' => $data['name'],
            'company_name' => Request::input('company_name', ''),
            'phone' => $data['phone'] ?? '',
            'province' => Request::input('province', ''),
            'district' => Request::input('district', ''),
            'bio' => $data['bio'] ?? '',
            'linkedin_url' => $data['linkedin_url'] ?? '',
            'website_url' => $data['website_url'] ?? '',
        ];

        User::update($user->id, $updateData);

        if ($user->role === 'investor') {
            InvestorProfile::createOrUpdate($user->id, [
                'preferred_sectors' => Request::input('preferred_sectors', ''),
                'ticket_min' => Request::input('ticket_min') ? (int)Request::input('ticket_min') : null,
                'ticket_max' => Request::input('ticket_max') ? (int)Request::input('ticket_max') : null,
                'preferred_stages' => Request::input('preferred_stages', ''),
                'preferred_geography' => Request::input('preferred_geography', ''),
            ]);
        }

        flash('success', 'Profile updated successfully.');
        $this->back();
    }

    public function uploadVerificationDoc()
    {
        Auth::requireAuth();
        $user = Auth::user();

        $file = Request::file('document');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Please select a valid document to upload.');
            $this->back();
        }

        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($file['type'], $allowedTypes)) {
            flash('error', 'Only PDF, JPEG, and PNG files are accepted.');
            $this->back();
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            flash('error', 'Document size must be under 10MB.');
            $this->back();
        }

        $docType = Request::input('doc_type', 'citizenship');
        $path = upload_file($file, 'verification-docs');

        Database::insert('verification_docs', [
            'user_id' => $user->id,
            'doc_type' => $docType,
            'file_path' => $path,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        flash('success', 'Verification document uploaded successfully. It will be reviewed by our team.');
        $this->back();
    }
}
