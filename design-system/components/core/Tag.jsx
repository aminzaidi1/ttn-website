import React from 'react';

/**
 * To The North — Tag / Badge
 * variant: category (neutral pill), region (gold fill), certified (gold-bordered, uppercase).
 */
export function Tag({ variant = 'category', icon, children, style = {}, ...rest }) {
  const base = {
    display: 'inline-flex',
    alignItems: 'center',
    gap: '5px',
    fontFamily: 'var(--font-body)',
    lineHeight: 1,
    whiteSpace: 'nowrap',
    borderRadius: 'var(--radius-pill)',
    border: '1px solid transparent',
  };

  const variants = {
    category: {
      background: 'var(--ttn-slate)',
      color: 'var(--ttn-grey)',
      fontSize: '12px',
      fontWeight: 500,
      padding: '5px 12px',
    },
    region: {
      background: 'var(--ttn-gold)',
      color: 'var(--ttn-black)',
      fontSize: '11px',
      fontWeight: 600,
      letterSpacing: 'var(--ls-badge)',
      textTransform: 'uppercase',
      padding: '5px 11px',
    },
    certified: {
      background: 'var(--ttn-black)',
      color: 'var(--ttn-gold)',
      borderColor: 'var(--ttn-gold)',
      fontSize: '11px',
      fontWeight: 600,
      letterSpacing: 'var(--ls-badge)',
      textTransform: 'uppercase',
      padding: '5px 12px',
    },
    gold: {
      background: 'transparent',
      color: 'var(--ttn-gold)',
      fontSize: '11px',
      fontWeight: 600,
      letterSpacing: 'var(--ls-badge)',
      textTransform: 'uppercase',
      padding: '0',
    },
  };

  return (
    <span style={{ ...base, ...variants[variant], ...style }} {...rest}>
      {icon}
      {children}
    </span>
  );
}
