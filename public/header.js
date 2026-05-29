/* =============================================
   InvestMatch Nepal — Header Navigation
   ============================================= */

var headerState = {
  mobileOpen: false,
  type: 'public',
  role: null
};

function injectHeader(type, role) {
  headerState.type = type || 'public';
  headerState.role = role || localStorage.getItem('demoRole') || null;

  var root = document.getElementById('header-root');
  if (!root) return;

  var html = '';

  if (type === 'public') {
    html = publicHeaderHTML();
  } else if (type === 'admin') {
    html = adminHeaderHTML();
  } else {
    html = dashboardHeaderHTML(role);
  }

  root.innerHTML = html;

  // Mobile toggle
  var toggle = document.querySelector('.mobile-toggle');
  if (toggle) {
    toggle.addEventListener('click', function() {
      toggleMobileDrawer();
    });
  }

  // Mobile drawer close
  var closeBtn = document.querySelector('.mobile-drawer-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', function() {
      closeMobileDrawer();
    });
  }

  // Overlay click
  var overlay = document.querySelector('.mobile-drawer-overlay');
  if (overlay) {
    overlay.addEventListener('click', function() {
      closeMobileDrawer();
    });
  }

  // Fetch notification count
  fetchNotificationCount();
}

function publicHeaderHTML() {
  return `
    <header class="header-premium">
      <div class="header-premium-inner">
        <a href="index.html" class="header-premium-brand">
          <svg viewBox="0 0 32 32" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="32" height="32" rx="8" fill="#C41E3A"/>
            <path d="M8 16c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8z" fill="rgba(255,255,255,0.2)"/>
            <path d="M12 16c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z" fill="white"/>
            <path d="M16 8l2.5 5 5.5.8-4 3.9 1 5.3L16 20l-5 2.7 1-5.3-4-3.9 5.5-.8L16 8z" fill="white" opacity="0.3"/>
          </svg>
          Invest<span class="brand-highlight">Match</span>
        </a>

        <ul class="nav-links">
          <li><a href="browse-businesses.html" class="nav-link">Browse</a></li>
          <li><a href="how-it-works.html" class="nav-link">How It Works</a></li>
          <li><a href="about.html" class="nav-link">About</a></li>
          <li><a href="support.html" class="nav-link">Support</a></li>
        </ul>

        <div class="header-actions">
          <a href="login.html" class="nav-link" style="font-weight:600;">Log in</a>
          <a href="sign-up.html" class="btn-join">Join Free</a>
          <button class="mobile-toggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>
    <div class="mobile-drawer-overlay" id="mobile-overlay"></div>
    <div class="mobile-drawer" id="mobile-drawer">
      <div class="mobile-drawer-header">
        <strong style="font-size:1.1rem;">Menu</strong>
        <button class="mobile-drawer-close">&times;</button>
      </div>
      <div class="mobile-drawer-nav">
        <a href="index.html" class="mobile-drawer-link">Home</a>
        <a href="browse-businesses.html" class="mobile-drawer-link">Browse Businesses</a>
        <a href="browse-investors.html" class="mobile-drawer-link">Browse Investors</a>
        <a href="how-it-works.html" class="mobile-drawer-link">How It Works</a>
        <a href="about.html" class="mobile-drawer-link">About</a>
        <a href="support.html" class="mobile-drawer-link">Support</a>
        <div class="mobile-drawer-divider"></div>
        <a href="login.html" class="mobile-drawer-link" style="font-weight:600;">Log in</a>
        <a href="sign-up.html" class="btn-join" style="text-align:center;margin-top:0.5rem;">Join Free</a>
      </div>
    </div>
  `;
}

