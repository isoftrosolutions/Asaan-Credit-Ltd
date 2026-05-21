/**
 * InvestMatch Nepal — Reusable Header Components
 * Pure vanilla JS for static HTML mockups (works fully offline)
 *
 * Usage in any page:
 *
 *   <div id="header-root"></div>
 *   <script src="components.js"></script>
 *   <script>
 *     // For public pages
 *     injectHeader('public');
 *
 *     // For dashboards (pass 'investor' or 'entrepreneur')
 *     injectHeader('dashboard', 'investor');
 *
 *     // For admin panel
 *     injectHeader('admin');
 *   </script>
 */

(function() {
  // Prevent double injection
  if (window.__investmatchHeadersLoaded) return;
  window.__investmatchHeadersLoaded = true;

  // ============================================
  // PROFESSIONAL ICON SET (Heroicons style - inline SVGs)
  // ============================================
  const icons = {
    overview: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1v-5.5" /></svg>`,
    discover: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>`,
    interests: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2 3 3 0 003 3 3 3 0 003-3 2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" /></svg>`,
    connections: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 01-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 01-2 2 2 2 0 01-2-2 2 2 0 012-2 2 2 0 012 2z" /></svg>`,
    profile: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>`,
    edit: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>`,
    requests: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2.009 2.009 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>`,
    notifications: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>`,
    logout: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>`,
  };

  function createPublicHeader() {
    return `
      <nav class="nav">
        <div class="container nav-content">
          <a href="index.html" class="logo">Invest<span>Match</span></a>
          
          <div class="nav-links" id="nav-links-public">
            <a href="index.html">Home</a>
            <a href="about.html">About</a>
            <a href="how-it-works.html">How it Works</a>
            <a href="browse-entrepreneurs.html">Browse Pitches</a>
            <a href="browse-investors.html">Investors</a>
          </div>
          
          <div class="nav-actions">
            <a href="login.html" class="btn btn-ghost">Log in</a>
            <a href="sign-up.html" class="btn btn-accent">Get Started</a>
            
            <!-- Mobile hamburger -->
            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-public')" aria-label="Menu" style="display:none; background:none; border:1.5px solid #ccc; border-radius:8px; padding:6px 10px; font-size:1.1rem; cursor:pointer;">☰</button>
            
            <!-- Demo role switcher -->
            <div onclick="switchDemoRole()" style="cursor:pointer; margin-left:8px; font-size:0.75rem; padding:4px 10px; background:#f0edeb; border-radius:999px; display:flex; align-items:center; gap:4px;">
              <span id="demo-role">Demo: Guest</span>
            </div>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-public" style="display:none; background:#fff; border-top:1px solid #eae8e6; padding:0.75rem 1rem;">
          <!-- Populated by JS toggle if needed, links duplicated for mobile -->
        </div>
      </nav>
    `;
  }

  function createDashboardHeader(role) {
    const isInvestor = role === 'investor';
    const links = isInvestor 
      ? `<a href="browse-entrepreneurs.html">Discover</a>
         <a href="investor-dashboard.html" class="active">Dashboard</a>
         <a href="browse-investors.html">Network</a>`
      : `<a href="pitch-edit.html">Edit Pitch</a>
         <a href="entrepreneur-dashboard.html" class="active">Dashboard</a>`;

    const actions = isInvestor
      ? `<a href="notifications-settings.html" class="btn btn-ghost" style="display:flex; align-items:center; gap:4px;">${icons.notifications} 4</a>
         <a href="profile-edit.html" class="btn btn-secondary btn-sm">My Profile</a>
         <a href="index.html" onclick="logout()" class="btn btn-ghost btn-sm">Logout</a>`
      : `<a href="notifications-settings.html" class="btn btn-ghost" style="display:flex; align-items:center; gap:4px;">${icons.notifications} 7</a>
         <a href="pitch-edit.html" class="btn btn-secondary btn-sm">My Pitch</a>
         <a href="index.html" onclick="logout()" class="btn btn-ghost btn-sm">Logout</a>`;

    return `
      <nav class="nav">
        <div class="container nav-content">
          <a href="index.html" class="logo">Invest<span>Match</span></a>
          <div class="nav-links" id="nav-links-${role}">
            ${links}
          </div>
          <div class="nav-actions">
            ${actions}
            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-${role}')" aria-label="Menu" style="display:none; background:none; border:1.5px solid #ccc; border-radius:8px; padding:6px 10px; font-size:1.1rem; cursor:pointer;">☰</button>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-${role}" style="display:none; background:#fff; border-top:1px solid #eae8e6; padding:0.75rem 1rem;"></div>
      </nav>
    `;
  }

  function createAdminHeader() {
    return `
      <nav class="nav" style="background:#141413; color:white;">
        <div class="container nav-content">
          <a href="index.html" class="logo" style="color:white;">Invest<span style="color:#f37338;">Match</span> 
            <span style="font-size:0.65rem; background:#333; padding:1px 5px; border-radius:4px; margin-left:4px;">ADMIN</span>
          </a>
          
          <div class="nav-links" id="nav-links-admin" style="display:none;"></div>
          
          <div class="nav-actions">
            <a href="index.html" class="btn btn-ghost" style="color:#ccc; font-size:0.85rem; padding:0.5rem 0.9rem;">Exit to Public Site</a>
            <!-- Mobile hamburger for admin -->
            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-admin')" aria-label="Menu" style="display:none; background:none; border:1.5px solid #555; border-radius:8px; padding:8px 10px; font-size:1.35rem; cursor:pointer; color:#ccc;">☰</button>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-admin" style="display:none; background:#1f1f1f; color:#ccc; border-top:1px solid #333; padding:0.75rem 1rem;"></div>
      </nav>
    `;
  }

  // Main injection function
  window.injectHeader = function(type, role = null) {
    const root = document.getElementById('header-root');
    if (!root) {
      console.warn('[InvestMatch] <div id="header-root"></div> not found in page.');
      return;
    }

    let html = '';
    if (type === 'public') {
      html = createPublicHeader();
    } else if (type === 'dashboard') {
      html = createDashboardHeader(role || 'investor');
    } else if (type === 'admin') {
      html = createAdminHeader();
    } else {
      html = createPublicHeader();
    }

    root.innerHTML = html;

    // Restore demo role display if it exists
    restoreDemoRole();
  };

  // Demo role switcher (global)
  window.switchDemoRole = function() {
    const roleEl = document.getElementById('demo-role');
    if (!roleEl) return;

    const current = roleEl.textContent;

    if (current.includes('Guest')) {
      roleEl.textContent = 'Demo: Verified Investor';
      localStorage.setItem('demoRole', 'investor');
    } else if (current.includes('Investor')) {
      roleEl.textContent = 'Demo: Verified Entrepreneur';
      localStorage.setItem('demoRole', 'entrepreneur');
    } else {
      roleEl.textContent = 'Demo: Guest';
      localStorage.setItem('demoRole', 'guest');
    }
  };

  window.logout = function() {
    localStorage.removeItem('demoRole');
    window.location.href = 'index.html';
  };

  function restoreDemoRole() {
    const roleEl = document.getElementById('demo-role');
    if (!roleEl) return;

    const saved = localStorage.getItem('demoRole');
    if (saved === 'investor') {
      roleEl.textContent = 'Demo: Verified Investor';
    } else if (saved === 'entrepreneur') {
      roleEl.textContent = 'Demo: Verified Entrepreneur';
    } else {
      roleEl.textContent = 'Demo: Guest';
    }
  }

  // Auto-inject if data-header attribute is present (convenience)
  document.addEventListener('DOMContentLoaded', () => {
    const auto = document.querySelector('[data-header]');
    if (auto) {
      const type = auto.getAttribute('data-header');
      const role = auto.getAttribute('data-role');
      window.injectHeader(type, role);
    }
  });

  // ============================================
  // REUSABLE SIDEBAR COMPONENT (for dashboards)
  // ============================================

  function createInvestorSidebar() {
    return `
      <div class="sidebar">
        <div style="padding:0 0.5rem 1rem; border-bottom:1px solid #e5e2e0; margin-bottom:1rem;">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="avatar avatar-sm">RT</div>
            <div>
              <div style="font-weight:600;">Ramesh Thapa</div>
              <div style="font-size:0.75rem; color:#666;">Verified Investor • Kathmandu</div>
            </div>
          </div>
        </div>
        
        <div class="sidebar-nav">
          <a href="investor-dashboard.html" class="active">${icons.overview} Overview</a>
          <a href="browse-entrepreneurs.html">${icons.discover} Smart Matches</a>
          <a href="browse-entrepreneurs.html">${icons.interests} My Interests (8)</a>
          <a href="my-connections.html">${icons.connections} My Connections (3)</a>
          <a href="profile-edit.html">${icons.profile} Profile &amp; Preferences</a>
        </div>
        
        <div style="margin-top:2rem; font-size:0.75rem; padding:0 1rem; color:#888;">
          7 new pitches matched your mandate this week
        </div>
      </div>
    `;
  }

  function createEntrepreneurSidebar() {
    return `
      <div class="sidebar">
        <div style="padding-bottom:1rem; border-bottom:1px solid #e5e2e0; margin-bottom:1rem;">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="avatar avatar-sm">AK</div>
            <div>
              <div style="font-weight:600;">Anjali K.C.</div>
              <div style="font-size:0.75rem; color:#666;">Founder, Aarohan Foods • Verified</div>
            </div>
          </div>
        </div>
        <div class="sidebar-nav">
          <a href="entrepreneur-dashboard.html" class="active">${icons.overview} Overview</a>
          <a href="pitch-edit.html">${icons.edit} Edit Pitch &amp; Media</a>
          <a href="my-connections.html">${icons.requests} Interest Requests (7)</a>
          <a href="my-connections.html">${icons.connections} My Connections (2)</a>
          <a href="notifications-settings.html">${icons.notifications} Notifications</a>
        </div>
      </div>
    `;
  }

  window.injectSidebar = function(role) {
    // Look for existing .sidebar and replace it, or find a placeholder
    let sidebarContainer = document.querySelector('.sidebar');
    
    if (!sidebarContainer) {
      // Try to find a placeholder div
      sidebarContainer = document.getElementById('sidebar-root');
    }
    
    if (sidebarContainer) {
      if (role === 'investor') {
        sidebarContainer.outerHTML = createInvestorSidebar();
      } else if (role === 'entrepreneur') {
        sidebarContainer.outerHTML = createEntrepreneurSidebar();
      }
    } else {
      console.warn('[InvestMatch] No .sidebar or #sidebar-root found for sidebar injection.');
    }
  };

  // ============================================
  // REUSABLE FOOTER COMPONENT
  // ============================================

  function createFooter() {
    return `
      <footer class="footer">
        <div class="container" style="display:flex; justify-content:space-between; font-size:0.9rem; flex-wrap:wrap; gap:1rem;">
          <div>© 2026 InvestMatch Nepal • iSoftro Solutions</div>
          <div style="display:flex; gap:2rem;">
            <a href="legal.html">Privacy</a>
            <a href="legal.html">Terms</a>
            <a href="support.html">Support</a>
            <a href="mailto:hello@investmatch.com.np">Contact</a>
          </div>
        </div>
      </footer>
    `;
  }

  window.injectFooter = function() {
    const footerRoot = document.getElementById('footer-root');
    if (footerRoot) {
      footerRoot.innerHTML = createFooter();
    } else {
      // If no placeholder, we can optionally append at end of body
      console.warn('[InvestMatch] No #footer-root found. Add <div id="footer-root"></div> before </body>');
    }
  };

  // Convenience: Auto inject footer if placeholder exists
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('footer-root')) {
      window.injectFooter();
    }
    // Show mobile hamburger buttons on small screens
    const mql = window.matchMedia('(max-width: 640px)');
    function showMobileBtns(matches) {
      document.querySelectorAll('.mobile-menu-btn').forEach(btn => {
        btn.style.display = matches ? 'inline-block' : 'none';
      });
    }
    showMobileBtns(mql.matches);
    mql.addEventListener('change', e => showMobileBtns(e.matches));
  });

  // Mobile nav toggle (hamburger)
  window.toggleMobileNav = function(linkContainerId) {
    const drawerId = linkContainerId.replace('nav-links', 'mobile-drawer');
    const drawer = document.getElementById(drawerId);
    if (!drawer) return;

    const btn = event.currentTarget || null; // not always reliable from inline onclick

    if (drawer.style.display === 'block') {
      drawer.style.display = 'none';
      // Try to restore hamburger icon if we can find sibling button
      const headerNav = drawer.parentElement;
      const ham = headerNav && headerNav.querySelector('.mobile-menu-btn');
      if (ham) ham.textContent = '☰';
      return;
    }

    // Clone the nav links into drawer for mobile
    const linksContainer = document.getElementById(linkContainerId);
    if (linksContainer) {
      drawer.innerHTML = `<div style="display:flex; flex-direction:column; gap:0.5rem;">${linksContainer.innerHTML}</div>`;
    }
    drawer.style.display = 'block';

    // Change hamburger to close icon
    const headerNav = drawer.parentElement;
    const ham = headerNav && headerNav.querySelector('.mobile-menu-btn');
    if (ham) ham.textContent = '✕';
  };

})();
