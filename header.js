/**
 * InvestMatch Nepal — Modular Header Component
 * Agency-grade vanilla JS implementation
 */

(function() {
  'use strict';

  // Constants for state management
  const BREAKPOINT = 900;
  let activeMobileDrawer = null;

  /**
   * Main Header Injection Engine
   */
  window.injectHeader = function(type = 'public', role = null) {
    const root = document.getElementById('header-root');
    if (!root) return;

    let headerHTML = '';
    
    switch(type) {
      case 'dashboard':
        headerHTML = createDashboardHeader(role);
        break;
      case 'admin':
        headerHTML = createAdminHeader();
        break;
      default:
        headerHTML = createPublicHeader();
    }

    root.innerHTML = headerHTML;
    
    // Lifecycle hooks
    initHeaderLogic();
    if (window.restoreDemoRole) window.restoreDemoRole();
  };

  /**
   * Templates
   */
  function createPublicHeader() {
    return `
      <nav class="im-header im-header-premium">
        <div class="container im-header-content">
          <a href="index.html" class="im-logo">Invest<span>Match</span></a>

          <div class="im-nav-links" id="nav-links-public">
            <div class="im-nav-dropdown">
              <a href="browse-businesses.html" class="im-dropdown-trigger">Businesses ${window.icons.chevronDown}</a>
              <div class="im-dropdown-menu">
                <a href="browse-businesses.html">Businesses for Sale</a>
                <a href="browse-businesses.html?type=investment">Investment Opportunities</a>
                <a href="browse-businesses.html?type=loan">Businesses Seeking Loan</a>
                <a href="browse-businesses.html?type=asset">Assets For Sale</a>
              </div>
            </div>
            <a href="browse-investors.html">Investors</a>
            <a href="browse-franchises.html">Franchises</a>
            <a href="how-it-works.html">How It Works</a>
            <div class="im-nav-dropdown">
              <a href="about.html" class="im-dropdown-trigger">Company ${window.icons.chevronDown}</a>
              <div class="im-dropdown-menu">
                <a href="about.html">Our Story</a>
                <a href="support.html">Contact Us</a>
                <a href="about.html#press">Press</a>
                <a href="about.html#testimonials">Testimonials</a>
                <a href="about.html#blog">Blog</a>
                <a href="about.html#industry-watch">Industry Watch</a>
              </div>
            </div>
          </div>

          <div class="im-nav-actions">
            <button class="im-lang-btn" aria-label="Language">
              ${window.icons.globe}
              <span>EN</span>
            </button>
            <a href="login.html" class="im-btn im-btn-ghost">Log in</a>
            <a href="sign-up.html" class="im-btn-premium">Get Started Free</a>
            <button class="im-mobile-toggle" aria-label="Toggle Menu">☰</button>
          </div>
        </div>
        <div class="im-mobile-drawer" id="mobile-drawer-public"></div>
      </nav>
      <div class="im-chat-widget">
        ${window.icons.chat} <span>Chat</span>
      </div>
    `;
  }

  function createDashboardHeader(role) {
    const isInvestor = role === 'investor' || role === 'buyer';
    
    const links = isInvestor
      ? `<a href="browse-businesses.html">Discover</a>
         <a href="investor-dashboard.html" class="active">Dashboard</a>
         <a href="browse-investors.html">Network</a>`
      : `<a href="business-detail.html">My Business</a>
         <a href="business-owner-dashboard.html" class="active">Dashboard</a>`;

    const actions = isInvestor
      ? `<a href="notifications-settings.html" class="im-btn im-btn-ghost">${window.icons.notifications} <span>4</span></a>
         <a href="profile-edit.html" class="im-btn im-btn-secondary">My Profile</a>
         <a href="index.html" class="im-btn im-btn-ghost im-logout-btn">Logout</a>`
      : `<a href="notifications-settings.html" class="im-btn im-btn-ghost">${window.icons.notifications} <span>7</span></a>
         <a href="pitch-edit.html" class="im-btn im-btn-secondary">My Pitch</a>
         <a href="index.html" class="im-btn im-btn-ghost im-logout-btn">Logout</a>`;

    return `
      <nav class="im-header">
        <div class="container im-header-content">
          <a href="index.html" class="im-logo">Invest<span>Match</span></a>
          <div class="im-nav-links" id="nav-links-dashboard">
            ${links}
          </div>
          <div class="im-nav-actions">
            ${actions}
            <button class="im-mobile-toggle" aria-label="Toggle Menu">☰</button>
          </div>
        </div>
        <div class="im-mobile-drawer" id="mobile-drawer-dashboard"></div>
      </nav>
    `;
  }

  function createAdminHeader() {
    return `
      <nav class="im-header im-header-admin">
        <div class="container im-header-content">
          <a href="index.html" class="im-logo">Invest<span>Match</span> <span class="im-badge">ADMIN</span></a>
          <div class="im-nav-links" id="nav-links-admin"></div>
          <div class="im-nav-actions">
            <a href="index.html" class="im-btn im-btn-ghost">Exit to Public Site</a>
            <button class="im-mobile-toggle" aria-label="Toggle Menu">☰</button>
          </div>
        </div>
        <div class="im-mobile-drawer" id="mobile-drawer-admin"></div>
      </nav>
    `;
  }

  /**
   * Component Lifecycle
   */
  function initHeaderLogic() {
    initDropdowns();
    initMobileNav();
    initLogout();
  }

  function initDropdowns() {
    const dropdowns = document.querySelectorAll('.im-nav-dropdown');
    
    dropdowns.forEach(dd => {
      const trigger = dd.querySelector('.im-dropdown-trigger');
      const menu = dd.querySelector('.im-dropdown-menu');
      if (!trigger || !menu) return;

      // Desktop Hover
      dd.addEventListener('mouseenter', () => {
        if (window.innerWidth > BREAKPOINT) {
          menu.classList.add('is-open');
          dd.classList.add('is-active');
        }
      });

      dd.addEventListener('mouseleave', () => {
        if (window.innerWidth > BREAKPOINT) {
          menu.classList.remove('is-open');
          dd.classList.remove('is-active');
        }
      });

      // Touch / Click Toggle
      trigger.addEventListener('click', (e) => {
        if (window.innerWidth <= BREAKPOINT || ('ontouchstart' in window)) {
          e.preventDefault();
          e.stopPropagation();
          
          const isOpen = menu.classList.contains('is-open');
          
          // Close others
          document.querySelectorAll('.im-dropdown-menu.is-open').forEach(m => {
            if (m !== menu) {
              m.classList.remove('is-open');
              m.closest('.im-nav-dropdown').classList.remove('is-active');
            }
          });

          menu.classList.toggle('is-open');
          dd.classList.toggle('is-active');
        }
      });
    });

    // Global click-outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.im-nav-dropdown')) {
        document.querySelectorAll('.im-dropdown-menu.is-open').forEach(menu => {
          menu.classList.remove('is-open');
          menu.closest('.im-nav-dropdown').classList.remove('is-active');
        });
      }
    });
  }

  function initMobileNav() {
    const toggles = document.querySelectorAll('.im-mobile-toggle');
    toggles.forEach(btn => {
      btn.onclick = () => {
        const header = btn.closest('.im-header');
        const linksContainer = header.querySelector('.im-nav-links');
        const drawer = header.querySelector('.im-mobile-drawer');
        
        if (!linksContainer || !drawer) return;

        const isOpen = drawer.classList.contains('is-open');
        if (isOpen) {
          closeDrawer(drawer, btn);
        } else {
          openDrawer(drawer, btn, linksContainer);
        }
      };
    });
  }

  function openDrawer(drawer, btn, sourceLinks) {
    // Clone links non-destructively
    const clone = sourceLinks.cloneNode(true);
    clone.removeAttribute('id');
    clone.classList.add('im-mobile-nav-content');
    
    // Transform dropdowns to accordions in the clone
    clone.querySelectorAll('.im-nav-dropdown').forEach(dd => {
      const trigger = dd.querySelector('.im-dropdown-trigger');
      const menu = dd.querySelector('.im-dropdown-menu');
      if (!trigger || !menu) return;

      trigger.href = 'javascript:void(0)';
      trigger.innerHTML = `<span>${trigger.textContent.trim()}</span> ${window.icons.chevronDown}`;
      
      trigger.onclick = (e) => {
        e.preventDefault();
        const isCollapsed = menu.style.display === 'none' || !menu.style.display;
        menu.style.display = isCollapsed ? 'block' : 'none';
        trigger.classList.toggle('is-expanded', isCollapsed);
      };
    });

    drawer.innerHTML = '';
    drawer.appendChild(clone);
    drawer.style.display = 'block';
    
    requestAnimationFrame(() => {
      drawer.classList.add('is-open');
      btn.textContent = '✕';
    });

    // Handle outside clicks
    window.__imMobileClickHandler = (e) => {
      if (!drawer.contains(e.target) && !btn.contains(e.target)) {
        closeDrawer(drawer, btn);
      }
    };
    setTimeout(() => document.addEventListener('click', window.__imMobileClickHandler), 10);
  }

  function closeDrawer(drawer, btn) {
    drawer.classList.remove('is-open');
    btn.textContent = '☰';
    document.removeEventListener('click', window.__imMobileClickHandler);
    setTimeout(() => {
      if (!drawer.classList.contains('is-open')) drawer.style.display = 'none';
    }, 300);
  }

  function initLogout() {
    const logoutBtns = document.querySelectorAll('.im-logout-btn');
    logoutBtns.forEach(btn => {
      btn.onclick = (e) => {
        if (window.logout) {
          e.preventDefault();
          window.logout();
        }
      };
    });
  }

  // Auto-init on DOM load
  document.addEventListener('DOMContentLoaded', () => {
    const auto = document.querySelector('[data-header]');
    if (auto) {
      const type = auto.getAttribute('data-header');
      const role = auto.getAttribute('data-role');
      window.injectHeader(type, role);
    }
  });

})();
