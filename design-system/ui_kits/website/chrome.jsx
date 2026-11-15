/* To The North — Website UI kit: shared chrome (Nav, Hero, Footer, SectionHeader) */
const TTN = window.ToTheNorthDesignSystem_dc220a;
const { Button, Tag, Icon, NewsletterSignup, Divider } = TTN;

/* Reading progress bar — 2px gold line at the very top of the viewport.
   The one animation the brand earns. Rendered on article-type pages. */
function TTN_ProgressBar() {
  const [pct, setPct] = React.useState(0);
  React.useEffect(() => {
    const onScroll = () => {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      setPct(max > 0 ? Math.min(1, window.scrollY / max) : 0);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    onScroll();
    return () => { window.removeEventListener('scroll', onScroll); window.removeEventListener('resize', onScroll); };
  }, []);
  return (
    <div style={{ position: 'fixed', top: 0, left: 0, right: 0, height: '2px', zIndex: 60, background: 'transparent', pointerEvents: 'none' }}>
      <div style={{ width: `${pct * 100}%`, height: '100%', background: 'var(--ttn-gold)' }} />
    </div>
  );
}

/* Search overlay — full-screen TTN Black, Playfair input with gold bottom border,
   live results grouped by type. */
function SearchOverlay({ open, onClose, onOpenStory, onOpenDestination, onOpenPartner }) {
  const [q, setQ] = React.useState('');
  const inputRef = React.useRef(null);
  React.useEffect(() => {
    if (open) { setQ(''); setTimeout(() => inputRef.current && inputRef.current.focus(), 50); }
  }, [open]);
  React.useEffect(() => {
    if (!open) return;
    const onKey = (e) => { if (e.key === 'Escape') onClose(); };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [open, onClose]);
  if (!open) return null;

  const needle = q.trim().toLowerCase();
  const hit = (...fields) => needle && fields.some((f) => (f || '').toLowerCase().includes(needle));
  const stories = needle ? (window.TTN_STORIES || []).map((s, i) => ({ s, i })).filter(({ s }) => hit(s.title, s.tag, s.excerpt)) : [];
  const dests = needle ? (window.TTN_DESTINATIONS || []).map((d, i) => ({ d, i })).filter(({ d }) => hit(d.name, d.region, d.blurb)) : [];
  const partners = needle ? (window.TTN_PARTNERS || []).map((p, i) => ({ p, i })).filter(({ p }) => hit(p.name, p.category, p.location)) : [];
  const none = needle && stories.length === 0 && dests.length === 0 && partners.length === 0;

  const groupLabel = { fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)', margin: '32px 0 4px' };
  const row = { display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '16px', padding: '14px 0', borderBottom: '1px solid var(--ttn-slate)', textDecoration: 'none', cursor: 'pointer' };
  const rowTitle = { fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '19px', color: 'var(--ttn-white)' };
  const rowMeta = { fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)', flexShrink: 0 };
  const hoverRow = (e, on) => { e.currentTarget.style.borderBottomColor = on ? 'var(--ttn-border-lift, #333333)' : 'var(--ttn-slate)'; };
  const pick = (fn, i) => { onClose(); fn && fn(i); };

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 80, background: 'var(--ttn-black)', overflowY: 'auto' }}>
      <div style={{ maxWidth: '720px', margin: '0 auto', padding: '0 var(--gutter-desktop)' }}>
        <div style={{ display: 'flex', justifyContent: 'flex-end', padding: '24px 0 8px' }}>
          <button aria-label="Close search" onClick={onClose} style={{ background: 'none', border: 'none', color: 'var(--ttn-grey)', cursor: 'pointer', padding: '8px' }}>
            <Icon name="x" size={26} color="currentColor" />
          </button>
        </div>
        <input
          ref={inputRef}
          value={q}
          onChange={(e) => setQ(e.target.value)}
          placeholder="Where to?"
          style={{
            width: '100%', background: 'transparent', border: 'none', outline: 'none',
            borderBottom: '2px solid var(--ttn-gold)', padding: '8px 0 16px',
            fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(30px, 6vw, 44px)',
            letterSpacing: '-0.02em', color: 'var(--ttn-white)', caretColor: 'var(--ttn-gold)',
          }}
        />
        {!needle && <p style={{ margin: '20px 0 0', fontFamily: 'var(--font-body)', fontSize: '14px', color: 'var(--ttn-grey)' }}>Search stories, destinations and certified partners.</p>}

        {stories.length > 0 && (
          <div>
            <div style={groupLabel}>Stories</div>
            {stories.map(({ s, i }) => (
              <a key={i} style={row} onClick={() => pick(onOpenStory, i)} onMouseEnter={(e) => hoverRow(e, true)} onMouseLeave={(e) => hoverRow(e, false)}>
                <span style={rowTitle}>{s.title}</span><span style={rowMeta}>{s.tag} · {s.readTime}</span>
              </a>
            ))}
          </div>
        )}
        {dests.length > 0 && (
          <div>
            <div style={groupLabel}>Destinations</div>
            {dests.map(({ d, i }) => (
              <a key={i} style={row} onClick={() => pick(onOpenDestination, i)} onMouseEnter={(e) => hoverRow(e, true)} onMouseLeave={(e) => hoverRow(e, false)}>
                <span style={rowTitle}>{d.name}</span><span style={rowMeta}>{d.region}</span>
              </a>
            ))}
          </div>
        )}
        {partners.length > 0 && (
          <div>
            <div style={groupLabel}>Partners</div>
            {partners.map(({ p, i }) => (
              <a key={i} style={row} onClick={() => pick(onOpenPartner, i)} onMouseEnter={(e) => hoverRow(e, true)} onMouseLeave={(e) => hoverRow(e, false)}>
                <span style={rowTitle}>{p.name}</span><span style={rowMeta}>{p.category} · {p.location}</span>
              </a>
            ))}
          </div>
        )}
        {none && (
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '16px', padding: 'var(--space-8) 0', textAlign: 'center' }}>
            <Icon name="mountain" size={32} color="var(--ttn-grey)" />
            <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '16px', color: 'var(--ttn-grey-light, #B4B4AE)' }}>Nothing here yet. The road is still being built.</p>
          </div>
        )}
      </div>
    </div>
  );
}

