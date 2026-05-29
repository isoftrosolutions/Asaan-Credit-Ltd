<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up — InvestMatch Nepal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('styles.css') ?>">
  <link rel="stylesheet" href="<?= asset('header.css') ?>">
  <style>
    .auth-container { max-width: 620px; margin: 2.5rem auto; padding: 2.25rem 2.75rem; background:white; border-radius:2.5rem; }
    .step-indicator { display:flex; gap:8px; margin-bottom:2rem; }
    .step { flex:1; height:4px; background:#e5e2e0; border-radius:999px; position:relative; }
    .step.active { background:#C41E3A; }
    .step.done { background:#166534; }
    .role-card { border:2px solid #e5e2e0; border-radius:1.25rem; padding:1.25rem; cursor:pointer; transition:all 0.2s; }
    .role-card.selected { border-color:#C41E3A; background:#fffaf5; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  </style>
</head>
<body style="background:#f6f3f1;">
  <div id="header-root"></div>
  <script src="<?= asset('icons.js') ?>"></script>
  <script src="<?= asset('header.js') ?>"></script>
  <script src="<?= asset('components.js') ?>"></script>
  <script>injectHeader('public');</script>

  <div class="auth-container">
    <div style="text-align:center; margin-bottom:1.5rem;">
      <h2 style="margin-bottom:0.25rem;">Create your account</h2>
      <p style="color:#666;">Join Nepal's most trusted capital matching platform</p>
    </div>

    <div class="step-indicator">
      <div class="step active" id="step-bar-1"></div>
      <div class="step" id="step-bar-2"></div>
      <div class="step" id="step-bar-3"></div>
    </div>

    <form method="POST" action="<?= url('register') ?>" id="signup-form">
      <?= csrf_field() ?>

      <!-- STEP 1: Role Selection -->
      <div id="step-1">
        <h3 style="margin-bottom:1rem; text-align:center;">I am joining as a...</h3>

        <div class="role-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin:1.5rem 0;">
          <div onclick="selectRole(this, 'investor')" class="role-card" id="role-investor">
            <div style="font-size:2rem; margin-bottom:0.5rem;">💰</div>
            <strong>Investor / Buyer</strong>
            <div style="font-size:0.85rem; color:#666;">Angel, HNI, VC, PE, family office, or corporate acquirer</div>
          </div>
          <div onclick="selectRole(this, 'owner')" class="role-card" id="role-owner">
            <div style="font-size:2rem; margin-bottom:0.5rem;">🏢</div>
            <strong>Business Owner</strong>
            <div style="font-size:0.85rem; color:#666;">Selling your business, seeking investment, or a loan</div>
          </div>
          <div onclick="selectRole(this, 'franchisor')" class="role-card" id="role-franchisor">
            <div style="font-size:2rem; margin-bottom:0.5rem;">🍟</div>
            <strong>Franchisor / Brand</strong>
            <div style="font-size:0.85rem; color:#666;">Expanding your brand through franchise partners</div>
          </div>
          <div onclick="selectRole(this, 'advisor')" class="role-card" id="role-advisor">
            <div style="font-size:2rem; margin-bottom:0.5rem;">📋</div>
            <strong>Advisor / Broker</strong>
            <div style="font-size:0.85rem; color:#666;">M&amp;A advisor, business broker, consultant, law firm</div>
          </div>
        </div>

        <input type="hidden" name="role" id="selected-role" value="">
        <button type="button" onclick="nextStep(2)" class="btn btn-primary" style="width:100%; margin-top:1rem;" disabled id="continue-btn-1">Continue →</button>
        <div style="text-align:center; margin-top:1rem; font-size:0.8rem; color:#888;">You can only have one active role per account</div>
      </div>

      <!-- STEP 2: Basic Info -->
      <div id="step-2" style="display:none;">
        <h3 style="margin-bottom:1.25rem;">Tell us about yourself</h3>

        <div class="form-grid">
          <div class="input-group">
            <label>Full name / Company name</label>
            <input type="text" name="name" class="input" value="<?= htmlspecialchars(old('name', 'Ramesh Thapa')) ?>" id="full-name">
          </div>
          <div class="input-group">
            <label>Location (District, Province)</label>
            <input type="text" name="location" class="input" value="<?= htmlspecialchars(old('location', 'Kathmandu, Bagmati')) ?>" id="location">
          </div>
        </div>

        <div class="input-group">
          <label>Account type</label>
          <div style="display:flex; gap:1rem; margin-top:0.5rem;">
            <label style="flex:1; border:1.5px solid #e5e2e0; padding:10px 14px; border-radius:12px; cursor:pointer;">
              <input type="radio" name="account_type" value="individual" <?= old('account_type', 'individual') === 'individual' ? 'checked' : '' ?>> Individual
            </label>
            <label style="flex:1; border:1.5px solid #e5e2e0; padding:10px 14px; border-radius:12px; cursor:pointer;">
              <input type="radio" name="account_type" value="company" <?= old('account_type') === 'company' ? 'checked' : '' ?>> Registered Company
            </label>
          </div>
        </div>

        <div class="form-grid">
          <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" class="input" value="<?= htmlspecialchars(old('email', 'ramesh@thapa.com')) ?>" id="email">
          </div>
          <div class="input-group">
            <label>Phone (Nepal)</label>
            <input type="tel" name="phone" class="input" value="<?= htmlspecialchars(old('phone', '+977 9841 234567')) ?>" id="phone">
          </div>
        </div>

        <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
          <button type="button" onclick="prevStep(1)" class="btn btn-secondary" style="flex:1;">← Back</button>
          <button type="button" onclick="nextStep(3)" class="btn btn-primary" style="flex:1;">Continue →</button>
        </div>
      </div>

      <!-- STEP 3: Password -->
      <div id="step-3" style="display:none;">
        <h3 style="margin-bottom:1rem;">Secure your account</h3>

        <div class="input-group">
          <label>Create password</label>
          <input type="password" name="password" class="input" value="demo2026" id="password">
          <div style="font-size:0.75rem; color:#888; margin-top:4px;">Min 8 characters • Must include number</div>
        </div>

        <div class="input-group">
          <label>Confirm password</label>
          <input type="password" name="password_confirmation" class="input" value="demo2026">
        </div>

        <div style="background:#fffaf5; border-radius:12px; padding:1rem; font-size:0.9rem; margin:1.25rem 0;">
          📧 After signup we will send a verification link to your email. You must verify before your profile goes live.
        </div>

        <div style="display:flex; gap:0.75rem;">
          <button type="button" onclick="prevStep(2)" class="btn btn-secondary" style="flex:1;">← Back</button>
          <button type="button" onclick="completeSignup()" class="btn btn-accent" style="flex:1;">Create Account &amp; Verify Email</button>
        </div>

        <div style="margin-top:1rem; font-size:0.75rem; color:#888; text-align:center;">
          By creating an account you agree to our <a href="<?= url('legal') ?>" style="color:#C41E3A;">Terms</a> and <a href="<?= url('legal') ?>" style="color:#C41E3A;">Privacy Policy</a>.
        </div>
      </div>
    </form>
  </div>

  <script>
    var selectedRole = null;
    var currentStep = 1;

    function selectRole(el, role) {
      document.querySelectorAll('.role-card').forEach(function(c) { c.classList.remove('selected'); });
      el.classList.add('selected');
      selectedRole = role;
      document.getElementById('selected-role').value = role;
      document.getElementById('continue-btn-1').disabled = false;
      localStorage.setItem('demoRole', role);
    }

    function nextStep(step) {
      document.getElementById('step-' + currentStep).style.display = 'none';
      document.getElementById('step-' + step).style.display = 'block';
      for (var i=1; i<=3; i++) {
        var bar = document.getElementById('step-bar-' + i);
        bar.classList.remove('active', 'done');
        if (i < step) bar.classList.add('done');
        if (i === step) bar.classList.add('active');
      }
      currentStep = step;
    }

    function prevStep(step) {
      document.getElementById('step-' + currentStep).style.display = 'none';
      document.getElementById('step-' + step).style.display = 'block';
      for (var i=1; i<=3; i++) {
        var bar = document.getElementById('step-bar-' + i);
        bar.classList.remove('active', 'done');
        if (i < step) bar.classList.add('done');
        if (i === step) bar.classList.add('active');
      }
      currentStep = step;
    }

    function completeSignup() {
      var role = selectedRole || 'investor';
      localStorage.setItem('demoRole', role);
      document.getElementById('signup-form').submit();
    }

    setTimeout(function() {
      var inv = document.getElementById('role-investor');
      if (inv && !selectedRole) {
        inv.classList.add('selected');
        selectedRole = 'investor';
        document.getElementById('selected-role').value = 'investor';
        document.getElementById('continue-btn-1').disabled = false;
        localStorage.setItem('demoRole', 'investor');
      }
    }, 600);
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
