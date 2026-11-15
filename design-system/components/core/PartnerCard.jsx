import React from 'react';
import { Tag } from './Tag.jsx';
import { Button } from './Button.jsx';
import { Icon } from './Icon.jsx';

/**
 * To The North — TTN Certified Partner card.
 * Gold border differentiates partners from editorial cards.
 */
export function PartnerCard({
  name,
  category,
  location,
  description,
  ctaLabel = 'Contact',
  href = '#',
  onCta,
  style = {},
}) {
  return (
    <div
      style={{
        position: 'relative',
        background: 'var(--ttn-deep)',
        border: '1px solid var(--ttn-gold)',
        borderRadius: 'var(--radius-card)',
        padding: 'var(--space-3)',
        display: 'flex',
        flexDirection: 'column',
        gap: '10px',
        ...style,
      }}
    >
      <div style={{ position: 'absolute', top: '16px', right: '16px' }}>
        <Tag variant="certified">TTN Certified</Tag>
      </div>
      <div style={{ paddingRight: '120px' }}>
        <h3
          style={{
            margin: 0,
            fontFamily: 'var(--font-display)',
            fontWeight: 700,
            fontSize: '20px',
            color: 'var(--ttn-white)',
          }}
        >
          {name}
        </h3>
        {category && (
          <div
            style={{
              marginTop: '4px',
              fontFamily: 'var(--font-body)',
              fontSize: '13px',
              fontWeight: 500,
              color: 'var(--ttn-gold)',
            }}
          >
            {category}
          </div>
        )}
      </div>
      {location && (
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '6px',
            fontFamily: 'var(--font-body)',
            fontSize: '13px',
            color: 'var(--ttn-grey)',
          }}
        >
          <Icon name="mapPin" size={14} color="var(--ttn-grey)" />
          {location}
        </div>
      )}
      {description && (
        <p
          style={{
            margin: 0,
            fontFamily: 'var(--font-body)',
            fontSize: '14px',
            lineHeight: 1.6,
            color: 'var(--ttn-white)',
            display: '-webkit-box',
            WebkitLineClamp: 3,
            WebkitBoxOrient: 'vertical',
            overflow: 'hidden',
          }}
        >
          {description}
        </p>
      )}
      <div style={{ marginTop: '6px' }}>
        <Button variant="secondary" size="sm" href={href} onClick={onCta}>
          {ctaLabel}
        </Button>
      </div>
    </div>
  );
}