/* Placeholder-copy marker — same discipline as the labelled image slots.
   Mark any block of dummy copy so nobody mistakes lorem for approved text. */
function TTN_Placeholder({ label = 'Placeholder copy' }) {
  return (
    <span style={{ display: 'inline-block', fontFamily: 'var(--font-body)', fontSize: '10px', fontWeight: 600, letterSpacing: '0.1em', textTransform: 'uppercase', color: 'var(--ttn-grey)', border: '1px dashed var(--ttn-border-lift, #333333)', borderRadius: 'var(--radius-btn)', padding: '3px 8px' }}>{label}</span>
  );
}

const NAV_LINKS = ['Home', 'Destinations', 'Stories', 'TTN Certified', 'Community', 'About'];

function Nav({ active = 'Home', onNavigate, onSearch }) {
  const [open, setOpen] = React.useState(false);
  const go = (l) => { setOpen(false); onNavigate && onNavigate(l); };
  return (
    <header
      style={{
        position: 'sticky', top: 0, zIndex: 50,
        background: 'var(--ttn-black)',
        borderBottom: '1px solid var(--ttn-slate)',
      }}
    >
      <div className="ttn-inner" style={{ height: '72px', display: 'flex', alignItems: 'center', gap: '24px' }}>
        <a href="#" onClick={(e) => { e.preventDefault(); go('Home'); }} style={{ display: 'flex', alignItems: 'center', flexShrink: 0 }}>
          <img src="../../assets/logo-mark.jpeg" alt="To The North" style={{ height: '40px', display: 'block', objectFit: 'fill', backgroundColor: '#0A0A0A' }} />
        </a>
        <nav className="ttn-nav-desktop" style={{ gap: '28px', margin: '0 auto' }}>
          {NAV_LINKS.map((l) => {
            const on = l === active;
            return (
              <a key={l} href="#" onClick={(e) => { e.preventDefault(); go(l); }}
                style={{
                  fontFamily: 'var(--font-body)', fontSize: '14px', fontWeight: 500, letterSpacing: 'var(--ls-ui)',
                  color: on ? 'var(--ttn-white)' : 'var(--ttn-grey)', textDecoration: 'none',
                  paddingBottom: '4px', borderBottom: `2px solid ${on ? 'var(--ttn-gold)' : 'transparent'}`,
                  transition: 'color .15s ease',
                }}
                onMouseEnter={(e) => { if (!on) e.currentTarget.style.color = 'var(--ttn-white)'; }}
                onMouseLeave={(e) => { if (!on) e.currentTarget.style.color = 'var(--ttn-grey)'; }}
              >{l}</a>
            );
          })}
        </nav>
        <div className="ttn-nav-desktop" style={{ flexShrink: 0, alignItems: 'center', gap: '18px' }}>
          <button aria-label="Search" onClick={onSearch}
            style={{ background: 'none', border: 'none', color: 'var(--ttn-grey)', cursor: 'pointer', padding: '6px', display: 'inline-flex', transition: 'color .15s ease' }}
            onMouseEnter={(e) => e.currentTarget.style.color = 'var(--ttn-white)'}
            onMouseLeave={(e) => e.currentTarget.style.color = 'var(--ttn-grey)'}>
            <Icon name="search" size={20} color="currentColor" />
          </button>
          <Button variant="secondary" size="sm">Subscribe</Button>
        </div>
        <button className="ttn-nav-burger" aria-label="Search" onClick={onSearch}
          style={{ marginLeft: 'auto', background: 'none', border: 'none', color: 'var(--ttn-white)', cursor: 'pointer', padding: '6px', alignItems: 'center' }}>
          <Icon name="search" size={22} color="currentColor" />
        </button>
        <button className="ttn-nav-burger" aria-label="Menu" onClick={() => setOpen((v) => !v)}
          style={{ background: 'none', border: 'none', color: 'var(--ttn-white)', cursor: 'pointer', padding: '6px', alignItems: 'center' }}>
          <Icon name={open ? 'x' : 'menu'} size={24} color="currentColor" />
        </button>
      </div>
      {open && (
        <nav className="ttn-nav-burger" style={{ flexDirection: 'column', borderTop: '1px solid var(--ttn-slate)', padding: '8px 0' }}>
          {NAV_LINKS.map((l) => {
            const on = l === active;
            return (
              <a key={l} href="#" onClick={(e) => { e.preventDefault(); go(l); }}
                style={{
                  fontFamily: 'var(--font-body)', fontSize: '15px', fontWeight: 500, letterSpacing: 'var(--ls-ui)',
                  color: on ? 'var(--ttn-gold)' : 'var(--ttn-white)', textDecoration: 'none',
                  padding: '14px var(--gutter-mobile)', borderLeft: `3px solid ${on ? 'var(--ttn-gold)' : 'transparent'}`,
                }}
              >{l}</a>
            );
          })}
        </nav>
      )}
    </header>
  );
}

