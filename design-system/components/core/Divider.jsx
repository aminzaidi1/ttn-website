import React from 'react';

/**
 * To The North — Divider.
 * Plain 1px rule, or a labelled section divider (rule + centred uppercase label).
 */
export function Divider({ label, style = {} }) {
  if (!label) {
    return <hr style={{ border: 0, borderTop: '1px solid var(--ttn-slate)', margin: 0, ...style }} />;
  }
  return (
    <div style={{ display: 'flex', alignItems: 'center', gap: '20px', ...style }}>
      <span style={{ flex: 1, height: '1px', background: 'var(--ttn-slate)' }} />
      <span
        style={{
          fontFamily: 'var(--font-body)',
          fontSize: '12px',
          fontWeight: 500,
          letterSpacing: 'var(--ls-eyebrow)',
          textTransform: 'uppercase',
          color: 'var(--ttn-grey)',
          whiteSpace: 'nowrap',
        }}
      >
        {label}
      </span>
      <span style={{ flex: 1, height: '1px', background: 'var(--ttn-slate)' }} />
    </div>
  );
}
