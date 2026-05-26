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
  if (window.__investmatchHeadersLoaded) return;
  window.__investmatchHeadersLoaded = true;

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
    business: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>`,
    franchise: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>`,
    search: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>`,
    globe: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>`,
    plus: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>`,
    chevronDown: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>`,
    chat: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>`,
    help: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`,
    star: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
  };

  function createPublicHeader() {
    return `
      <nav class="nav nav-premium">
        <div class="container nav-content">
          <a href="index.html" class="logo">Invest<span>Match</span></a>

          <div class="nav-links" id="nav-links-public">
            <div class="nav-dropdown">
              <a href="browse-businesses.html" class="dropdown-trigger">Businesses ${icons.chevronDown}</a>
              <div class="dropdown-menu">
                <a href="browse-businesses.html">Businesses for Sale</a>
                <a href="browse-businesses.html?type=investment">Investment Opportunities</a>
                <a href="browse-businesses.html?type=loan">Businesses Seeking Loan</a>
                <a href="browse-businesses.html?type=asset">Assets For Sale</a>
              </div>
            </div>
            <a href="browse-investors.html">Investors</a>
            <a href="browse-franchises.html">Franchises</a>
            <a href="how-it-works.html">How It Works</a>
            <div class="nav-dropdown">
              <a href="about.html" class="dropdown-trigger">Company ${icons.chevronDown}</a>
              <div class="dropdown-menu">
                <a href="about.html">Our Story</a>
                <a href="support.html">Contact Us</a>
                <a href="about.html#press">Press</a>
                <a href="about.html#testimonials">Testimonials</a>
                <a href="about.html#blog">Blog</a>
                <a href="about.html#industry-watch">Industry Watch</a>
              </div>
            </div>
          </div>

          <div class="nav-actions">
            <button class="lang-btn" onclick="alert('Language switcher (demo)')" aria-label="Language">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
              <span>EN</span>
            </button>
            <a href="login.html" class="btn btn-ghost btn-sm" style="font-size:0.88rem; font-weight:600;">Log in</a>
            <a href="sign-up.html" class="btn-premium btn-sm" style="padding:0.55rem 1.35rem;">Get Started Free</a>

            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-public', this)" aria-label="Menu">☰</button>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-public"></div>
      </nav>
      <div class="chat-widget" onclick="alert('Chat widget (demo)')">
        ${icons.chat} <span>Chat</span>
      </div>
    `;
  }

  function createDashboardHeader(role) {
    const isInvestor = role === 'investor' || role === 'buyer';
    const isOwner = role === 'owner' || role === 'entrepreneur';

    const links = isInvestor
      ? `<a href="browse-businesses.html">Discover</a>
         <a href="investor-dashboard.html" class="active">Dashboard</a>
         <a href="browse-investors.html">Network</a>`
      : `<a href="business-detail.html">My Business</a>
         <a href="business-owner-dashboard.html" class="active">Dashboard</a>`;

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
            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-${role}', this)" aria-label="Menu">☰</button>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-${role}"></div>
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
            <button class="mobile-menu-btn" onclick="toggleMobileNav('nav-links-admin', this)" aria-label="Menu">☰</button>
          </div>
        </div>
        <div class="mobile-nav-drawer" id="mobile-drawer-admin"></div>
      </nav>
    `;
  }

  window.injectHeader = function(type, role = null) {
    const root = document.getElementById('header-root');
    if (!root) return;

    let html = '';
    if (type === 'public') html = createPublicHeader();
    else if (type === 'dashboard') html = createDashboardHeader(role || 'investor');
    else if (type === 'admin') html = createAdminHeader();
    else html = createPublicHeader();

    root.innerHTML = html;
    restoreDemoRole();
    initDropdowns();
  };

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

  function restoreDemoRole() {
    const roleEl = document.getElementById('demo-role');
    if (!roleEl) return;
    const saved = localStorage.getItem('demoRole');
    const map = { investor: 'Demo: Investor', owner: 'Demo: Business Owner', advisor: 'Demo: Advisor' };
    if (saved && map[saved]) roleEl.textContent = map[saved];
    else roleEl.textContent = 'Demo: Guest';
  }

  function initDropdowns() {
    document.querySelectorAll('.nav-dropdown').forEach(dd => {
      dd.addEventListener('mouseenter', () => {
        const menu = dd.querySelector('.dropdown-menu');
        if (menu) menu.classList.add('open');
      });
      dd.addEventListener('mouseleave', () => {
        const menu = dd.querySelector('.dropdown-menu');
        if (menu) menu.classList.remove('open');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const auto = document.querySelector('[data-header]');
    if (auto) {
      const type = auto.getAttribute('data-header');
      const role = auto.getAttribute('data-role');
      window.injectHeader(type, role);
    }
  });

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
          <a href="investor-dashboard.html" class="active">${icons.overview} Overview</a>
          <a href="browse-businesses.html">${icons.discover} Browse Businesses</a>
          <a href="my-connections.html">${icons.interests} My Interests (8)</a>
          <a href="my-connections.html">${icons.connections} My Connections (3)</a>
          <a href="profile-edit.html">${icons.profile} Profile &amp; Preferences</a>
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
          <a href="business-owner-dashboard.html" class="active">${icons.overview} Overview</a>
          <a href="pitch-edit.html">${icons.edit} Edit Business Profile</a>
          <a href="my-connections.html">${icons.requests} Interest Requests (7)</a>
          <a href="my-connections.html">${icons.connections} My Connections (2)</a>
          <a href="notifications-settings.html">${icons.notifications} Notifications</a>
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

  window.toggleMobileNav = function(linkContainerId, btnEl) {
    const drawerId = linkContainerId.replace('nav-links', 'mobile-drawer');
    const drawer = document.getElementById(drawerId);
    if (!drawer) return;

    const isOpen = drawer.classList.contains('open');

    if (isOpen) {
      drawer.classList.remove('open');
      drawer.style.maxHeight = '0px';
      drawer.style.opacity = '0';
      if (btnEl) btnEl.textContent = '☰';
      if (window.__navOutsideCloseHandler) {
        document.removeEventListener('click', window.__navOutsideCloseHandler);
        window.__navOutsideCloseHandler = null;
      }
      setTimeout(() => { drawer.style.display = 'none'; }, 280);
      return;
    }

    const linksContainer = document.getElementById(linkContainerId);
    let contentHTML = '';
    if (linksContainer && linksContainer.innerHTML.trim()) {
      // Flatten dropdown menus: extract links from .dropdown-menu and remove wrapping
      const temp = document.createElement('div');
      temp.innerHTML = linksContainer.innerHTML;
      temp.querySelectorAll('.nav-dropdown').forEach(dd => {
        const ddLinks = dd.querySelectorAll('.dropdown-menu a');
        const flatDiv = document.createElement('div');
        flatDiv.style.cssText = 'padding-left:1rem; border-left:2px solid var(--surface-container-high); margin:0.25rem 0;';
        ddLinks.forEach(link => {
          const clone = document.createElement('a');
          clone.href = link.getAttribute('href');
          clone.textContent = link.textContent;
          clone.style.fontSize = '0.85rem';
          clone.style.padding = '0.5rem 0.9rem';
          clone.style.display = 'flex';
          clone.style.alignItems = 'center';
          clone.style.minHeight = '40px';
          clone.style.color = 'var(--on-surface-variant)';
          clone.style.textDecoration = 'none';
          clone.style.borderRadius = '0.5rem';
          flatDiv.appendChild(clone);
        });
        // Replace the dropdown trigger with the parent link, then append sub-links
        const trigger = dd.querySelector('.dropdown-trigger');
        if (trigger) {
          const parentLink = document.createElement('a');
          parentLink.href = trigger.getAttribute('href');
          parentLink.textContent = trigger.textContent.replace(/▼/g, '').trim();
          parentLink.style.fontWeight = '600';
          parentLink.style.display = 'flex';
          parentLink.style.alignItems = 'center';
          parentLink.style.padding = '0.75rem 0.9rem';
          parentLink.style.minHeight = '44px';
          parentLink.style.color = 'var(--on-surface-variant)';
          parentLink.style.textDecoration = 'none';
          parentLink.style.borderRadius = '0.5rem';
          dd.parentNode.replaceChild(parentLink, dd);
          parentLink.insertAdjacentElement('afterend', flatDiv);
        } else {
          dd.parentNode.replaceChild(flatDiv, dd);
        }
      });
      contentHTML = temp.innerHTML;
    }

    drawer.innerHTML = contentHTML
      ? `<div style="display:flex; flex-direction:column; gap:0.25rem; padding:0.25rem 0;">${contentHTML}</div>`
      : '';

    drawer.style.display = 'block';
    drawer.style.maxHeight = '0px';
    drawer.style.opacity = '0';

    requestAnimationFrame(() => {
      drawer.classList.add('open');
      const targetH = Math.min(drawer.scrollHeight + 20, 600);
      drawer.style.maxHeight = targetH + 'px';
      drawer.style.opacity = '1';
    });

    if (btnEl) btnEl.textContent = '✕';

    drawer.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        drawer.classList.remove('open');
        drawer.style.maxHeight = '0px';
        drawer.style.opacity = '0';
        if (btnEl) btnEl.textContent = '☰';
        if (window.__navOutsideCloseHandler) {
          document.removeEventListener('click', window.__navOutsideCloseHandler);
          window.__navOutsideCloseHandler = null;
        }
        setTimeout(() => { drawer.style.display = 'none'; }, 150);
      }, { once: true });
    });

    window.__navOutsideCloseHandler = function(e) {
      const clickInsideDrawer = drawer.contains(e.target);
      const clickOnBtn = btnEl && btnEl.contains(e.target);
      if (!clickInsideDrawer && !clickOnBtn) {
        drawer.classList.remove('open');
        drawer.style.maxHeight = '0px';
        drawer.style.opacity = '0';
        if (btnEl) btnEl.textContent = '☰';
        document.removeEventListener('click', window.__navOutsideCloseHandler);
        window.__navOutsideCloseHandler = null;
        setTimeout(() => { drawer.style.display = 'none'; }, 150);
      }
    };
    setTimeout(() => {
      document.addEventListener('click', window.__navOutsideCloseHandler);
    }, 0);
  };
})();