function Hero({ image, eyebrow, title, sub, ctaLabel = 'Read the story', location, onCta }) {
  return (
    <section style={{ position: 'relative', minHeight: '88vh', display: 'flex', alignItems: 'flex-end', overflow: 'hidden' }}>
      <img src={image} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
      <div style={{ position: 'absolute', inset: 0, background: 'var(--hero-overlay)' }} />
      <div className="ttn-inner" style={{ position: 'relative', width: '100%', paddingBottom: 'var(--space-12)' }}>
        {eyebrow && (
          <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey-light, #B4B4AE)', marginBottom: '20px' }}>{eyebrow}</div>
        )}
        <h1 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 8vw, 76px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)', maxWidth: '16ch' }}>{title}</h1>
        {sub && <p style={{ margin: '20px 0 0', fontFamily: 'var(--font-body)', fontSize: 'clamp(15px, 2.4vw, 18px)', color: 'var(--ttn-grey)', maxWidth: '52ch' }}>{sub}</p>}
        <div style={{ marginTop: 'var(--space-4)' }}>
          <Button variant="primary" size="lg" iconRight={<Icon name="arrowRight" size={18} />} onClick={onCta}>{ctaLabel}</Button>
        </div>
        {location && (
          <div style={{ display: 'flex', alignItems: 'center', gap: '6px', marginTop: 'var(--space-6)', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
            <Icon name="mapPin" size={14} color="var(--ttn-grey)" />{location}
          </div>
        )}
      </div>
    </section>
  );
}

function SectionHeader({ title, action, onAction }) {
  return (
    <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 'var(--space-6)', gap: '24px' }}>
      <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(28px, 5vw, 40px)', lineHeight: 1.15, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{title}</h2>
      {action && <Button variant="text" iconRight={<Icon name="arrowRight" size={16} />} onClick={onAction}>{action}</Button>}
    </div>
  );
}

