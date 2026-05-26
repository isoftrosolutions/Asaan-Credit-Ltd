/**
 * SMERGERS-style Platform — Reusable Components
 * Pure vanilla JS for static HTML mockup
 *
 * Usage:
 *   <div id="header-root"></div>
 *   <script src="components.js"></script>
 *   <script>injectHeader('public');</script>
 */

(function() {
  if (window.__investmatchComponentsLoaded) return;
  window.__investmatchComponentsLoaded = true;

  window.switchDemoRole = function() {
    const roleEl = document.getElementById('demo-role');
    if (!roleEl) return;
    const current = roleEl.textContent;
    if (current.includes('Guest')) {
      roleEl.textContent = 'Demo: Investor';
      localStorage.setItem('demoRole', 'investor');
    } else if (current.includes('Investor')) {
      roleEl.textContent = 'Demo: Business Owner';
      localStorage.setItem('demoRole', 'owner');
    } else if (current.includes('Owner')) {
      roleEl.textContent = 'Demo: Advisor';
      localStorage.setItem('demoRole', 'advisor');
    } else {
      roleEl.textContent = 'Demo: Guest';
      localStorage.setItem('demoRole', 'guest');
    }
  };

  window.logout = function() {
    localStorage.removeItem('demoRole');
    window.location.href = 'index.html';
  };

  window.restoreDemoRole = function() {
    const roleEl = document.getElementById('demo-role');
    if (!roleEl) return;
    const saved = localStorage.getItem('demoRole');
    const map = { investor: 'Demo: Investor', owner: 'Demo: Business Owner', advisor: 'Demo: Advisor' };
    if (saved && map[saved]) roleEl.textContent = map[saved];
    else roleEl.textContent = 'Demo: Guest';
  }

  function createInvestorSidebar() {
    return `
      <div class="sidebar">
        <div style="padding:0 0 1rem; border-bottom:1px solid var(--surface-container-high); margin-bottom:1rem;">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="avatar avatar-sm">RT</div>
            <div>
              <div style="font-weight:600;">Ramesh Thapa</div>
              <div style="font-size:0.75rem; color:#666;">Individual Investor</div>
            </div>
          </div>
        </div>
        <div class="sidebar-nav">
          <a href="investor-dashboard.html" class="active">${window.icons.overview} Overview</a>
          <a href="browse-businesses.html">${window.icons.discover} Browse Businesses</a>
          <a href="my-connections.html">${window.icons.interests} My Interests (8)</a>
          <a href="my-connections.html">${window.icons.connections} My Connections (3)</a>
          <a href="profile-edit.html">${window.icons.profile} Profile &amp; Preferences</a>
        </div>
      </div>
    `;
  }

  function createOwnerSidebar() {
    return `
      <div class="sidebar">
        <div style="padding-bottom:1rem; border-bottom:1px solid var(--surface-container-high); margin-bottom:1rem;">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="avatar avatar-sm">AK</div>
            <div>
              <div style="font-weight:600;">Aarohan Kitchens</div>
              <div style="font-size:0.75rem; color:#666;">Business Owner • Verified</div>
            </div>
          </div>
        </div>
        <div class="sidebar-nav">
          <a href="business-owner-dashboard.html" class="active">${window.icons.overview} Overview</a>
          <a href="pitch-edit.html">${window.icons.edit} Edit Business Profile</a>
          <a href="my-connections.html">${window.icons.requests} Interest Requests (7)</a>
          <a href="my-connections.html">${window.icons.connections} My Connections (2)</a>
          <a href="notifications-settings.html">${window.icons.notifications} Notifications</a>
        </div>
      </div>
    `;
  }

  window.injectSidebar = function(role) {
    let sidebarContainer = document.querySelector('.sidebar') || document.getElementById('sidebar-root');
    if (sidebarContainer) {
      if (role === 'investor' || role === 'buyer') {
        sidebarContainer.innerHTML = createInvestorSidebar();
      } else if (role === 'owner' || role === 'entrepreneur') {
        sidebarContainer.innerHTML = createOwnerSidebar();
      }
    }
  };

  function createFooter() {
    return `
      <footer class="footer-premium">
        <div class="container">
          <div class="footer-premium-grid">
            <div class="footer-premium-col">
              <div class="footer-premium-brand">Invest<span>Match</span></div>
              <p class="footer-premium-desc">The premium marketplace for buying, selling, franchising, and funding SMEs. Connecting verified business owners with qualified investors.</p>
            </div>
            <div class="footer-premium-col">
              <h5>Get Started</h5>
              <a href="how-it-works.html#sell">Sell Your Business</a>
              <a href="how-it-works.html#invest">Finance Your Business</a>
              <a href="how-it-works.html#buy">Buy a Business</a>
              <a href="how-it-works.html#invest">Invest in a Business</a>
              <a href="how-it-works.html#franchise">Franchise Your Business</a>
              <a href="sign-up.html">Register as Advisor</a>
            </div>
            <div class="footer-premium-col">
              <h5>Businesses</h5>
              <a href="browse-businesses.html">Businesses For Sale</a>
              <a href="browse-businesses.html?type=investment">Investment Opportunities</a>
              <a href="browse-businesses.html?type=loan">Businesses Seeking Loan</a>
              <a href="browse-businesses.html?type=asset">Assets For Sale</a>
            </div>
            <div class="footer-premium-col">
              <h5>Investors</h5>
              <a href="browse-investors.html">Individual Investors</a>
              <a href="browse-investors.html?type=buyer">Business Buyers</a>
              <a href="browse-investors.html?type=corporate">Corporate Investors</a>
              <a href="browse-investors.html?type=vc">Venture Capital Firms</a>
              <a href="browse-investors.html?type=pe">Private Equity Firms</a>
              <a href="browse-investors.html?type=fund">Family Offices &amp; Funds</a>
            </div>
            <div class="footer-premium-col">
              <h5>Company</h5>
              <a href="about.html">About</a>
              <a href="about.html#testimonials">Testimonials</a>
              <a href="about.html#press">Press</a>
              <a href="support.html">FAQs</a>
              <a href="legal.html">Privacy Policy</a>
              <a href="legal.html">Terms of Use</a>
            </div>
          </div>
          <div class="footer-premium-bottom">
            <div>© 2026 InvestMatch Nepal • iSoftro Solutions</div>
            <div class="footer-premium-social">
              <a href="#" aria-label="Facebook">Facebook</a>
              <a href="#" aria-label="LinkedIn">LinkedIn</a>
              <a href="#" aria-label="YouTube">YouTube</a>
              <a href="#" aria-label="Twitter">X</a>
              <a href="#" aria-label="Instagram">Instagram</a>
            </div>
          </div>
        </div>
      </footer>
    `;
  }

  window.injectFooter = function() {
    const footerRoot = document.getElementById('footer-root');
    if (footerRoot) footerRoot.innerHTML = createFooter();
  };

  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('footer-root')) window.injectFooter();
  });
})();
