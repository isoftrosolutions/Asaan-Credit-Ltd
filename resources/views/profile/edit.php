<?php
    $profile = $user->isInvestor() ? $user->investorProfile : null;
    $verifStatus = $user->verification_status ?? 'unverified';
    $statusColors = [
        'unverified' => ['bg' => '#f1f5f9', 'fg' => '#475569', 'label' => 'Not Verified'],
        'pending' => ['bg' => '#fef3c7', 'fg' => '#92400e', 'label' => 'Pending Review'],
        'verified' => ['bg' => '#dcfce7', 'fg' => '#166534', 'label' => '✓ Verified'],
        'rejected' => ['bg' => '#fee2e2', 'fg' => '#991b1b', 'label' => 'Rejected'],
    ];
    $statusPill = $statusColors[$verifStatus] ?? $statusColors['unverified'];

    $fieldStyle = 'width:100%;padding:0.65rem 0.85rem;border:1px solid var(--border,#e5e7eb);border-radius:8px;font-size:0.95rem;font-family:inherit;background:#fff;';
    $labelStyle = 'display:block;font-size:0.82rem;font-weight:600;color:var(--dark,#0f172a);margin-bottom:0.4rem;';
    $sectionTitleStyle = 'font-size:1.05rem;font-weight:700;color:#1e3a8a;margin:0 0 1rem;padding-bottom:0.55rem;border-bottom:2px solid #1e3a8a;';
    $rowStyle = 'display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;margin-bottom:1rem;';
