<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ &amp; Support — InvestMatch</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('styles.css') ?>">
  <link rel="stylesheet" href="<?= asset('header.css') ?>">
  <link rel="icon" href="<?= asset('favicon.ico') ?>">
</head>
<body>
  <div id="header-root"></div>
  <script src="<?= asset('icons.js') ?>"></script>
  <script src="<?= asset('header.js') ?>"></script>
  <script src="<?= asset('components.js') ?>"></script>
  <script>injectHeader('public');</script>

  <div class="breadcrumbs container">
    <a href="<?= url('/') ?>">Home</a> <span>/</span>
    <span>Q &amp; A</span>
  </div>

  <div class="container" style="max-width:800px; padding-bottom:4rem;">
    <div style="text-align:center;margin-bottom:2rem;">
      <h1>Frequently Asked Questions</h1>
      <p class="body-lg">Find answers about using InvestMatch — whether you're a business owner, investor, franchisor, or advisor.</p>
    </div>

    <div class="persona-tabs" style="justify-content:center;">
      <button class="persona-tab active" onclick="filterFAQ('all',this)">All</button>
      <button class="persona-tab" onclick="filterFAQ('owner',this)">Business Owners</button>
      <button class="persona-tab" onclick="filterFAQ('investor',this)">Investors &amp; Buyers</button>
      <button class="persona-tab" onclick="filterFAQ('advisor',this)">Advisors</button>
      <button class="persona-tab" onclick="filterFAQ('franchisor',this)">Franchisors</button>
    </div>

    <div class="faq-section" style="margin-top:1.5rem;">
      <?php $faqItems = [
        ['q' => 'How does the platform ensure profiles are genuine?', 'a' => 'Every profile is manually reviewed by our analysts before going live. We verify email, phone, and social media accounts. Businesses also undergo document verification including GST certificates and registration documents. Profiles are rated out of 10 based on completeness and verification level.', 'cat' => 'all'],
        ['q' => 'When do contact details get shared?', 'a' => 'Contact information (name, phone, email) is revealed only when both parties express mutual interest and a match is established. This "Available after connect" pattern ensures confidentiality and prevents unsolicited outreach.', 'cat' => 'all'],
        ['q' => 'What types of transactions are supported?', 'a' => 'We support full business sale, partial stake sale, investment/equity funding, business loans, asset sales, franchise opportunities, and distributorship opportunities.', 'cat' => 'all'],
        ['q' => 'Is there a fee to use InvestMatch?', 'a' => 'Basic registration is free. Premium subscription plans are available for businesses (NPR 25,500 / 38,500 / 2,55,000), investors (NPR 25,500 / 30,500 / 76,500), and advisors (NPR 1,55,000). All paid plans carry a 1% finder\'s fee post deal closure.', 'cat' => 'all'],
        ['q' => 'How long does it take to get my business listing approved?', 'a' => 'Profile activation typically takes 2 business days. Our analysts review your business details, financials, and supporting documents before approving the listing.', 'cat' => 'owner'],
        ['q' => 'Can I list my business for both sale and investment?', 'a' => 'Yes, you can select multiple transaction types for your business. You can offer a full sale, partial stake, investment, or loan — all within a single profile.', 'cat' => 'owner'],
        ['q' => 'How do I express interest in a business?', 'a' => 'Once you\'ve created a verified investor profile, you can browse business listings and click "Send Proposal" or "Contact Business" on any listing.', 'cat' => 'investor'],
        ['q' => 'Is there a limit on how many businesses I can contact?', 'a' => 'Free members have a daily limit on connection requests. Premium subscribers get unlimited connection requests and priority visibility.', 'cat' => 'investor'],
        ['q' => 'How do I register as an advisor?', 'a' => 'Select "Advisor / Broker" during registration, or visit the How To page and choose "Register as an Advisor".', 'cat' => 'advisor'],
        ['q' => 'What information do I need to list a franchise opportunity?', 'a' => 'You\'ll need your brand details, number of existing franchisees, year established, headquarters location, expansion regions, expected monthly sales, space requirements, and total investment required.', 'cat' => 'franchisor'],
      ]; ?>
      <?php foreach ($faqItems as $item): ?>
      <div class="faq-item <?= $item['cat'] === 'all' ? 'open' : '' ?>" data-category="<?= htmlspecialchars($item['cat']) ?>">
        <div class="faq-question" onclick="this.parentElement.classList.toggle('open')">
          <span><?= htmlspecialchars($item['q']) ?></span>
          <span style="font-size:1.2rem;">+</span>
        </div>
        <div class="faq-answer"><?= htmlspecialchars($item['a']) ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="margin-top:3rem; padding:2rem; background:#f6f3f1; border-radius:2rem; text-align:center;">
      <h3>Still have questions?</h3>
      <p>Send your query and we'll get back to you within 2 business days.</p>
      <form method="POST" action="<?= url('support') ?>" style="max-width:480px; margin:1.5rem auto 0;">
        <?= csrf_field() ?>
        <div class="input-group">
          <textarea name="message" class="input" placeholder="Type your question..." style="min-height:80px;"></textarea>
        </div>
        <div style="display:flex; gap:0.75rem;">
          <input type="email" name="email" class="input" placeholder="Your email" style="flex:1;">
          <button type="submit" class="btn btn-primary">Send</button>
        </div>
      </form>
      <div style="margin-top:1rem; font-size:0.85rem; color:#888;">
        Or email us at <a href="mailto:support@investmatch.com.np" style="color:var(--accent);">support@investmatch.com.np</a>
      </div>
    </div>
  </div>

  <script>
    function filterFAQ(category, btn) {
      document.querySelectorAll('.persona-tab').forEach(function(t) { t.classList.remove('active'); });
      btn.classList.add('active');
      document.querySelectorAll('.faq-item').forEach(function(item) {
        var cats = item.getAttribute('data-category').split(' ');
        item.style.display = (category === 'all' || cats.indexOf(category) !== -1) ? 'block' : 'none';
      });
    }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