function dashboardHeaderHTML(role) {
  var initials = 'U';
  var name = 'User';
  var targets = {
    investor: { name: 'Ramesh Thapa', initials: 'RT' },
    owner: { name: 'Aarohan Kitchens', initials: 'AK' },
    entrepreneur: { name: 'Anjali K.C.', initials: 'AK' },
    advisor: { name: 'Ramesh Thapa', initials: 'RT' },
    admin: { name: 'Admin', initials: 'AD' }
  };
  if (targets[role]) {
    initials = targets[role].initials;
    name = targets[role].name;
  }

  var dashboardLink = {
    investor: 'investor-dashboard.html',
    owner: 'business-owner-dashboard.html',
    entrepreneur: 'entrepreneur-dashboard.html',
    advisor: 'investor-dashboard.html',
    admin: 'admin.html'
  }[role] || 'investor-dashboard.html';

  var settingsLink = {
    investor: 'profile-edit.html',
    owner: 'pitch-edit.html',
    entrepreneur: 'pitch-edit.html',
    advisor: 'profile-edit.html',
    admin: 'admin.html'
  }[role] || 'profile-edit.html';

  return `
    <header class="header-premium">
      <div class="header-premium-inner">
        <a href="index.html" class="header-premium-brand">
          <svg viewBox="0 0 32 32" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="32" height="32" rx="8" fill="#C41E3A"/>
            <path d="M8 16c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8z" fill="rgba(255,255,255,0.2)"/>
            <path d="M12 16c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z" fill="white"/>
            <path d="M16 8l2.5 5 5.5.8-4 3.9 1 5.3L16 20l-5 2.7 1-5.3-4-3.9 5.5-.8L16 8z" fill="white" opacity="0.3"/>
          </svg>
          Invest<span class="brand-highlight">Match</span>
        </a>

        <ul class="nav-links">
          <li><a href="${dashboardLink}" class="nav-link">Dashboard</a></li>
          <li><a href="browse-businesses.html" class="nav-link">Browse</a></li>
          <li><a href="my-connections.html" class="nav-link">Connections</a></li>
        </ul>

        <div class="header-actions">
          <div class="header-notification" onclick="location.href='notifications-settings.html'" title="Notifications">
            ${Icons.bell}
            <span class="notification-count" id="notif-count">0</span>
          </div>
          <div class="header-user">
            <div class="header-user-avatar">${initials}</div>
            <span class="header-user-name">${name}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="color:#888;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            <div class="header-user-dropdown">
              <a href="${dashboardLink}" class="header-user-dropdown-item">
                ${Icons.home} Dashboard
              </a>
              <a href="${settingsLink}" class="header-user-dropdown-item">
                ${Icons.settings} Settings
              </a>
              <a href="my-connections.html" class="header-user-dropdown-item">
                ${Icons.chat} Connections
              </a>
              <a href="notifications-settings.html" class="header-user-dropdown-item">
                ${Icons.bell} Notifications
              </a>
              <div style="height:1px;background:var(--border-light);margin:0.25rem 0;"></div>
              <a href="login.html" class="header-user-dropdown-item danger" onclick="localStorage.removeItem('demoRole')">
                ${Icons.logout} Sign Out
              </a>
            </div>
          </div>
          <button class="mobile-toggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>
    <div class="mobile-drawer-overlay" id="mobile-overlay"></div>
    <div class="mobile-drawer" id="mobile-drawer">
      <div class="mobile-drawer-header">
        <div style="display:flex;align-items:center;gap:10px;">
          <div class="header-user-avatar">${initials}</div>
          <div><strong>${name}</strong></div>
        </div>
        <button class="mobile-drawer-close">&times;</button>
      </div>
      <div class="mobile-drawer-nav">
        <a href="${dashboardLink}" class="mobile-drawer-link">Dashboard</a>
        <a href="browse-businesses.html" class="mobile-drawer-link">Browse Businesses</a>
        <a href="browse-investors.html" class="mobile-drawer-link">Browse Investors</a>
        <a href="my-connections.html" class="mobile-drawer-link">My Connections</a>
        <a href="${settingsLink}" class="mobile-drawer-link">Settings</a>
        <a href="notifications-settings.html" class="mobile-drawer-link">Notifications</a>
        <div class="mobile-drawer-divider"></div>
        <a href="login.html" class="mobile-drawer-link" style="color:var(--brand-red);" onclick="localStorage.removeItem('demoRole')">Sign Out</a>
      </div>
    </div>
  `;
}

