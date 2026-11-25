(() => {
  'use strict';

  const $ = (selector, scope = document) => scope.querySelector(selector);
  const $$ = (selector, scope = document) => [...scope.querySelectorAll(selector)];
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(pointer: fine)').matches;

  // Scroll Progress & Floating Header
  const header = $('[data-header]');
  const progressBar = $('[data-scroll-progress]');

  const updateScroll = () => {
    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
    header?.classList.toggle('scrolled', window.scrollY > 18);
    if (progressBar) {
      const pct = scrollable > 0 ? Math.min(100, (window.scrollY / scrollable) * 100) : 0;
      progressBar.style.width = `${pct}%`;
    }
  };

  window.addEventListener('scroll', updateScroll, { passive: true });
  updateScroll();

  // Mobile Drawer
  const menuBtn = $('[data-menu-toggle]');
  const mobileMenu = $('[data-mobile-menu]');

  const closeMenu = () => {
    if (!menuBtn || !mobileMenu) return;
    menuBtn.setAttribute('aria-expanded', 'false');
    mobileMenu.classList.remove('open');
    mobileMenu.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
  };

  menuBtn?.addEventListener('click', () => {
    const isOpen = menuBtn.getAttribute('aria-expanded') === 'true';
    menuBtn.setAttribute('aria-expanded', String(!isOpen));
    mobileMenu?.classList.toggle('open', !isOpen);
    mobileMenu?.setAttribute('aria-hidden', String(isOpen));
    document.body.classList.toggle('menu-open', !isOpen);
  });

  mobileMenu?.addEventListener('click', (e) => {
    if (e.target === mobileMenu || e.target.closest('[data-mobile-link]')) {
      closeMenu();
    }
  });

  // Admin Mobile Sidebar Toggle
  const adminToggle = $('[data-admin-toggle]');
  const adminSidebar = $('[data-admin-sidebar]');
  adminToggle?.addEventListener('click', () => {
    adminSidebar?.classList.toggle('open');
  });

  // Button Ripple Effect
  $$('[data-ripple]').forEach((btn) => {
    btn.addEventListener('pointerdown', (e) => {
      if (reducedMotion) return;
      const rect = btn.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className = 'button-ripple';
      ripple.style.left = `${e.clientX - rect.left}px`;
      ripple.style.top = `${e.clientY - rect.top}px`;
      btn.append(ripple);
      setTimeout(() => ripple.remove(), 700);
    });
  });

  // Reveal Observer
  const revealNodes = $$('[data-reveal]');
  if (!reducedMotion && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1, rootMargin: '0px 0px -6% 0px' }
    );
    revealNodes.forEach((el) => io.observe(el));
  } else {
    revealNodes.forEach((el) => el.classList.add('is-visible'));
  }

  // Subtle 3D Tilt on Desktop
  if (!reducedMotion && finePointer) {
    $$('[data-tilt]').forEach((el) => {
      el.addEventListener('pointermove', (e) => {
        const r = el.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        const shell = $('.browser-mockup', el) || el;
        shell.style.transform = `perspective(1600px) rotateX(${(-y * 3).toFixed(2)}deg) rotateY(${(x * 4.5).toFixed(2)}deg)`;
      });
      el.addEventListener('pointerleave', () => {
        const shell = $('.browser-mockup', el) || el;
        shell.style.transform = '';
      });
    });
  }

  // Cursor Spotlight on Bento Cards
  if (!reducedMotion && finePointer) {
    $$('[data-spotlight]').forEach((card) => {
      card.addEventListener('pointermove', (e) => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${e.clientX - r.left}px`);
        card.style.setProperty('--my', `${e.clientY - r.top}px`);
      });
    });
  }

  // Interactive Hero Browser Preview
  const browserDemo = $('[data-browser-demo]');
  if (browserDemo) {
    const urlEl = $('[data-browser-url]', browserDemo);
    const tabs = $$('[data-demo-tab]', browserDemo);
    const panels = $$('[data-demo-panel]', browserDemo);

    tabs.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const target = trigger.dataset.demoTab;
        const nextUrl = trigger.dataset.demoUrl;
        tabs.forEach((item) => {
          const match = item.dataset.demoTab === target;
          item.classList.toggle('active', match);
          if (item.hasAttribute('aria-selected')) {
            item.setAttribute('aria-selected', String(match));
          }
        });
        panels.forEach((panel) => {
          panel.classList.toggle('active', panel.dataset.demoPanel === target);
        });
        if (urlEl && nextUrl) {
          urlEl.textContent = nextUrl;
        }
      });
    });

    const sidebarBtn = $('[data-toggle-sidebar]', browserDemo);
    sidebarBtn?.addEventListener('click', () => {
      const collapsed = browserDemo.classList.toggle('sidebar-collapsed');
      sidebarBtn.classList.toggle('active', collapsed);
      sidebarBtn.setAttribute('aria-pressed', String(collapsed));
    });

    const clarityBtn = $('[data-toggle-clarity]', browserDemo);
    clarityBtn?.addEventListener('click', () => {
      const clarity = browserDemo.classList.toggle('clarity-on');
      clarityBtn.classList.toggle('active', clarity);
      clarityBtn.setAttribute('aria-pressed', String(clarity));
    });
  }

  // Interactive Anatomy Inspector
  const anatomyPreview = $('[data-anatomy-preview]');
  const anatomySteps = $$('[data-anatomy]');
  if (anatomyPreview && anatomySteps.length) {
    const outTitle = $('[data-anatomy-output-title]');
    const outDesc = $('[data-anatomy-output-desc]');
    const outLa = $('[data-anatomy-output-la]');
    const outVa = $('[data-anatomy-output-va]');
    const outLb = $('[data-anatomy-output-lb]');
    const outVb = $('[data-anatomy-output-vb]');

    anatomySteps.forEach((step) => {
      step.addEventListener('click', () => {
        anatomySteps.forEach((s) => s.classList.remove('active'));
        step.classList.add('active');
        anatomyPreview.dataset.focus = step.dataset.anatomy || 'navigation';
        if (outTitle) outTitle.textContent = step.dataset.anatomyTitle || '';
        if (outDesc) outDesc.textContent = step.dataset.anatomyDesc || '';
        if (outLa) outLa.textContent = step.dataset.anatomyA || '';
        if (outVa) outVa.textContent = step.dataset.anatomyAv || '';
        if (outLb) outLb.textContent = step.dataset.anatomyB || '';
        if (outVb) outVb.textContent = step.dataset.anatomyBv || '';
      });
    });
  }

  // Changelog Category Filters
  const filterButtons = $$('[data-filter-cat]');
  const timelineItems = $$('.timeline-item[data-category]');
  if (filterButtons.length && timelineItems.length) {
    filterButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const cat = btn.dataset.filterCat;
        filterButtons.forEach((b) => b.classList.toggle('active', b === btn));
        timelineItems.forEach((item) => {
          const show = cat === 'all' || item.dataset.category === cat;
          item.style.display = show ? '' : 'none';
        });
      });
    });
  }

  // Admin Table Search
  $$('[data-search]').forEach((input) => {
    const table = document.getElementById(input.dataset.search || '');
    if (!table) return;
    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      $$('tbody tr', table).forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
      });
    });
  });

  // Toast System
  const dismissToast = (node) => {
    node.classList.add('leave');
    setTimeout(() => node.remove(), 300);
  };

  const showToast = (message, type = 'success') => {
    const toastHost = $('[data-toast]');
    if (!toastHost) return;
    const node = document.createElement('div');
    node.className = `toast ${type}`;

    const icon = document.createElement('div');
    icon.className = 'toast-icon';
    icon.textContent = type === 'error' ? '×' : '✓';

    const body = document.createElement('div');
    const title = document.createElement('strong');
    title.textContent = type === 'error' ? 'Something went wrong' : 'Success';
    const text = document.createElement('span');
    text.textContent = message;
    body.append(title, text);

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.setAttribute('aria-label', 'Dismiss');
    closeBtn.textContent = '×';
    closeBtn.onclick = () => dismissToast(node);

    node.append(icon, body, closeBtn);
    toastHost.append(node);
    setTimeout(() => dismissToast(node), 4800);
  };

  window.showToast = showToast;

  // Clipboard Copy (Checksum)
  $$('[data-copy]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const value = btn.dataset.copy || '';
      const original = btn.textContent;
      try {
        await navigator.clipboard.writeText(value);
        btn.textContent = 'Copied';
        showToast('SHA-256 checksum copied to clipboard.');
        setTimeout(() => { btn.textContent = original; }, 1800);
      } catch {
        showToast('Copy failed. Please select the checksum manually.', 'error');
      }
    });
  });

  // Modal Support (Admin)
  const modal = $('[data-modal]');
  const confirmForm = $('#confirm-form');

  window.openModal = () => {
    modal?.classList.add('open');
    modal?.setAttribute('aria-hidden', 'false');
    document.body.classList.add('menu-open');
  };

  window.closeModal = () => {
    modal?.classList.remove('open');
    modal?.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
  };

  modal?.addEventListener('click', (e) => {
    if (e.target === modal) window.closeModal();
  });

  $('[data-close-modal]')?.addEventListener('click', window.closeModal);

  $$('[data-confirm]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.confirm || '';
      if (confirmForm) {
        try {
          const url = new URL(target, window.location.href);
          const id = url.searchParams.get('id') || '';
          const idInput = confirmForm.querySelector('[name="id"]');
          if (idInput) idInput.value = id;
          confirmForm.action = url.pathname;
        } catch {
          confirmForm.action = target;
        }
      }
      window.openModal();
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMenu();
      window.closeModal();
    }
  });
})();
