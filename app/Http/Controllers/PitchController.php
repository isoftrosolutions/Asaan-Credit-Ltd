<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Models\Pitch;
use App\Models\Sector;

class PitchController extends Controller
{
    public function create()
    {
        Auth::requireAuth();
        if (Auth::user()->role !== 'entrepreneur') {
            flash('error', 'Only entrepreneurs can create pitches.');
            $this->redirect('/dashboard');
        }
        $sectors = Sector::all();
        return $this->render('pitch.create', ['sectors' => $sectors]);
    }

    public function store()
    {
        Auth::requireAuth();
        $user = Auth::user();
        if ($user->role !== 'entrepreneur') {
            flash('error', 'Only entrepreneurs can create pitches.');
            $this->redirect('/dashboard');
        }

        $data = Request::validate([
            'tagline' => 'required|min:10|max:200',
            'problem' => 'required|min:20|max:2500',
            'solution' => 'required|min:20|max:2500',
            'sector_id' => 'required',
        ]);

        $pitchId = Pitch::create([
            'user_id' => $user->id,
            'tagline' => $data['tagline'],
            'problem' => $data['problem'],
            'solution' => $data['solution'],
            'traction' => Request::input('traction', ''),
            'market_size' => Request::input('market_size', ''),
            'business_model' => Request::input('business_model', ''),
            'competition' => Request::input('competition', ''),
            'stage' => Request::input('stage', 'idea'),
            'sector_id' => (int)$data['sector_id'],
            'funding_amount' => Request::input('funding_amount') ? (int)Request::input('funding_amount') : null,
            'equity_offered' => Request::input('equity_offered') ? (float)Request::input('equity_offered') : null,
            'pitch_deck_url' => Request::input('pitch_deck_url', ''),
            'video_url' => Request::input('video_url', ''),
            'is_active' => 1,
            'is_hidden' => 0,
        ]);

        flash('success', 'Pitch created successfully! It is now live.');
        $this->redirect('/dashboard/entrepreneur');
    }

    public function edit($id)
    {
        Auth::requireAuth();
        $pitch = Pitch::find($id);

        if (!$pitch) {
            flash('error', 'Pitch not found.');
            $this->redirect('/dashboard');
        }
        if ($pitch->user_id !== Auth::id()) {
            flash('error', 'You can only edit your own pitches.');
            $this->redirect('/dashboard');
        }

        $sectors = Sector::all();
        return $this->render('pitch.edit', [
            'pitch' => $pitch,
            'sectors' => $sectors,
        ]);
    }

    public function update($id)
    {
        Auth::requireAuth();
        $pitch = Pitch::find($id);

        if (!$pitch) {
            flash('error', 'Pitch not found.');
            $this->redirect('/dashboard');
        }
        if ($pitch->user_id !== Auth::id()) {
            flash('error', 'You can only edit your own pitches.');
            $this->redirect('/dashboard');
        }

        $data = Request::validate([
            'tagline' => 'required|min:10|max:200',
            'problem' => 'required|min:20|max:2500',
            'solution' => 'required|min:20|max:2500',
        ]);

        Pitch::update($id, [
            'tagline' => $data['tagline'],
            'problem' => $data['problem'],
            'solution' => $data['solution'],
            'traction' => Request::input('traction', ''),
            'market_size' => Request::input('market_size', ''),
            'business_model' => Request::input('business_model', ''),
            'competition' => Request::input('competition', ''),
            'stage' => Request::input('stage', 'idea'),
            'sector_id' => (int)Request::input('sector_id', $pitch->sector_id),
            'funding_amount' => Request::input('funding_amount') ? (int)Request::input('funding_amount') : null,
            'equity_offered' => Request::input('equity_offered') ? (float)Request::input('equity_offered') : null,
            'pitch_deck_url' => Request::input('pitch_deck_url', ''),
            'video_url' => Request::input('video_url', ''),
        ]);

        flash('success', 'Pitch updated successfully.');
        $this->redirect('/dashboard/entrepreneur');
    }
}
