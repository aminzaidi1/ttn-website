// To The North — site behaviour. Small on purpose: no framework, no dependencies.
const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* Mobile menu */
const nav = $('[data-nav]');
const toggle = $('[data-nav-toggle]');
if (nav && toggle) {
  const setOpen = (open) => { nav.classList.toggle('is-open', open); toggle.setAttribute('aria-expanded', open); };
  toggle.addEventListener('click', () => setOpen(!nav.classList.contains('is-open')));
  $$('.ttn-nav__mobile a').forEach((a) => a.addEventListener('click', () => setOpen(false)));
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') setOpen(false); });
}

/* Search overlay: live results, grouped, Esc to close */
const search = $('[data-search]');
if (search) {
  const input = $('[data-search-input]', search);
  const results = $('[data-search-results]', search);
  const hint = $('[data-search-hint]', search);
  let lastFocus = null, timer = null, seq = 0;
  const open = () => { lastFocus = document.activeElement; search.hidden = false; search.classList.add('is-open'); document.body.classList.add('is-locked'); input.value = ''; results.innerHTML = ''; hint.hidden = false; setTimeout(() => input.focus(), 30); };
  const close = () => { search.classList.remove('is-open'); search.hidden = true; document.body.classList.remove('is-locked'); lastFocus && lastFocus.focus(); };
  $$('[data-search-open]').forEach((b) => b.addEventListener('click', (e) => { e.preventDefault(); open(); }));
  $('[data-search-close]', search).addEventListener('click', close);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && search.classList.contains('is-open')) close();
    if (e.key === '/' && !search.classList.contains('is-open') && !/INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName)) { e.preventDefault(); open(); }
  });
  input.addEventListener('input', () => {
    clearTimeout(timer);
    const q = input.value.trim();
    hint.hidden = q !== '';
    if (!q) { results.innerHTML = ''; return; }
    timer = setTimeout(async () => {
      const mine = ++seq;
      try {
        const res = await fetch('/search/results?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const html = await res.text();
        if (mine === seq) results.innerHTML = html;
      } catch (_) { /* the form still submits to /search */ }
    }, 160);
  });
}

/* Newsletter: submit in place */
$$('[data-newsletter]').forEach((box) => {
  const form = $('form', box), ok = $('[data-newsletter-success]', box), err = $('[data-newsletter-error]', box);
  if (!form) return;
  const input = $('input[type=email]', form);
  input.addEventListener('input', () => { form.classList.remove('is-error'); err.hidden = true; });
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fail = (msg) => { form.classList.add('is-error'); err.textContent = msg || 'Please enter a valid email address.'; err.hidden = false; };
    if (!/.+@.+\..+/.test(input.value.trim())) return fail();
    const btn = $('button', form); btn.disabled = true;
    try {
      const res = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json' }, body: new FormData(form) });
      if (res.ok) { form.hidden = true; ok.hidden = false; }
      else if (res.status === 429) fail('Too many tries. Give it a minute.');
      else fail();
    } catch (_) { fail('Could not reach us. Try again in a moment.'); }
    btn.disabled = false;
  });
});

/* Reading progress line */
const bar = $('[data-progress]');
if (bar) {
  let ticking = false;
  const update = () => { const max = document.documentElement.scrollHeight - innerHeight; bar.style.transform = `scaleX(${max > 0 ? Math.min(1, scrollY / max) : 0})`; ticking = false; };
  const on = () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } };
  addEventListener('scroll', on, { passive: true }); addEventListener('resize', on); update();
}

/* Scroll reveal: fade up once, staggered within a group */
const revealables = $$('[data-reveal]');
if (reduced || !('IntersectionObserver' in window)) {
  revealables.forEach((el) => el.classList.add('is-in'));
} else {
  $$('[data-reveal-group]').forEach((group) => {
    $$(':scope > [data-reveal]', group).forEach((el, i) => el.style.setProperty('--reveal-delay', Math.min(i, 5) * 70 + 'ms'));
  });
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-in'); io.unobserve(entry.target); } });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
  revealables.forEach((el) => io.observe(el));
}

/* Count up once: <span data-count="466000">466,000</span> */
const counters = $$('[data-count]');
if (counters.length && !reduced && 'IntersectionObserver' in window) {
  const fmt = new Intl.NumberFormat('en-US');
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      io.unobserve(entry.target);
      const el = entry.target, end = parseFloat(el.dataset.count), suffix = el.dataset.suffix || '', t0 = performance.now(), dur = 1100;
      const step = (t) => { const p = Math.min(1, (t - t0) / dur), eased = 1 - Math.pow(1 - p, 3); el.textContent = fmt.format(Math.round(end * eased)) + suffix; if (p < 1) requestAnimationFrame(step); };
      requestAnimationFrame(step);
    });
  }, { threshold: 0.4 });
  counters.forEach((el) => io.observe(el));
}

/* Scroll spy for in-page section navs: <nav data-spy><a href="#id">…</a></nav> */
$$('[data-spy]').forEach((spy) => {
  const links = $$('a[href^="#"]', spy);
  const targets = links.map((a) => document.getElementById(a.getAttribute('href').slice(1))).filter(Boolean);
  if (!targets.length || !('IntersectionObserver' in window)) return;
  const setActive = (id) => links.forEach((a) => {
    const on = a.getAttribute('href') === '#' + id;
    a.classList.toggle('is-active', on);
    if (on && spy.scrollWidth > spy.clientWidth) spy.scrollTo({ left: a.offsetLeft - spy.clientWidth / 2 + a.clientWidth / 2, behavior: reduced ? 'auto' : 'smooth' });
  });
  const io = new IntersectionObserver((entries) => { entries.forEach((e) => { if (e.isIntersecting) setActive(e.target.id); }); }, { rootMargin: '-30% 0px -60% 0px' });
  targets.forEach((t) => io.observe(t));
});
