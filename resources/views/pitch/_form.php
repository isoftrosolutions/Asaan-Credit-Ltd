<div class="card" style="margin-bottom:1.5rem;">
  <h4>Pitch Content</h4>
  <div class="input-group">
    <label>One-line Tagline</label>
    <input class="input" name="tagline" value="<?= htmlspecialchars($pitch['tagline'] ?? '') ?>" placeholder="e.g., AI cold storage that cuts farmer losses by 34%">
  </div>
  <div class="input-group">
    <label>Problem (max 500 words)</label>
    <textarea name="problem" placeholder="Describe the problem you are solving..."><?= htmlspecialchars($pitch['problem'] ?? '') ?></textarea>
  </div>
  <div class="input-group">
    <label>Solution</label>
    <textarea name="solution" placeholder="Describe your solution..."><?= htmlspecialchars($pitch['solution'] ?? '') ?></textarea>
  </div>
  <div class="input-group">
    <label>Market &amp; Traction</label>
    <textarea name="traction" placeholder="Describe your market, traction, and milestones..."><?= htmlspecialchars($pitch['traction'] ?? '') ?></textarea>
  </div>
</div>

<div class="card">
  <h4>Funding Ask &amp; Media</h4>
  <div class="form-grid pitch-form-grid">
    <div class="input-group">
      <label>Amount Sought (NPR)</label>
      <input class="input" name="amount" value="<?= htmlspecialchars($pitch['amount'] ?? '') ?>" placeholder="e.g., 28000000">
    </div>
    <div class="input-group">
      <label>Equity Offered (%)</label>
      <input class="input" name="equity" value="<?= htmlspecialchars($pitch['equity'] ?? '') ?>" placeholder="e.g., 12">
    </div>
  </div>
  <div class="input-group">
    <label>Pitch Deck (PDF)</label>
    <input type="file" name="pitch_deck" class="input">
  </div>
  <div class="input-group">
    <label>Pitch Video (YouTube / Vimeo URL)</label>
    <input class="input" name="video_url" value="<?= htmlspecialchars($pitch['video_url'] ?? '') ?>" placeholder="https://youtube.com/watch?v=...">
  </div>
</div>
