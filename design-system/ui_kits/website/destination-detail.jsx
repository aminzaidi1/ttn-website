/* To The North — Destination detail screen */
const _TTND = window.ToTheNorthDesignSystem_dc220a;
const { Button: DDButton, Tag: DDTag, Icon: DDIcon, StoryCard: DDStoryCard, DestinationCard: DDDestinationCard, PartnerCard: DDPartnerCard, CommunityQuote: DDCommunityQuote } = _TTND;

const ddPara = { fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-white)', margin: '0 0 24px' };
const ddH2 = { margin: '0 0 24px', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(26px, 4.5vw, 34px)', lineHeight: 1.15, letterSpacing: '-0.02em', color: 'var(--ttn-white)' };
const ddH3 = { margin: '0 0 10px', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '21px', lineHeight: 1.25, color: 'var(--ttn-white)' };

const DD_SECTIONS = [
  { id: 'dd-overview', label: 'Overview' },
  { id: 'dd-getting-there', label: 'Getting there' },
  { id: 'dd-stay', label: 'Where to stay' },
  { id: 'dd-todo', label: 'What to do' },
  { id: 'dd-tips', label: 'Community tips' },
];

function DDStatsBar({ stats }) {
  const items = [
    { icon: 'calendar', label: 'Best season', value: stats.season },
    { icon: 'mountain', label: 'Altitude', value: stats.altitude },
    { icon: 'car', label: 'Drive from Islamabad', value: stats.drive },
    { icon: 'gauge', label: 'Difficulty', value: stats.difficulty },
    { icon: 'fileCheck', label: 'Permits required', value: stats.permits },
  ];
  return (
    <div style={{ background: 'var(--ttn-deep)', borderTop: '1px solid var(--ttn-slate)', borderBottom: '1px solid var(--ttn-slate)' }}>
      <div className="ttn-inner ttn-stats" style={{ padding: 'var(--dd-stats-pad, 28px) var(--gutter-desktop)' }}>
        {items.map((it) => (
          <div key={it.label} style={{ display: 'flex', alignItems: 'flex-start', gap: '12px' }}>
            <DDIcon name={it.icon} size={20} color="var(--ttn-grey)" style={{ marginTop: '2px' }} />
            <div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>{it.label}</div>
              <div style={{ marginTop: '4px', fontFamily: 'var(--font-body)', fontSize: '15px', fontWeight: 500, color: 'var(--ttn-white)' }}>{it.value}</div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function DDSubNav({ active, onJump }) {
  return (
    <div style={{ position: 'sticky', top: '72px', zIndex: 40, background: 'var(--ttn-black)', borderBottom: '1px solid var(--ttn-slate)' }}>
      <div className="ttn-inner" style={{ display: 'flex', gap: '28px', overflowX: 'auto' }}>
        {DD_SECTIONS.map((s) => {
          const on = s.id === active;
          return (
            <a key={s.id} href={`#${s.id}`} onClick={(e) => { e.preventDefault(); onJump(s.id); }}
              style={{
                fontFamily: 'var(--font-body)', fontSize: '13px', fontWeight: 500, letterSpacing: 'var(--ls-ui)',
                color: on ? 'var(--ttn-white)' : 'var(--ttn-grey)', textDecoration: 'none', whiteSpace: 'nowrap',
                padding: '15px 0 13px', borderBottom: `2px solid ${on ? 'var(--ttn-gold)' : 'transparent'}`,
              }}
            >{s.label}</a>
          );
        })}
      </div>
    </div>
  );
}

function DDRouteLegs({ legs }) {
  return (
    <div style={{ border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', margin: '8px 0 24px' }}>
      {legs.map((l, i) => (
        <div key={i} style={{ padding: '18px 22px', borderTop: i === 0 ? 'none' : '1px solid var(--ttn-slate)' }}>
          <div style={{ display: 'flex', alignItems: 'baseline', justifyContent: 'space-between', gap: '16px', flexWrap: 'wrap' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '16px', fontWeight: 600, color: 'var(--ttn-white)', display: 'flex', alignItems: 'center', gap: '10px' }}>
              {l.from}
              <DDIcon name="arrowRight" size={14} color="var(--ttn-grey)" />
              {l.to}
            </div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>{l.dist} · {l.time}</div>
          </div>
          {l.note && <p style={{ margin: '6px 0 0', fontFamily: 'var(--font-body)', fontSize: '14px', lineHeight: 1.6, color: 'var(--ttn-grey)' }}>{l.note}</p>}
        </div>
      ))}
    </div>
  );
}

function DDMap({ name, origin, o, d, coords }) {
  const W = 800, H = 450, PAD = 120;
  const midLat = ((o[0] + d[0]) / 2) * Math.PI / 180;
  const px = (lon) => lon * Math.cos(midLat);
  const xs = [px(o[1]), px(d[1])], ys = [o[0], d[0]];
  const spanX = Math.max(Math.abs(xs[1] - xs[0]), 0.05), spanY = Math.max(Math.abs(ys[1] - ys[0]), 0.05);
  const k = Math.min((W - 2 * PAD) / spanX, (H - 2 * PAD) / spanY);
  const cx = (xs[0] + xs[1]) / 2, cy = (ys[0] + ys[1]) / 2;
  const X = (lon) => W / 2 + (px(lon) - cx) * k;
  const Y = (lat) => H / 2 - (lat - cy) * k;
  const x1 = X(o[1]), y1 = Y(o[0]), x2 = X(d[1]), y2 = Y(d[0]);
  const mx = (x1 + x2) / 2 - (y2 - y1) * 0.18, my = (y1 + y2) / 2 + (x2 - x1) * 0.18;
  const oLabelBelow = y1 >= y2;
  return (
    <figure style={{ margin: '8px 0 0' }}>
      <div style={{ aspectRatio: '16 / 9', border: '1px solid var(--ttn-slate)', borderRadius: 'var(--radius-card)', overflow: 'hidden', background: 'var(--ttn-black)' }}>
        <svg viewBox="0 0 800 450" width="100%" height="100%" style={{ display: 'block' }}>
          <rect width="800" height="450" fill="#0A0A0A" />
          <g fill="none" stroke="#1E1E1E" strokeWidth="1">
            <path d="M0 90 H800" /><path d="M0 225 H800" /><path d="M0 360 H800" />
            <path d="M160 0 V450" /><path d="M400 0 V450" /><path d="M640 0 V450" />
          </g>
          <path d={`M ${x1} ${y1} Q ${mx} ${my} ${x2} ${y2}`} fill="none" stroke="#8A8A8A" strokeWidth="2" strokeDasharray="1 8" strokeLinecap="round" />
          <circle cx={x1} cy={y1} r="5" fill="none" stroke="#8A8A8A" strokeWidth="2" />
          <text x={x1} y={oLabelBelow ? y1 + 28 : y1 - 18} textAnchor="middle" fontFamily="Inter, sans-serif" fontSize="13" fill="#8A8A8A">{origin}</text>
          <circle cx={x2} cy={y2} r="14" fill="none" stroke="#F5A623" strokeWidth="1.5" opacity="0.5" />
          <circle cx={x2} cy={y2} r="6" fill="#F5A623" />
          <text x={x2} y={oLabelBelow ? y2 - 26 : y2 + 36} textAnchor="middle" fontFamily="Inter, sans-serif" fontSize="15" fontWeight="600" fill="#F5F5F0">{name}</text>
        </svg>
      </div>
      <figcaption style={{ margin: '12px 0 0', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
        {name} · {coords} · route from {origin} · schematic, not to scale
      </figcaption>
    </figure>
  );
}

function DDRoadStatus({ status }) {
  if (!status || !status.asOf || !status.text) return null;
  return (
    <div style={{ background: 'var(--ttn-deep)', borderBottom: '1px solid var(--ttn-slate)' }}>
      <div className="ttn-inner" style={{ display: 'flex', alignItems: 'flex-start', gap: '14px', padding: '18px var(--gutter-desktop)' }}>
        <DDIcon name="car" size={18} color="var(--ttn-grey)" style={{ marginTop: '2px', flexShrink: 0 }} />
        <div>
          <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '15px', lineHeight: 1.6, color: 'var(--ttn-white)' }}>{status.text}</p>
          <p style={{ margin: '6px 0 0', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>Road status as of {status.asOf}. Conditions change fast. Check the group before you drive.</p>
        </div>
      </div>
    </div>
  );
}

window.TTN_DDMap = DDMap;

function DDPhotoStrip({ strip, destName }) {
  return (
    <div className="ttn-nosb" style={{ display: 'flex', gap: 'var(--space-2)', overflowX: 'auto', padding: '0 var(--gutter-desktop)' }}>
      {strip.map((s, i) => (
        <figure key={i} style={{ margin: 0, flexShrink: 0, width: 'min(440px, 78vw)' }}>
          <div style={{ aspectRatio: '3 / 2', borderRadius: 'var(--radius-card)', overflow: 'hidden' }}>
            <img src={s.image} alt={s.caption} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
          </div>
          <figcaption style={{ margin: '10px 0 0', display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
            <DDIcon name="mapPin" size={12} color="var(--ttn-grey)" />{s.caption}, {destName}
          </figcaption>
        </figure>
      ))}
    </div>
  );
}

function DestinationDetailScreen({ index = 0, onOpenStory, onOpenDestination, onOpenPartner }) {
  const dests = window.TTN_DESTINATIONS;
  const details = window.TTN_DEST_DETAILS;
  const d = dests[index] || dests[0];
  const det = details[index] || details[0];
  const stories = window.TTN_STORIES;
  const partners = window.TTN_PARTNERS.filter((p) => p.region === d.region);
  const others = dests.map((x, i) => ({ ...x, _i: i })).filter((x) => x._i !== index).slice(0, 3);
  const [active, setActive] = React.useState('dd-overview');

  React.useEffect(() => {
    const onScroll = () => {
      let current = DD_SECTIONS[0].id;
      for (const s of DD_SECTIONS) {
        const el = document.getElementById(s.id);
        if (el && el.getBoundingClientRect().top <= 140) current = s.id;
      }
      setActive(current);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    return () => window.removeEventListener('scroll', onScroll);
  }, [index]);

  const jump = (id) => {
    const el = document.getElementById(id);
    if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 128, behavior: 'smooth' });
  };

  const article = { maxWidth: 'var(--dd-article, var(--w-article))', margin: '0 auto' };

  return (
    <div data-screen-label={`Destination — ${d.name}`}>
      {/* Hero */}
      <section style={{ position: 'relative', height: 'var(--dd-hero, 70vh)', display: 'flex', alignItems: 'flex-end', overflow: 'hidden' }}>
        <img src={det.hero} alt={d.name} style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'var(--hero-overlay)' }} />
        <div className="ttn-inner" style={{ position: 'relative', width: '100%', paddingBottom: 'var(--space-6)' }}>
          <DDTag variant="region">{d.region}</DDTag>
          <h1 style={{ margin: '16px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(40px, 8vw, 72px)', lineHeight: 1.05, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{d.name}</h1>
          <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: '24px', marginTop: 'var(--space-4)', flexWrap: 'wrap' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
              <DDIcon name="mapPin" size={14} color="var(--ttn-grey)" />{d.name}, {d.region}
            </div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', color: 'var(--ttn-grey)' }}>Photo: Waseem Photography</div>
          </div>
        </div>
      </section>

      <DDStatsBar stats={det.stats} />
      <DDRoadStatus status={det.status} />
      <DDSubNav active={active} onJump={jump} />

      {/* Overview */}
      <section id="dd-overview" className="ttn-section" style={{ background: 'var(--ttn-black)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
        <div className="ttn-inner"><div style={article}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '14px', marginBottom: '24px' }}>
            <h2 style={{ ...ddH2, marginBottom: 0 }}>Overview</h2>
            <window.TTN_Placeholder />
          </div>
          <p style={ddPara}>{det.overview[0]}</p>
          <blockquote style={{ borderLeft: '3px solid var(--ttn-gold)', paddingLeft: '24px', margin: '32px 0' }}>
            <p style={{ margin: 0, fontFamily: 'var(--font-display)', fontStyle: 'italic', fontWeight: 400, fontSize: '26px', lineHeight: 1.4, color: 'var(--ttn-white)' }}>{det.pull}</p>
          </blockquote>
          <p style={{ ...ddPara, margin: 0 }}>{det.overview[1]}</p>
        </div></div>
      </section>

      {/* Getting there + map */}
      <section id="dd-getting-there" className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
        <div className="ttn-inner"><div style={article}>
          <h2 style={ddH2}>Getting there</h2>
          {det.gettingThere.map((p, i) => <p key={i} style={ddPara}>{p}</p>)}
          <DDRouteLegs legs={det.legs} />
          <DDMap name={d.name} origin={det.map.origin} o={det.map.o} d={det.map.d} coords={det.map.coords} />
        </div></div>
      </section>

      {/* Where to stay */}
      <section id="dd-stay" className="ttn-section" style={{ background: 'var(--ttn-black)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
        <div className="ttn-inner"><div style={article}>
          <h2 style={ddH2}>Where to stay</h2>
          {det.stay.map((p, i) => <p key={i} style={{ ...ddPara, margin: 0 }}>{p}</p>)}
        </div></div>
      </section>

      {/* What to do — composable content blocks */}
      <section id="dd-todo" className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
        <div className="ttn-inner"><div style={{ maxWidth: '880px', margin: '0 auto' }}>
          <h2 style={{ ...ddH2, marginBottom: 'var(--space-6)' }}>What to do</h2>
          <div style={{ display: 'flex', flexDirection: 'column' }}>
            {(det.blocks || []).map((b, i) => (
              <div key={i} className="ttn-ddblock" style={{ display: 'grid', gridTemplateColumns: '180px 1fr', gap: 'var(--space-3)', padding: '28px 0', borderTop: '1px solid var(--ttn-slate)' }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)', paddingTop: '6px' }}>{b.kind}</div>
                <div>
                  <h3 style={ddH3}>{b.h}</h3>
                  <p style={{ ...ddPara, margin: 0, fontSize: '17px' }}>{b.p}</p>
                </div>
              </div>
            ))}
          </div>
        </div></div>
      </section>

      {/* Photo strip — full bleed, held still */}
      <section style={{ background: 'var(--ttn-black)', padding: 'var(--space-8) 0' }}>
        <DDPhotoStrip strip={det.strip} destName={d.name} />
        <p style={{ margin: '16px var(--gutter-desktop) 0', fontFamily: 'var(--font-body)', fontSize: '11px', color: 'var(--ttn-grey)' }}>Photo: Waseem Photography</p>
      </section>

      {/* Community tips — centred, generous */}
      <section id="dd-tips" className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--space-16, 128px) 0' }}>
        <div className="ttn-inner">
          <div style={{ textAlign: 'center', marginBottom: 'var(--space-6)' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)', marginBottom: '14px' }}>From the group</div>
            <h2 style={{ ...ddH2, marginBottom: 0 }}>Community tips</h2>
          </div>
          <div className="ttn-g2">
            {det.tips.map((t, i) => <DDCommunityQuote key={i} quote={t.quote} name={t.name} />)}
          </div>
        </div>
      </section>

      {/* Certified partners in region — the page's close begins */}
      {partners.length > 0 && (
        <section className="ttn-section" style={{ background: 'var(--ttn-black)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
          <div className="ttn-inner">
            <h2 style={{ ...ddH2, marginBottom: 'var(--space-6)' }}>TTN Certified in {d.region}</h2>
            <div className="ttn-g3">
              {partners.map((p, i) => <DDPartnerCard key={i} {...p} ctaLabel="View partner" onCta={(e) => { e.preventDefault(); onOpenPartner && onOpenPartner(window.TTN_PARTNERS.indexOf(p)); }} />)}
            </div>
          </div>
        </section>
      )}

      {/* Related destinations */}
      <section className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--dd-pad, var(--space-12)) 0' }}>
        <div className="ttn-inner">
          <h2 style={{ ...ddH2, marginBottom: 'var(--space-6)' }}>Keep exploring</h2>
          <div className="ttn-g3">
            {others.map((o) => (
              <DDDestinationCard key={o._i} image={o.image} name={o.name} region={o.region} height={340}
                onClick={(e) => { e.preventDefault(); onOpenDestination && onOpenDestination(o._i); }} />
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}

window.TTN_DestinationDetailScreen = DestinationDetailScreen;
