<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = \App\Core\Auth::user();
        $sectors = \App\Core\Database::fetchAll("SELECT * FROM sectors WHERE is_active = 1");
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];
        $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
        return view('profile.edit', compact('user', 'sectors', 'stages', 'provinces'));
    }

    public function update()
    {
        $user = \App\Core\Auth::user();

        $data = [];

        $name = \App\Core\Request::input('name');
        if (empty($name) || strlen($name) > 255) {
            $_SESSION['_errors'] = ['name' => ['The name field is required and must not exceed 255 characters.']];
            back();
        }
        $data['name'] = $name;

        $allowedStrings = ['phone', 'province', 'district', 'company_name', 'linkedin_url', 'website_url'];
        foreach ($allowedStrings as $field) {
            $val = \App\Core\Request::input($field);
            if ($val !== null) {
                $data[$field] = $val;
            }
        }

        $bio = \App\Core\Request::input('bio');
        if ($bio !== null) {
            if (strlen($bio) > 250) {
                $_SESSION['_errors'] = ['bio' => ['The bio must not exceed 250 characters.']];
                back();
            }
            $data['bio'] = $bio;
        }

        if (\App\Core\Request::hasFile('profile_photo')) {
            $file = \App\Core\Request::file('profile_photo');
            $path = upload_file($file, 'profile-photos');
            if ($path) $data['profile_photo'] = $path;
        }

        \App\Core\Database::update('users', $data, 'id = ?', [$user->id]);

        if ($user->role === 'investor') {
            $invData = [];
            $invFields = [
                'past_investments', 'portfolio_companies', 'total_capital_deployed',
                'preferred_sectors', 'preferred_stages', 'ticket_min', 'ticket_max',
                'preferred_geography', 'references',
            ];
            foreach ($invFields as $field) {
                $val = \App\Core\Request::input($field);
                if ($val !== null) {
                    $invData[$field] = is_array($val) ? json_encode($val) : $val;
                }
            }

            if (!empty($invData)) {
                $existing = \App\Core\Database::fetch(
                    "SELECT id FROM investor_profiles WHERE user_id = ?", [$user->id]
                );
                if ($existing) {
                    \App\Core\Database::update('investor_profiles', $invData, 'user_id = ?', [$user->id]);
                } else {
                    $invData['user_id'] = $user->id;
                    \App\Core\Database::insert('investor_profiles', $invData);
                }
            }
        }

        set_flash('success', 'Profile updated successfully.');
        back();
    }

    public function uploadVerificationDoc()
    {
        $documentType = \App\Core\Request::input('document_type');
        if (!in_array($documentType, ['citizenship', 'company_registration', 'pan'])) {
            $_SESSION['_errors'] = ['document_type' => ['The document type is invalid.']];
            back();
        }

        if (!\App\Core\Request::hasFile('document_file')) {
            $_SESSION['_errors'] = ['document_file' => ['The document file is required.']];
            back();
        }

        $user = \App\Core\Auth::user();
        $file = \App\Core\Request::file('document_file');
        $path = upload_file($file, 'verification-docs/' . $user->id);

        if (!$path) {
            set_flash('error', 'Failed to upload document.');
            back();
        }

        \App\Core\Database::insert('verification_documents', [
            'user_id' => $user->id,
            'document_type' => $documentType,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        \App\Core\Database::update('users', ['verification_status' => 'pending'], 'id = ?', [$user->id]);

        set_flash('success', 'Verification document uploaded. Awaiting admin review.');
        back();
    }
}