function Section({ alt, children, style = {} }) {
  return (
    <section className="ttn-section" style={{ background: alt ? 'var(--ttn-deep)' : 'var(--ttn-black)', padding: 'var(--space-12) 0', ...style }}>
      <div className="ttn-inner">{children}</div>
    </section>
  );
}

function Footer({ onNavigate }) {
  const linkStyle = { fontFamily: 'var(--font-body)', fontSize: '14px', color: 'var(--ttn-grey)', textDecoration: 'none', letterSpacing: 'var(--ls-ui)' };
  const colHead = { fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-white)', margin: '0 0 18px' };
  const FooterLink = ({ label, page }) => (
    <a href="#" onClick={(e) => { e.preventDefault(); onNavigate && onNavigate(page || label); }} style={linkStyle}
      onMouseEnter={(e) => e.currentTarget.style.color = 'var(--ttn-white)'}
      onMouseLeave={(e) => e.currentTarget.style.color = 'var(--ttn-grey)'}>{label}</a>
  );
  const social = ['facebook', 'instagram', 'tiktok', 'youtube'];
  return (
    <footer style={{ background: 'var(--ttn-deep)', borderTop: '1px solid var(--ttn-gold)' }}>
      <div className="ttn-inner" style={{ paddingTop: 'var(--space-8)', paddingBottom: 'var(--space-4)' }}>
        <div className="ttn-footer-cols">
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', gap: '18px' }}>
            <img src="../../assets/logo-mark.jpeg" alt="To The North" style={{ height: '40px', objectFit: 'fill', backgroundColor: '#0A0A0A' }} />
            <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '14px', lineHeight: 1.7, color: 'var(--ttn-grey-light, #B4B4AE)', maxWidth: '30ch' }}>Promote tourism and mountaineering in the Northern Areas of Pakistan.</p>
            <div style={{ display: 'flex', gap: '14px' }}>
              {social.map((s) => (
                <a key={s} href="#" aria-label={s} style={{ display: 'inline-flex', color: 'var(--ttn-grey)' }}
                  onMouseEnter={(e) => e.currentTarget.style.color = 'var(--ttn-white)'}
                  onMouseLeave={(e) => e.currentTarget.style.color = 'var(--ttn-grey)'}>
                  <Icon name={s} size={18} color="currentColor" />
                </a>
              ))}
            </div>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
            <h4 style={colHead}>Explore</h4>
            <FooterLink label="Destinations" />
            <FooterLink label="Stories" />
            <FooterLink label="TTN Certified" />
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
            <h4 style={colHead}>Community</h4>
            <FooterLink label="Facebook group" page="Community" />
            <FooterLink label="About" />
            <FooterLink label="Contact" page="Contact" />
          </div>
          <div>
            <h4 style={colHead}>The TTN Newsletter</h4>
            <NewsletterSignup variant="footer" />
          </div>
        </div>
        <Divider style={{ margin: 'var(--space-6) 0 0' }} />
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '12px 24px', padding: 'var(--space-3) 0 6px', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
          <span>© To The North · Lahore, Pakistan</span>
          <span>466,000 travellers and counting</span>
        </div>
        <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>All photography by Waseem Photography.</p>
      </div>
    </footer>
  );
}

Object.assign(window, { TTN_Nav: Nav, TTN_Hero: Hero, TTN_SectionHeader: SectionHeader, TTN_Section: Section, TTN_Footer: Footer, TTN_ProgressBar, TTN_SearchOverlay: SearchOverlay, TTN_Placeholder, TTN_NAV_LINKS: NAV_LINKS });
