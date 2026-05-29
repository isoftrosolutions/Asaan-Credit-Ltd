/* =============================================
   InvestMatch Nepal — Reusable Components
   ============================================= */

/* ----- Footer ----- */
function injectFooter() {
  var root = document.getElementById('footer-root');
  if (!root) return;

  root.innerHTML = `
    <footer class="footer-premium">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <div style="display:flex;align-items:center;gap:8px;font-weight:800;font-size:1.2rem;margin-bottom:0.75rem;">
              <svg viewBox="0 0 32 32" width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="#C41E3A"/>
                <path d="M8 16c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8z" fill="rgba(255,255,255,0.2)"/>
                <path d="M12 16c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z" fill="white"/>
              </svg>
              Invest<span style="color:var(--brand-red);">Match</span>
            </div>
            <p style="font-size:0.85rem;color:var(--text-secondary);max-width:280px;">Nepal's premium marketplace for buying, selling, franchising, and funding SMEs.</p>
            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
              <span style="width:32px;height:32px;border-radius:50%;background:var(--bg-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);cursor:pointer;font-size:0.75rem;font-weight:600;">in</span>
              <span style="width:32px;height:32px;border-radius:50%;background:var(--bg-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);cursor:pointer;font-size:0.75rem;font-weight:600;">X</span>
              <span style="width:32px;height:32px;border-radius:50%;background:var(--bg-subtle);display:flex;align-items:center;justify-content:center;color:var(--text-secondary);cursor:pointer;font-size:0.75rem;font-weight:600;">YT</span>
            </div>
          </div>

          <div class="footer-col">
            <h6>For Businesses</h6>
            <div class="footer-links">
              <a href="sign-up.html">Sell Your Business</a>
              <a href="browse-businesses.html">Businesses for Sale</a>
              <a href="browse-franchises.html">Franchise Opportunities</a>
              <a href="business-valuation.html">Business Valuation</a>
              <a href="create-business-profile.html">List Your Business</a>
            </div>
          </div>

          <div class="footer-col">
            <h6>For Investors</h6>
            <div class="footer-links">
              <a href="sign-up.html">Create Investor Profile</a>
              <a href="browse-businesses.html">Investment Opportunities</a>
              <a href="browse-investors.html">Browse Investors</a>
              <a href="how-it-works.html">How It Works</a>
            </div>
          </div>

          <div class="footer-col">
            <h6>Company</h6>
            <div class="footer-links">
              <a href="about.html">About Us</a>
              <a href="how-it-works.html">How It Works</a>
              <a href="support.html">Support &amp; FAQs</a>
              <a href="legal.html">Terms &amp; Privacy</a>
              <a href="legal.html">Cookie Policy</a>
            </div>
          </div>
        </div>

        <div class="divider-subtle" style="margin:2rem 0;"></div>

        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;padding-bottom:2rem;font-size:0.78rem;color:var(--text-muted);">
          <span>&copy; ${new Date().getFullYear()} InvestMatch Nepal. All rights reserved.</span>
          <span>Made with ❤️ in Nepal</span>
        </div>
      </div>
    </footer>
  `;
}

/* ----- Sidebar ----- */
function injectSidebar(role) {
  var root = document.getElementById('sidebar-root');
  if (!root) return;

  var items = getSidebarItems(role);
  var html = '';
  for (var i = 0; i < items.length; i++) {
    var item = items[i];
    var activeClass = isActiveSidebar(item.href) ? ' active' : '';
    html += '<a href="' + item.href + '" class="sidebar-item' + activeClass + '">';
    html += item.icon;
    html += item.label;
    html += '</a>';
  }
  root.innerHTML = html;
}

function getSidebarItems(role) {
  var common = [
    { href: 'browse-businesses.html', label: 'Browse Businesses', icon: Icons.search },
    { href: 'browse-investors.html', label: 'Browse Investors', icon: Icons.users },
    { href: 'my-connections.html', label: 'My Connections', icon: Icons.chat },
  ];

  if (role === 'investor' || role === 'advisor') {
    return [
      { href: 'investor-dashboard.html', label: 'Dashboard', icon: Icons.home },
      { href: 'profile-edit.html', label: 'My Profile', icon: Icons.user },
      ...common,
      { href: 'notifications-settings.html', label: 'Notifications', icon: Icons.bell },
      { href: 'profile-edit.html', label: 'Settings', icon: Icons.settings },
    ];
  }

  if (role === 'owner' || role === 'entrepreneur') {
    return [
      { href: 'entrepreneur-dashboard.html', label: 'Dashboard', icon: Icons.home },
      { href: 'pitch-edit.html', label: 'My Pitch', icon: Icons.document },
      ...common,
      { href: 'notifications-settings.html', label: 'Notifications', icon: Icons.bell },
      { href: 'pitch-edit.html', label: 'Settings', icon: Icons.settings },
    ];
  }

  if (role === 'admin') {
    return [
      { href: 'admin.html', label: 'Dashboard', icon: Icons.home },
      { href: 'admin.html?tab=verification', label: 'Verification Queue', icon: Icons.checkCircle },
      { href: 'admin.html?tab=users', label: 'User Management', icon: Icons.users },
      { href: 'admin.html?tab=broadcast', label: 'Broadcast', icon: Icons.mail },
      { href: 'admin.html?tab=analytics', label: 'Analytics', icon: Icons.chart },
    ];
  }

  // Default / fallback
  return [
    { href: 'investor-dashboard.html', label: 'Dashboard', icon: Icons.home },
    { href: 'profile-edit.html', label: 'My Profile', icon: Icons.user },
    ...common,
  ];
}

function isActiveSidebar(href) {
  var path = window.location.pathname.split('/').pop() || 'index.html';
  var tab = new URLSearchParams(window.location.search).get('tab');
  if (tab && href.indexOf('tab=' + tab) !== -1) return true;
  return path === href;
}

/* ----- Interest Request Modal ----- */
function showInterestModal() {
  var modal = document.getElementById('interest-modal');
  if (modal) {
    modal.classList.add('open');
  }
}

function closeInterestModal() {
  var modal = document.getElementById('interest-modal');
  if (modal) {
    modal.classList.remove('open');
  }
}

/* ----- Generic Modal Helpers ----- */
function openModal(id) {
  var el = document.getElementById(id);
  if (el) el.classList.add('open');
}

function closeModal(id) {
  var el = document.getElementById(id);
  if (el) el.classList.remove('open');
}

/* ----- Close modal on overlay click ----- */
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal') && e.target.classList.contains('open')) {
    e.target.classList.remove('open');
  }
});