function adminHeaderHTML() {
  return `
    <header class="header-premium">
      <div class="header-premium-inner">
        <a href="admin.html" class="header-premium-brand">
          <svg viewBox="0 0 32 32" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="32" height="32" rx="8" fill="#C41E3A"/>
            <path d="M8 16c0-4.418 3.582-8 8-8s8 3.582 8 8-3.582 8-8 8-8-3.582-8-8z" fill="rgba(255,255,255,0.2)"/>
            <path d="M12 16c0-2.209 1.791-4 4-4s4 1.791 4 4-1.791 4-4 4-4-1.791-4-4z" fill="white"/>
          </svg>
          Invest<span class="brand-highlight">Match</span> <span style="font-size:0.7rem;background:var(--brand-red);color:white;padding:2px 8px;border-radius:999px;font-weight:600;">Admin</span>
        </a>

        <ul class="nav-links">
          <li><a href="admin.html" class="nav-link">Dashboard</a></li>
        </ul>

        <div class="header-actions">
          <div class="header-user">
            <div class="header-user-avatar">AD</div>
            <span class="header-user-name">Admin</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="color:#888;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            <div class="header-user-dropdown">
              <a href="admin.html" class="header-user-dropdown-item">${Icons.home} Dashboard</a>
              <a href="login.html" class="header-user-dropdown-item danger" onclick="localStorage.removeItem('demoRole')">${Icons.logout} Sign Out</a>
            </div>
          </div>
          <button class="mobile-toggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
          </button>
        </div>
      </div>
    </header>
    <div class="mobile-drawer-overlay" id="mobile-overlay"></div>
    <div class="mobile-drawer" id="mobile-drawer">
      <div class="mobile-drawer-header">
        <strong>Admin Menu</strong>
        <button class="mobile-drawer-close">&times;</button>
      </div>
      <div class="mobile-drawer-nav">
        <a href="admin.html" class="mobile-drawer-link">Dashboard</a>
        <div class="mobile-drawer-divider"></div>
        <a href="login.html" class="mobile-drawer-link" style="color:var(--brand-red);" onclick="localStorage.removeItem('demoRole')">Sign Out</a>
      </div>
    </div>
  `;
}

function toggleMobileDrawer() {
  headerState.mobileOpen = !headerState.mobileOpen;
  var drawer = document.getElementById('mobile-drawer');
  var overlay = document.getElementById('mobile-overlay');
  var toggle = document.querySelector('.mobile-toggle');
  if (drawer) drawer.classList.toggle('open', headerState.mobileOpen);
  if (overlay) overlay.classList.toggle('open', headerState.mobileOpen);
  if (toggle) toggle.classList.toggle('open', headerState.mobileOpen);
  document.body.style.overflow = headerState.mobileOpen ? 'hidden' : '';
}

function closeMobileDrawer() {
  headerState.mobileOpen = false;
  var drawer = document.getElementById('mobile-drawer');
  var overlay = document.getElementById('mobile-overlay');
  var toggle = document.querySelector('.mobile-toggle');
  if (drawer) drawer.classList.remove('open');
  if (overlay) overlay.classList.remove('open');
  if (toggle) toggle.classList.remove('open');
  document.body.style.overflow = '';
}

function fetchNotificationCount() {
  var badge = document.getElementById('notif-count');
  if (!badge) return;

  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/notifications/unread-count', true);
  xhr.onload = function() {
    if (xhr.status >= 200 && xhr.status < 400) {
      try {
        var data = JSON.parse(xhr.responseText);
        var count = parseInt(data.count) || 0;
        badge.textContent = count > 99 ? '99+' : count;
        if (count === 0) {
          badge.style.display = 'none';
        } else {
          badge.style.display = 'flex';
        }
      } catch(e) {
        badge.textContent = '0';
        badge.style.display = 'none';
      }
    } else {
      badge.textContent = '0';
      badge.style.display = 'none';
    }
  };
  xhr.onerror = function() {
    badge.textContent = '0';
    badge.style.display = 'none';
  };
  xhr.send();
}