?>
<?php $title = 'Edit Profile' ?>
<?php $description = 'Manage your InvestMatch Nepal profile and verification.' ?>
<?php ob_start() ?>
<section class="section-premium" style="padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">

        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.75rem;">
            <div>
                <h1 style="font-size:1.65rem;font-weight:800;color:#1e3a8a;margin:0 0 0.25rem;">Edit Profile</h1>
                <p style="color:var(--secondary-text,#64748b);margin:0;font-size:0.95rem;">Keep your profile fresh — it's how investors and entrepreneurs find you.</p>
            </div>
            <span class="badge-premium" style="background:<?= htmlspecialchars($statusPill['bg']) ?>;color:<?= htmlspecialchars($statusPill['fg']) ?>;font-size:0.8rem;">
                <?= htmlspecialchars($statusPill['label']) ?>
            </span>
        </div>

        <form method="POST" action="<?= htmlspecialchars(route('profile.update')) ?>" enctype="multipart/form-data" class="card-premium" style="margin-bottom:2rem;">
            <?= csrf_field() ?>
            <?= method_field('PUT') ?>

            <h2 style="<?= htmlspecialchars($sectionTitleStyle) ?>">Account Info</h2>
            <div style="<?= htmlspecialchars($rowStyle) ?>">
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars(old('name', $user->name)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                </div>
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="email">Email</label>
                    <input type="email" id="email" value="<?= htmlspecialchars($user->email) ?>" disabled style="<?= htmlspecialchars($fieldStyle) ?>background:#f8fafc;color:#64748b;">
                </div>
            </div>
            <div style="<?= htmlspecialchars($rowStyle) ?>">
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars(old('phone', $user->phone)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="+977-98...">
                </div>
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="company_name"><?= htmlspecialchars($user->account_type === 'company' ? 'Company Name' : 'Company / Brand Name (optional)') ?></label>
                    <input type="text" id="company_name" name="company_name" value="<?= htmlspecialchars(old('company_name', $user->company_name)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                </div>
            </div>
            <div style="<?= htmlspecialchars($rowStyle) ?>">
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="province">Province</label>
                    <select id="province" name="province" style="<?= htmlspecialchars($fieldStyle) ?>">
                        <option value="">— Select Province —</option>
                        <?php foreach ($provinces as $p): ?>
                            <option value="<?= htmlspecialchars($p) ?>" <?= old('province', $user->province) === $p ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="district">District</label>
                    <input type="text" id="district" name="district" value="<?= htmlspecialchars(old('district', $user->district)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="e.g. Kathmandu">
                </div>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="bio">Short Bio <span style="color:#64748b;font-weight:400;">(max 250 chars)</span></label>
                <textarea id="bio" name="bio" rows="3" maxlength="250" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;"><?= htmlspecialchars(old('bio', $user->bio)) ?></textarea>
            </div>

            <div style="<?= htmlspecialchars($rowStyle) ?>">
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="linkedin_url">LinkedIn URL</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="<?= htmlspecialchars(old('linkedin_url', $user->linkedin_url)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="https://linkedin.com/in/...">
                </div>
                <div>
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="website_url">Website URL</label>
                    <input type="url" id="website_url" name="website_url" value="<?= htmlspecialchars(old('website_url', $user->website_url)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="https://...">
                </div>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="profile_photo">Profile Photo</label>
                <?php if ($user->profile_photo): ?>
                    <div style="margin-bottom:0.5rem;display:flex;align-items:center;gap:0.75rem;">
                        <img src="<?= htmlspecialchars(asset('storage/' . $user->profile_photo)) ?>" alt="Current photo" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:1px solid #e5e7eb;">
                        <span style="font-size:0.85rem;color:#64748b;">Current photo</span>
                    </div>
                <?php endif; ?>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="font-size:0.9rem;">
            </div>

            <?php if ($user->isInvestor()): ?>
                <h2 style="<?= htmlspecialchars($sectionTitleStyle) ?>margin-top:2rem;">Investor Details</h2>

                <div style="<?= htmlspecialchars($rowStyle) ?>">
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="past_investments">Past Investments (count)</label>
                        <input type="number" min="0" id="past_investments" name="past_investments" value="<?= htmlspecialchars(old('past_investments', $profile?->past_investments)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                    </div>
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="total_capital_deployed">Total Capital Deployed (NPR)</label>
                        <input type="number" step="0.01" min="0" id="total_capital_deployed" name="total_capital_deployed" value="<?= htmlspecialchars(old('total_capital_deployed', $profile?->total_capital_deployed)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="portfolio_companies">Notable Portfolio Companies</label>
                    <textarea id="portfolio_companies" name="portfolio_companies" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="One per line or comma-separated"><?= htmlspecialchars(old('portfolio_companies', $profile?->portfolio_companies)) ?></textarea>
                </div>

                <div style="<?= htmlspecialchars($rowStyle) ?>">
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="ticket_min">Ticket Min (NPR)</label>
                        <input type="number" step="0.01" min="0" id="ticket_min" name="ticket_min" value="<?= htmlspecialchars(old('ticket_min', $profile?->ticket_min)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                    </div>
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="ticket_max">Ticket Max (NPR)</label>
                        <input type="number" step="0.01" min="0" id="ticket_max" name="ticket_max" value="<?= htmlspecialchars(old('ticket_max', $profile?->ticket_max)) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
                    </div>
                </div>

                <?php
                    $selSectors = old('preferred_sectors', $profile?->preferred_sectors ?? []);
                    $selStages = old('preferred_stages', $profile?->preferred_stages ?? []);
                    $selGeo = old('preferred_geography', $profile?->preferred_geography ?? []);
                ?>

                <div style="margin-bottom:1.25rem;">
                    <label style="<?= htmlspecialchars($labelStyle) ?>">Preferred Sectors</label>
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem 1rem;padding:0.6rem 0.85rem;border:1px solid var(--border,#e5e7eb);border-radius:8px;background:#fff;">
                        <?php foreach ($sectors as $sector): ?>
                            <label style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.88rem;font-weight:500;color:#334155;cursor:pointer;">
                                <input type="checkbox" name="preferred_sectors[]" value="<?= htmlspecialchars($sector->name) ?>" <?= in_array($sector->name, $selSectors) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($sector->name) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="<?= htmlspecialchars($labelStyle) ?>">Preferred Stages</label>
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem 1rem;padding:0.6rem 0.85rem;border:1px solid var(--border,#e5e7eb);border-radius:8px;background:#fff;">
                        <?php foreach ($stages as $stage): ?>
                            <label style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.88rem;font-weight:500;color:#334155;cursor:pointer;">
                                <input type="checkbox" name="preferred_stages[]" value="<?= htmlspecialchars($stage) ?>" <?= in_array($stage, $selStages) ? 'checked' : '' ?>>
                                <?= htmlspecialchars(ucwords(str_replace('_', ' ', $stage))) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="<?= htmlspecialchars($labelStyle) ?>">Preferred Geography</label>
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem 1rem;padding:0.6rem 0.85rem;border:1px solid var(--border,#e5e7eb);border-radius:8px;background:#fff;">
                        <?php foreach ($provinces as $p): ?>
                            <label style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.88rem;font-weight:500;color:#334155;cursor:pointer;">
                                <input type="checkbox" name="preferred_geography[]" value="<?= htmlspecialchars($p) ?>" <?= in_array($p, $selGeo) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($p) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="<?= htmlspecialchars($labelStyle) ?>" for="references">References (optional)</label>
                    <textarea id="references" name="references" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Names, links or short context for due diligence"><?= htmlspecialchars(old('references', $profile?->references)) ?></textarea>
                </div>
            <?php endif; ?>

            <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>

        <div class="card-premium">
            <h2 style="<?= htmlspecialchars($sectionTitleStyle) ?>">Verification Documents</h2>
            <p style="font-size:0.9rem;color:var(--secondary-text,#64748b);margin:0 0 1.25rem;">
                Upload citizenship, PAN, or company registration to get the <strong>Verified</strong> badge.
                Verified profiles get higher visibility and trust from counterparties.
            </p>

            <form method="POST" action="<?= htmlspecialchars(route('profile.verification.upload')) ?>" enctype="multipart/form-data" style="margin-bottom:1.5rem;">
                <?= csrf_field() ?>
                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.75rem;align-items:end;">
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="document_type">Document Type *</label>
                        <select id="document_type" name="document_type" required style="<?= htmlspecialchars($fieldStyle) ?>">
                            <option value="citizenship">Citizenship</option>
                            <option value="company_registration">Company Registration</option>
                            <option value="pan">PAN</option>
                        </select>
                    </div>
                    <div>
                        <label style="<?= htmlspecialchars($labelStyle) ?>" for="document_file">File (PDF / JPG / PNG, max 10MB) *</label>
                        <input type="file" id="document_file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png" style="font-size:0.9rem;padding:0.5rem 0;">
                    </div>
                    <button type="submit" class="btn btn-outline" style="height:fit-content;">Upload</button>
                </div>
            </form>

            <?php if ($user->verificationDocuments->count()): ?>
                <div style="border-top:1px solid #e5e7eb;padding-top:1rem;">
                    <h3 style="font-size:0.85rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.04em;margin:0 0 0.85rem;">Submitted Documents</h3>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <?php foreach ($user->verificationDocuments as $doc): ?>
                            <?php
                                $docPill = match($doc->status) {
                                    'approved' => ['bg' => '#dcfce7', 'fg' => '#166534', 'label' => 'Approved'],
                                    'rejected' => ['bg' => '#fee2e2', 'fg' => '#991b1b', 'label' => 'Rejected'],
                                    default => ['bg' => '#fef3c7', 'fg' => '#92400e', 'label' => 'Pending'],
                                };
                            ?>
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:0.75rem 1rem;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;">
                                <div>
                                    <div style="font-weight:600;color:#0f172a;font-size:0.92rem;">
                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $doc->document_type))) ?>
                                    </div>
                                    <div style="font-size:0.78rem;color:#64748b;">
                                        Uploaded <?= htmlspecialchars($doc->created_at->diffForHumans()) ?>
                                    </div>
                                    <?php if ($doc->status === 'rejected' && $doc->rejection_reason): ?>
                                        <div style="font-size:0.78rem;color:#991b1b;margin-top:0.25rem;">
                                            Reason: <?= htmlspecialchars($doc->rejection_reason) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="badge-premium" style="background:<?= htmlspecialchars($docPill['bg']) ?>;color:<?= htmlspecialchars($docPill['fg']) ?>;">
                                    <?= htmlspecialchars($docPill['label']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div style="border-top:1px solid #e5e7eb;padding-top:1rem;color:#64748b;font-size:0.9rem;">
                    No documents submitted yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
