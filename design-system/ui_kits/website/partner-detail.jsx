/* To The North — Partner detail screen (TTN Certified) */
const _TTNP = window.ToTheNorthDesignSystem_dc220a;
const { Button: PDButton, Tag: PDTag, Icon: PDIcon, PartnerCard: PDPartnerCard, CertificationBlock: PDCertificationBlock } = _TTNP;

const pdPara = { fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-white)', margin: '0 0 24px' };
const pdH2 = { margin: '0 0 24px', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 'clamp(26px, 4.5vw, 34px)', lineHeight: 1.15, letterSpacing: '-0.02em', color: 'var(--ttn-white)' };

function PartnerDetailScreen({ index = 0, onOpenPartner }) {
  const partners = window.TTN_PARTNERS;
  const details = window.TTN_PARTNER_DETAILS;
  const p = partners[index] || partners[0];
  const det = details[index] || details[0];
  const others = partners.map((x, i) => ({ ...x, _i: i })).filter((x) => x._i !== index);
  const DDMap = window.TTN_DDMap;
  const article = { maxWidth: 'var(--w-article)', margin: '0 auto' };

  return (
    <div data-screen-label={`Partner — ${p.name}`}>
      {/* Hero */}
      <section style={{ position: 'relative', height: '55vh', display: 'flex', alignItems: 'flex-end', overflow: 'hidden' }}>
        <img src={det.hero} alt={p.name} style={{ position: 'absolute', inset: 0, width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'var(--hero-overlay)' }} />
        <div className="ttn-inner" style={{ position: 'relative', width: '100%', paddingBottom: 'var(--space-6)' }}>
          <PDTag variant="category">{p.category}</PDTag>
          <h1 style={{ margin: '16px 0 0', fontFamily: 'var(--font-display)', fontWeight: 900, fontSize: 'clamp(36px, 7vw, 60px)', lineHeight: 1.05, letterSpacing: '-0.02em', color: 'var(--ttn-white)' }}>{p.name}</h1>
          <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', gap: '24px', marginTop: 'var(--space-3)', flexWrap: 'wrap' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-grey)' }}>
              <PDIcon name="mapPin" size={14} color="var(--ttn-grey)" />{p.location}
            </div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', color: 'var(--ttn-grey)' }}>Photo: Waseem Photography</div>
          </div>
        </div>
      </section>

      {/* Certification block — the product */}
      <section style={{ background: 'var(--ttn-black)', padding: 'var(--space-8) 0 0' }}>
        <div className="ttn-inner">
          <div style={{ maxWidth: '780px', margin: '0 auto' }}>
            <PDCertificationBlock since={det.since} verdict={det.verdict} />
          </div>
        </div>
      </section>

      {/* Overview */}
      <section className="ttn-section" style={{ background: 'var(--ttn-black)', padding: 'var(--space-12) 0' }}>
        <div className="ttn-inner"><div style={article}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '14px', marginBottom: '24px' }}>
            <h2 style={{ ...pdH2, marginBottom: 0 }}>Overview</h2>
            <window.TTN_Placeholder />
          </div>
          {det.overview.map((t, i) => <p key={i} style={{ ...pdPara, margin: i === det.overview.length - 1 ? 0 : '0 0 24px' }}>{t}</p>)}
        </div></div>
      </section>

      {/* What they offer */}
      <section className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--space-12) 0' }}>
        <div className="ttn-inner"><div style={article}>
          <h2 style={pdH2}>What they offer</h2>
          <div style={{ borderTop: '1px solid var(--ttn-slate)' }}>
            {det.offers.map((o, i) => (
              <div key={i} style={{ display: 'flex', alignItems: 'center', gap: '18px', padding: '18px 4px', borderBottom: '1px solid var(--ttn-slate)' }}>
                <PDIcon name={o.icon} size={20} color="var(--ttn-grey)" />
                <span style={{ fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.5, color: 'var(--ttn-white)' }}>{o.text}</span>
              </div>
            ))}
          </div>
        </div></div>
      </section>

      {/* Gallery — staggered, generous */}
      <section className="ttn-section" style={{ background: 'var(--ttn-black)', padding: 'var(--space-16, 128px) 0' }}>
        <div className="ttn-inner">
          <div style={{ textAlign: 'center', marginBottom: 'var(--space-6)' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)', marginBottom: '14px' }}>{p.name}</div>
            <h2 style={{ ...pdH2, marginBottom: 0 }}>On the ground</h2>
          </div>
          <div className="ttn-gallery">
            {det.gallery.map((g, i) => (
              <figure key={i} className={i % 4 === 0 ? 'ttn-gallery-wide' : ''} style={{ margin: 0 }}>
                <div style={{ borderRadius: 'var(--radius-card)', overflow: 'hidden', aspectRatio: i % 4 === 0 ? '16 / 9' : '3 / 2', background: 'var(--ttn-slate)' }}>
                  <img src={g.image} alt={g.caption} style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }} />
                </div>
                <figcaption style={{ margin: '10px 0 0', display: 'flex', alignItems: 'center', gap: '6px', fontFamily: 'var(--font-body)', fontSize: '12px', color: 'var(--ttn-grey)' }}>
                  <PDIcon name="mapPin" size={12} color="var(--ttn-grey)" />{g.caption}
                </figcaption>
              </figure>
            ))}
          </div>
          <p style={{ margin: '16px 0 0', fontFamily: 'var(--font-body)', fontSize: '11px', color: 'var(--ttn-grey)' }}>Photo: Waseem Photography</p>
        </div>
      </section>

      {/* Where to find them */}
      <section className="ttn-section" style={{ background: 'var(--ttn-deep)', padding: 'var(--space-12) 0' }}>
        <div className="ttn-inner"><div style={{ maxWidth: '720px', margin: '0 auto' }}>
          <h2 style={pdH2}>Where to find them</h2>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '22px', paddingTop: '8px' }}>
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: '14px' }}>
                <PDIcon name="mapPin" size={18} color="var(--ttn-grey)" style={{ marginTop: '3px' }} />
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>Address</div>
                  <div style={{ marginTop: '4px', fontFamily: 'var(--font-body)', fontSize: '15px', lineHeight: 1.6, color: 'var(--ttn-white)' }}>{det.address}</div>
                </div>
              </div>
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: '14px' }}>
                <PDIcon name="phone" size={18} color="var(--ttn-grey)" style={{ marginTop: '3px' }} />
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>Phone</div>
                  <div style={{ marginTop: '4px', fontFamily: 'var(--font-body)', fontSize: '15px', color: 'var(--ttn-white)' }}>{det.phone}</div>
                </div>
              </div>
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: '14px' }}>
                <PDIcon name="mail" size={18} color="var(--ttn-grey)" style={{ marginTop: '3px' }} />
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: '11px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-grey)' }}>Email</div>
                  <div style={{ marginTop: '4px', fontFamily: 'var(--font-body)', fontSize: '15px', color: 'var(--ttn-white)' }}>{det.email}</div>
                </div>
              </div>
          </div>
        </div></div>
      </section>

      {/* Contact CTA — the close */}
      <section style={{ background: 'var(--ttn-deep)', borderTop: '1px solid var(--ttn-slate)', borderBottom: '1px solid var(--ttn-slate)', padding: 'var(--space-8) 0' }}>
        <div className="ttn-inner" style={{ textAlign: 'center', maxWidth: '56ch', marginLeft: 'auto', marginRight: 'auto' }}>
          <p style={{ margin: '0 0 24px', fontFamily: 'var(--font-body)', fontSize: '18px', lineHeight: 1.7, color: 'var(--ttn-white)' }}>{det.cta}</p>
          <PDButton variant="secondary" size="lg" icon={<PDIcon name="mail" size={17} />}>Contact {p.name}</PDButton>
        </div>
      </section>
    </div>
  );
}

window.TTN_PartnerDetailScreen = PartnerDetailScreen;
