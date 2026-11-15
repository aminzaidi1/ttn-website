/* To The North — Website UI kit: screens */
const _TTN = window.ToTheNorthDesignSystem_dc220a;
const { Button, Tag, Icon, StoryCard, DestinationCard, PartnerCard, CommunityQuote, NewsletterSignup, Divider } = _TTN;
/* Fallback keeps the site alive if a stale _ds_bundle.js predates EmptyState */
const EmptyState = _TTN.EmptyState || function EmptyStateFallback({ message = 'Nothing here yet. The road is still being built.', actionLabel = 'Clear filter', onAction }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '24px', padding: 'var(--space-8) var(--space-3)', textAlign: 'center' }}>
      <Icon name="mountain" size={32} color="var(--ttn-grey)" />
      <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.6, color: 'var(--ttn-grey-light, #B4B4AE)', maxWidth: '40ch' }}>{message}</p>
      {onAction && <Button variant="secondary" size="sm" onClick={onAction}>{actionLabel}</Button>}
    </div>
  );
};
const { TTN_Hero, TTN_SectionHeader, TTN_Section } = window;

function HomeScreen({ onNavigate, onOpenStory, onOpenDestination, onOpenPartner }) {
  const stories = window.TTN_STORIES, dests = window.TTN_DESTINATIONS, community = window.TTN_COMMUNITY, partners = window.TTN_PARTNERS, PH = window.TTN_PH;
  return (
    <div>
      <TTN_Hero
        image={PH.hero}
        eyebrow="Northern Pakistan"
        title="The mountains are waiting. So is the road."
        sub="Seven years of stories, destinations and photography from the people who actually drove the KKH."
        location="Attabad Lake, Gilgit-Baltistan"
        onCta={() => onOpenStory(0)}
      />

      <TTN_Section>
        <TTN_SectionHeader title="Latest stories" action="View all stories" onAction={() => onNavigate('Stories')} />
        <div className="ttn-g3">
          {stories.map((s, i) => <StoryCard key={i} {...s} href="#" onClick={(e) => { e.preventDefault(); onOpenStory(i); }} />)}
        </div>
      </TTN_Section>

      <TTN_Section alt>
        <TTN_SectionHeader title="Explore destinations" action="All destinations" onAction={() => onNavigate('Destinations')} />
        <div className="ttn-g3">
          {dests.map((d, i) => <DestinationCard key={i} {...d} height={360} onClick={(e) => { e.preventDefault(); onOpenDestination && onOpenDestination(i); }} />)}
        </div>
      </TTN_Section>

      <TTN_Section>
        <TTN_SectionHeader title="Featured from the community" />
        <div className="ttn-g2">
          {community.slice(0, 2).map((c, i) => <CommunityQuote key={i} quote={c.quote} name={c.name} />)}
        </div>
      </TTN_Section>

      <NewsletterSignup variant="embedded" />

      <TTN_Section>
        <TTN_SectionHeader title="TTN Certified partners" action="See all partners" onAction={() => onNavigate('TTN Certified')} />
        <div className="ttn-g3">
          {partners.map((p, i) => <PartnerCard key={i} {...p} ctaLabel="View partner" onCta={(e) => { e.preventDefault(); onOpenPartner && onOpenPartner(i); }} />)}
        </div>
      </TTN_Section>

      <TTN_Section alt>
        <div style={{ textAlign: 'center', maxWidth: '40ch', margin: '0 auto' }}>
          <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(34px, 7vw, 52px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>466,000 travellers and counting</h2>
          <p style={{ margin: '18px 0 28px', fontFamily: 'var(--font-body)', fontSize: '18px', color: 'var(--ttn-grey)' }}>Join the largest community of northern Pakistan travellers, built over seven years on Facebook.</p>
          <Button variant="primary" size="lg" icon={<Icon name="facebook" size={18} />}>Join the community</Button>
        </div>
      </TTN_Section>
    </div>
  );
}

function FilterPills({ items, active, onChange }) {
  return (
    <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', marginBottom: 'var(--space-6)' }}>
      {items.map((r) => {
        const on = r === active;
        return (
          <button key={r} onClick={() => onChange(r)}
            onMouseEnter={(e) => { if (!on) { e.currentTarget.style.borderColor = 'var(--ttn-border-lift, #333333)'; e.currentTarget.style.color = 'var(--ttn-white)'; } }}
            onMouseLeave={(e) => { if (!on) { e.currentTarget.style.borderColor = 'var(--ttn-slate)'; e.currentTarget.style.color = 'var(--ttn-grey)'; } }}
            style={{
            fontFamily: 'var(--font-body)', fontSize: '13px', fontWeight: 500, letterSpacing: '0.02em',
            padding: '7px 15px', borderRadius: 'var(--radius-pill)', cursor: 'pointer',
            border: `1px solid ${on ? 'var(--ttn-gold)' : 'var(--ttn-slate)'}`,
            background: on ? 'var(--ttn-gold)' : 'transparent',
            color: on ? 'var(--ttn-black)' : 'var(--ttn-grey)',
          }}>{r}</button>
        );
      })}
    </div>
  );
}

const idxEyebrow = { fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' };

function GoldLink({ children, onClick }) {
  const [hover, setHover] = React.useState(false);
  return (
    <a href="#" onClick={(e) => { e.preventDefault(); onClick && onClick(); }}
      onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
      style={{ display: 'inline-flex', alignItems: 'center', gap: '8px', fontFamily: 'var(--font-body)', fontSize: '14px', fontWeight: 600, letterSpacing: '0.02em', color: hover ? 'var(--ttn-amber)' : 'var(--ttn-gold)', textDecoration: 'none' }}>
      {children}<Icon name="arrowRight" size={15} color="currentColor" />
    </a>
  );
}

function StoriesScreen({ onOpenStory }) {
  const stories = window.TTN_STORIES, regions = window.TTN_REGIONS;
  const [active, setActive] = React.useState('All');
  const filtered = active === 'All' ? stories : stories.filter((s) => s.region === active);
  const featured = filtered[0];
  const rest = featured ? [...filtered.slice(1), ...filtered] : [];
  return (
    <TTN_Section>
      <div style={{ marginBottom: 'var(--space-6)' }}>
        <h1 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 9vw, 64px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>Stories</h1>
        <p style={{ margin: '14px 0 0', fontFamily: 'var(--font-body)', fontSize: '18px', color: 'var(--ttn-grey-light, #B4B4AE)' }}>Dispatches from the road, the trail and the camp.</p>
      </div>
      <FilterPills items={regions} active={active} onChange={setActive} />

      {!featured && <EmptyState onAction={() => setActive('All')} />}

      {featured && <React.Fragment>
      {/* Featured story */}
      <div className="ttn-featured" style={{ marginBottom: 'var(--space-8)' }}>
        <div style={{ position: 'relative', minHeight: '320px' }}>
          <img src={featured.hero || featured.image} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
        </div>
        <div style={{ padding: 'var(--space-5)', display: 'flex', flexDirection: 'column', alignItems: 'flex-start', gap: '16px' }}>
          <Tag variant="gold">{featured.tag}</Tag>
          <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '34px', lineHeight: 1.15, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{featured.title}</h2>
          <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.7, color: 'var(--ttn-grey-light, #B4B4AE)' }}>{featured.excerpt}</p>
          <div style={{ display: 'flex', alignItems: 'center', gap: '10px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
            <span>{featured.author}</span><span>·</span><span>{featured.date}</span><span>·</span>
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px' }}><Icon name="clock" size={13} color="var(--ttn-grey)" />{featured.readTime}</span>
          </div>
          <div style={{ marginTop: 'auto' }}>
            <GoldLink onClick={() => onOpenStory(0)}>Read story</GoldLink>
          </div>
        </div>
      </div>

      <Divider label="Latest" style={{ marginBottom: 'var(--space-6)' }} />
      <div className="ttn-g3">
        {rest.map((s, i) => <StoryCard key={i} {...s} href="#" onClick={(e) => { e.preventDefault(); onOpenStory(stories.indexOf(s) === -1 ? 0 : stories.indexOf(s)); }} />)}
      </div>
      </React.Fragment>}
    </TTN_Section>
  );
}

function PostBody({ blocks }) {
  const para = { fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-white)', margin: '0 0 24px' };
  return (
    <React.Fragment>
      {blocks.map((b, i) => {
        if (b.t === 'p') return <p key={i} style={para}>{b.text}</p>;
        if (b.t === 'h2') return <h2 key={i} style={{ margin: '40px 0 20px', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '30px', lineHeight: 1.2, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{b.text}</h2>;
        if (b.t === 'h3') return <h3 key={i} style={{ margin: '32px 0 14px', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '22px', lineHeight: 1.25, color: 'var(--ttn-white)' }}>{b.text}</h3>;
        if (b.t === 'quote') return (
          <blockquote key={i} style={{ borderLeft: '3px solid var(--ttn-gold)', paddingLeft: '24px', margin: '32px 0' }}>
            <p style={{ margin: 0, fontFamily: 'var(--font-display)', fontStyle: 'italic', fontWeight: 400, fontSize: '26px', lineHeight: 1.4, color: 'var(--ttn-white)' }}>{b.text}</p>
          </blockquote>
        );
        if (b.t === 'image') return (
          <figure key={i} style={{ margin: '32px 0' }}>
            <div style={{ aspectRatio: '3 / 2', borderRadius: 'var(--radius-card)', overflow: 'hidden', background: 'var(--ttn-slate)' }}>
              <img src={b.src} alt={b.caption} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
            </div>
            <figcaption style={{ margin: '10px 0 0', display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
              <Icon name="mapPin" size={12} color="var(--ttn-grey)" />{b.caption}
            </figcaption>
          </figure>
        );
        if (b.t === 'list') return (
          <ul key={i} style={{ margin: '0 0 24px', padding: 0, listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '10px' }}>
            {b.items.map((it, j) => (
              <li key={j} style={{ display: 'flex', gap: '14px', fontFamily: 'var(--font-body)', fontSize: '17px', lineHeight: 1.6, color: 'var(--ttn-white)' }}>
                <span aria-hidden="true" style={{ width: '5px', height: '5px', background: 'var(--ttn-grey)', flexShrink: 0, marginTop: '11px' }}></span>{it}
              </li>
            ))}
          </ul>
        );
        return null;
      })}
    </React.Fragment>
  );
}

function PostScreen({ index = 0, onOpenStory }) {
  const stories = window.TTN_STORIES;
  const details = window.TTN_STORY_DETAILS || [];
  const s = stories[index] || stories[0];
  const det = details[index] || details[0] || { lead: s.excerpt, body: [], after: [] };
  const related = stories.filter((_, i) => i !== index).slice(0, 3);
  const col = { maxWidth: 'var(--w-article)', margin: '0 auto', padding: '0 var(--gutter-desktop)' };
  return (
    <div data-screen-label={`Story — ${s.title}`}>
      {/* Hero */}
      <section style={{ position: 'relative', height: '65vh', display: 'flex', alignItems: 'flex-end', overflow: 'hidden' }}>
        <img src={s.hero || s.image} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'var(--hero-overlay)' }} />
        <div style={{ position: 'relative', maxWidth: 'var(--w-article)', margin: '0 auto', width: '100%', padding: '0 var(--gutter-desktop) var(--space-8)' }}>
          <Tag variant="gold">{s.tag}</Tag>
          <h1 style={{ margin: '14px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(34px, 7vw, 52px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{s.title}</h1>
        </div>
      </section>

      {/* Meta bar */}
      <div style={{ borderBottom: '1px solid var(--ttn-slate)' }}>
        <div style={{ ...col, display: 'flex', alignItems: 'center', gap: '10px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)', paddingTop: '18px', paddingBottom: '18px' }}>
          <span>{s.author}</span><span>·</span><span>{s.date}</span><span>·</span>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px' }}><Icon name="clock" size={13} color="var(--ttn-grey)" />{s.readTime}</span>
          {s.location && <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px' }}><span>·</span><Icon name="mapPin" size={13} color="var(--ttn-grey)" />{s.location}</span>}
          <span style={{ marginLeft: 'auto', fontSize: '11px' }}>Photo: Waseem Photography</span>
        </div>
      </div>

      {/* Lead + body */}
      <div style={{ ...col, paddingTop: 'var(--space-8)', paddingBottom: 'var(--space-4)' }}>
        <div style={{ marginBottom: '20px' }}><window.TTN_Placeholder /></div>
        <p style={{ margin: '0 0 32px', fontFamily: 'var(--font-body)', fontSize: '20px', lineHeight: 1.7, color: 'var(--ttn-white)' }}>{det.lead}</p>
        <PostBody blocks={det.body} />
      </div>

      {/* Full-bleed photo essay break */}
      {det.essay && (
        <figure style={{ margin: 'var(--space-6) 0' }}>
          <div style={{ height: 'min(78vh, 720px)', overflow: 'hidden' }}>
            <img src={det.essay.src} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
          </div>
          <figcaption style={{ maxWidth: 'var(--w-article)', margin: '14px auto 0', padding: '0 var(--gutter-desktop)', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>{det.essay.caption}</figcaption>
        </figure>
      )}

      {/* Inline newsletter */}
      <div style={{ ...col, paddingTop: 'var(--space-4)' }}>
        <NewsletterSignup variant="inline" />
      </div>

      {/* Closing body */}
      {det.after && det.after.length > 0 && (
        <div style={{ ...col, paddingTop: 'var(--space-6)' }}>
          <PostBody blocks={det.after} />
        </div>
      )}

      {/* Author card — the close */}
      <div style={{ ...col, paddingTop: 'var(--space-6)', paddingBottom: 'var(--space-12, 96px)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 'var(--space-3)', background: 'var(--ttn-deep)', border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', padding: 'var(--space-3)' }}>
          <div style={{ width: '72px', height: '72px', flexShrink: 0, borderRadius: '50%', overflow: 'hidden', background: 'var(--ttn-slate)' }}>
            <img src={window.TTN_PH.team} alt={s.author} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
          </div>
          <div style={{ flex: 1, minWidth: 0 }}>
            <div style={{ fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '20px', color: 'var(--ttn-white)' }}>{s.author}</div>
            <p style={{ margin: '4px 0 0', fontFamily: 'var(--font-body)', fontSize: '14px', lineHeight: 1.6, color: 'var(--ttn-grey-light, #B4B4AE)' }}>{det.authorBio}</p>
            <a href="#" onClick={(e) => { e.preventDefault(); onOpenStory(index === 0 ? 1 : 0); }} style={{ display: 'inline-block', marginTop: '8px', fontFamily: 'var(--font-body)', fontSize: '13px', fontWeight: 600, color: 'var(--ttn-gold)', textDecoration: 'none' }}>More stories by {s.author}</a>
          </div>
        </div>
      </div>
    </div>
  );
}

function DestinationsScreen({ onOpenDestination }) {
  const dests = window.TTN_DESTINATIONS, regions = window.TTN_REGIONS, details = window.TTN_DEST_DETAILS;
  const [active, setActive] = React.useState('All');
  const shown = active === 'All' ? dests : dests.filter((d) => d.region === active);
  return (
    <TTN_Section>
      <div style={{ maxWidth: '60ch', marginBottom: 'var(--space-6)' }}>
        <h1 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 9vw, 64px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>Destinations</h1>
        <p style={{ margin: '14px 0 0', fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-grey)' }}>The valleys, lakes and passes worth the drive. Filter by region, then start planning.</p>
      </div>
      <FilterPills items={regions} active={active} onChange={setActive} />
      <div style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-8)' }}>
        {shown.map((d, i) => {
          const orig = dests.indexOf(d);
          const det = details[orig] || {};
          const stats = det.stats || {};
          const mini = [
            { icon: 'calendar', label: 'Best season', value: stats.season },
            { icon: 'mountain', label: 'Altitude', value: stats.altitude },
            { icon: 'car', label: 'Drive', value: stats.drive },
          ].filter((m) => m.value);
          return (
            <React.Fragment key={orig}>
              {i > 0 && <Divider label={d.region} />}
              <div className={`ttn-destrow${i % 2 === 1 ? ' flip' : ''}`}>
                <a href="#" onClick={(e) => { e.preventDefault(); onOpenDestination && onOpenDestination(orig); }}
                  style={{ display: 'block', borderRadius: 'var(--radius-card)', overflow: 'hidden', aspectRatio: '3 / 2', background: 'var(--ttn-slate)' }}>
                  <img src={d.image} alt={d.name} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                </a>
                <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start', gap: '16px' }}>
                  <Tag variant="category">{d.region}</Tag>
                  <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '40px', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{d.name}</h2>
                  <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.7, color: 'var(--ttn-grey)', maxWidth: '48ch' }}>{d.blurb}</p>
                  <div style={{ display: 'flex', gap: 'var(--space-4)', flexWrap: 'wrap', padding: '14px 0', borderTop: '1px solid var(--ttn-slate)', borderBottom: '1px solid var(--ttn-slate)', alignSelf: 'stretch' }}>
                    {mini.map((m) => (
                      <div key={m.label} style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                        <Icon name={m.icon} size={15} color="var(--ttn-grey)" />
                        <span style={{ fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-white)' }}>{m.value}</span>
                      </div>
                    ))}
                  </div>
                  <GoldLink onClick={() => onOpenDestination && onOpenDestination(orig)}>Explore {d.name}</GoldLink>
                </div>
              </div>
            </React.Fragment>
          );
        })}
      </div>
      {shown.length === 0 && (
        <EmptyState onAction={() => setActive('All')} />
      )}
    </TTN_Section>
  );
}

function CertifiedRow({ p, det, onOpen }) {
  const [hover, setHover] = React.useState(false);
  return (
    <a href="#" onClick={(e) => { e.preventDefault(); onOpen(); }}
      onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
      style={{ display: 'flex', alignItems: 'center', gap: 'var(--space-3)', padding: '20px 4px', textDecoration: 'none', borderBottom: `1px solid ${hover ? 'var(--ttn-gold)' : 'var(--ttn-slate)'}`, transition: 'border-color 120ms ease' }}>
      <div style={{ width: '80px', height: '80px', flexShrink: 0, borderRadius: 'var(--radius-btn)', overflow: 'hidden', background: 'var(--ttn-slate)' }}>
        <img src={p.thumb} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
      </div>
      <div style={{ flex: 1, minWidth: 0 }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '14px', flexWrap: 'wrap' }}>
          <h3 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '22px', color: 'var(--ttn-white)' }}>{p.name}</h3>
          <Tag variant="category">{p.category}</Tag>
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: '18px', marginTop: '8px', flexWrap: 'wrap' }}>
          <span style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
            <Icon name="mapPin" size={13} color="var(--ttn-grey)" />{p.location}
          </span>
          {det && det.since && <span style={{ fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>Certified since {det.since}</span>}
        </div>
      </div>
      <Icon name="arrowRight" size={18} color={hover ? 'var(--ttn-gold)' : 'var(--ttn-grey)'} />
    </a>
  );
}

function CertifiedScreen({ onOpenPartner }) {
  const partners = window.TTN_PARTNERS, details = window.TTN_PARTNER_DETAILS || [];
  const cats = ['All', 'Hotels', 'Tour Operators', 'Jeep Services', 'Guides'];
  const [active, setActive] = React.useState('All');
  const shown = partners.filter((p) => active === 'All' || `${p.category}s` === active);
  return (
    <TTN_Section>
      <div style={{ maxWidth: '60ch', marginBottom: 'var(--space-6)' }}>
        <Tag variant="certified">TTN Certified</Tag>
        <h1 style={{ margin: '16px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 9vw, 64px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>Certified partners</h1>
        <p style={{ margin: '14px 0 0', fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-grey)' }}>Businesses the To The North team has visited, vetted and stands behind. The gold mark means we trust them with your trip.</p>
      </div>

      {/* What certification means */}
      <div style={{ border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', padding: 'var(--space-4)', marginBottom: 'var(--space-6)', maxWidth: '860px' }}>
        <div style={idxEyebrow}>What the mark means</div>
        <p style={{ margin: '12px 0 0', fontFamily: 'var(--font-body)', fontSize: '15px', lineHeight: 1.7, color: 'var(--ttn-white)' }}>Every certified business has been used by the TTN team on our own trips, unannounced and at full price. We check how they treat travellers when things go wrong, not just when they go right. Certification is reviewed yearly, cannot be bought, and is withdrawn if the standard slips.</p>
      </div>

      <FilterPills items={cats} active={active} onChange={setActive} />
      <div style={{ borderTop: '1px solid var(--ttn-slate)' }}>
        {shown.map((p) => {
          const orig = partners.indexOf(p);
          return <CertifiedRow key={orig} p={p} det={details[orig]} onOpen={() => onOpenPartner && onOpenPartner(orig)} />;
        })}
      </div>
      {shown.length === 0 && (
        <EmptyState message="No certified partners in this category yet. We only list who we have vetted." onAction={() => setActive('All')} />
      )}
    </TTN_Section>
  );
}

function CommunityPostCard({ post }) {
  const [hover, setHover] = React.useState(false);
  return (
    <a href={post.fb} target="_blank" rel="noopener noreferrer"
      onMouseEnter={() => setHover(true)} onMouseLeave={() => setHover(false)}
      style={{ display: 'block', textDecoration: 'none', background: 'var(--ttn-deep)', border: `1px solid ${hover ? 'var(--ttn-border-hover, #333333)' : 'var(--ttn-slate)'}`, borderRadius: 'var(--radius-card)', overflow: 'hidden', transition: 'border-color 120ms ease' }}>
      {post.image && (
        <div style={{ aspectRatio: '16 / 9', background: 'var(--ttn-slate)' }}>
          <img src={post.image} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
        </div>
      )}
      <div style={{ padding: 'var(--space-3)' }}>
        <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '15px', lineHeight: 1.7, color: 'var(--ttn-white)' }}>{post.quote}</p>
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginTop: '16px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
          <span style={{ fontWeight: 600 }}>{post.name}</span>{post.date && <><span>·</span><span>{post.date}</span></>}
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: '6px', marginTop: '14px', paddingTop: '14px', borderTop: '1px solid var(--ttn-slate)', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
          <Icon name="externalLink" size={13} color="var(--ttn-grey)" />View on Facebook
        </div>
      </div>
    </a>
  );
}

function CommunityScreen() {
  const community = window.TTN_COMMUNITY;
  return (
    <div>
      <TTN_Section>
        <div style={{ maxWidth: '60ch', marginBottom: 'var(--space-6)' }}>
          <h1 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 9vw, 64px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>Community</h1>
          <p style={{ margin: '14px 0 0', fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-grey)' }}>The road is better shared. These are the people who actually drove it, and what they brought back.</p>
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: '14px', marginBottom: 'var(--space-3)' }}>
          <div style={idxEyebrow}>Featured from the community</div>
          <window.TTN_Placeholder label="Placeholder posts" />
        </div>
        <div className="ttn-masonry">
          {community.map((c, i) => <CommunityPostCard key={i} post={c} />)}
        </div>
      </TTN_Section>

      <TTN_Section alt>
        <div style={{ textAlign: 'center', maxWidth: '40ch', margin: '0 auto' }}>
          <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(34px, 7vw, 52px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>466,000 travellers and counting</h2>
          <p style={{ margin: '18px 0 28px', fontFamily: 'var(--font-body)', fontSize: '18px', color: 'var(--ttn-grey)' }}>Built over seven years on Facebook. Ask a question, share a route, or just post the photo you cannot stop looking at.</p>
          <Button variant="primary" size="lg" icon={<Icon name="facebook" size={18} />}>Join the Facebook group</Button>
        </div>
      </TTN_Section>
    </div>
  );
}

function AboutScreen() {
  const PH = window.TTN_PH;
  const admins = ['Waseem', 'Hira', 'Bilal', 'Zoya'];
  const stats = [
    { n: '466,000', l: 'Community members' },
    { n: '7', l: 'Years on the road' },
    { n: '6', l: 'Destinations documented' },
    { n: '3', l: 'Certified partners' },
  ];
  const vmHead = { margin: 0, fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' };
  const vmBody = { margin: '18px 0 0', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(20px, 3vw, 26px)', lineHeight: 1.4, letterSpacing: '-0.01em', color: 'var(--ttn-white)' };
  return (
    <div>
      {/* 1. Founder story */}
      <TTN_Section>
        <div className="ttn-split">
          <div style={{ borderRadius: 'var(--radius-card)', overflow: 'hidden', aspectRatio: '3 / 4', background: 'var(--ttn-slate)' }}>
            <img src={PH.portrait} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
          </div>
          <div>
            <div style={{ display: 'flex', alignItems: 'center', gap: '14px' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-gold)' }}>Our story</div>
              <window.TTN_Placeholder />
            </div>
            <h1 style={{ margin: '16px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(36px, 7vw, 56px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>It started with a camera and a road.</h1>
            <p style={{ margin: '20px 0 0', fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-white)', maxWidth: '60ch' }}>To The North began in 2018 as one photographer's record of the northern valleys. Seven years later it is a community of hundreds of thousands, still run by people who load up the jeep and go.</p>
            <p style={{ margin: '18px 0 0', fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.7, color: 'var(--ttn-grey)', maxWidth: '60ch' }}>We are not a travel agency. We are travellers who photograph what we find and tell you the truth about getting there.</p>
          </div>
        </div>
      </TTN_Section>

      {/* 2. Stats band */}
      <div style={{ background: 'var(--ttn-deep)', borderTop: '1px solid var(--ttn-slate)', borderBottom: '1px solid var(--ttn-slate)' }}>
        <div className="ttn-inner ttn-stats-about" style={{ padding: 'var(--space-6) var(--gutter-desktop)' }}>
          {stats.map((s) => (
            <div key={s.l} style={{ textAlign: 'center' }}>
              <div style={{ fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(34px, 5vw, 52px)', lineHeight: 1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{s.n}</div>
              <div style={{ marginTop: '12px', fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>{s.l}</div>
            </div>
          ))}
        </div>
      </div>

      {/* 3. Vision and Mission — side by side */}
      <TTN_Section>
        <div className="ttn-g2">
          <div style={{ border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', padding: 'var(--space-5)' }}>
            <h2 style={vmHead}>Vision</h2>
            <p style={vmBody}>The north documented honestly, valley by valley, by the people who drive it.</p>
          </div>
          <div style={{ border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', padding: 'var(--space-5)' }}>
            <h2 style={vmHead}>Mission</h2>
            <p style={vmBody}>Promote tourism and mountaineering in the Northern Areas of Pakistan.</p>
          </div>
        </div>
      </TTN_Section>

      {/* 4. Gallery — generous */}
      <TTN_Section alt>
        <div style={{ textAlign: 'center', marginBottom: 'var(--space-6)' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)', marginBottom: '14px' }}>The work</div>
          <h2 style={{ margin: 0, fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(26px, 4.5vw, 34px)', lineHeight: 1.15, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>Seven years of the north</h2>
        </div>
        <div className="ttn-gallery">
          {PH.portfolio.map((item, i) => (
            <figure key={i} className={i === 0 ? 'ttn-gallery-wide' : ''} style={{ margin: 0 }}>
              <div style={{ borderRadius: 'var(--radius-card)', overflow: 'hidden', aspectRatio: i === 0 ? '16 / 9' : '3 / 2', background: 'var(--ttn-slate)' }}>
                <img src={item.src || item} alt={item.caption || ''} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
              </div>
              {item.caption && (
                <figcaption style={{ margin: '10px 0 0', display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
                  <Icon name="mapPin" size={12} color="var(--ttn-grey)" />{item.caption}
                </figcaption>
              )}
            </figure>
          ))}
        </div>
        <p style={{ margin: '16px 0 0', fontFamily: 'var(--font-body)', fontSize: '11px', color: 'var(--ttn-grey)' }}>Photo: Waseem Photography</p>
      </TTN_Section>

      {/* 5. Team */}
      <TTN_Section>
        <TTN_SectionHeader title="The team" />
        <div className="ttn-g4">
          {admins.map((name, i) => (
            <div key={i} style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '14px', textAlign: 'center' }}>
              <div style={{ width: '110px', height: '110px', borderRadius: '50%', overflow: 'hidden', background: 'var(--ttn-slate)' }}>
                <img src={PH.team} alt="" style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
              </div>
              <div>
                <div style={{ fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '20px', color: 'var(--ttn-white)' }}>{name}</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)', marginTop: '2px' }}>Admin</div>
              </div>
            </div>
          ))}
        </div>
      </TTN_Section>
    </div>
  );
}

function NotFoundScreen({ onHome }) {
  const S = window.TTN_SLOT;
  const img = S(1920, 1080, ['404 · full-bleed', 'A road ending at a river or slide, honest and beautiful', 'Headline centred'], 22);
  return (
    <div data-screen-label="404">
      <section style={{ position: 'relative', minHeight: '80vh', display: 'flex', alignItems: 'center', justifyContent: 'center', overflow: 'hidden' }}>
        <img src={img} alt="" style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'var(--hero-overlay)' }} />
        <div style={{ position: 'relative', textAlign: 'center', padding: '0 var(--gutter-desktop)', maxWidth: '620px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>404</div>
          <h1 style={{ margin: '18px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(36px, 7vw, 56px)', lineHeight: 1.1, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>This road does not go anywhere.</h1>
          <p style={{ margin: '18px 0 0', fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.7, color: 'var(--ttn-grey-light, #B4B4AE)' }}>The page you are looking for has washed out, slid away, or never existed. Head back and take the other fork.</p>
          <div style={{ marginTop: 'var(--space-4)' }}>
            <Button variant="secondary" size="md" onClick={onHome}>Back to the homepage</Button>
          </div>
        </div>
      </section>
    </div>
  );
}

Object.assign(window, { TTN_HomeScreen: HomeScreen, TTN_StoriesScreen: StoriesScreen, TTN_PostScreen: PostScreen, TTN_DestinationsScreen: DestinationsScreen, TTN_CertifiedScreen: CertifiedScreen, TTN_CommunityScreen: CommunityScreen, TTN_AboutScreen: AboutScreen, TTN_NotFoundScreen: NotFoundScreen });
