import React from 'react';
import { Icon } from './Icon.jsx';

/**
 * To The North — Featured Community Post.
 * Quote-style card with 3px gold left border. Optional image above text.
 */
export function CommunityQuote({ quote, name, image, style = {} }) {
  return (
    <figure
      style={{
        margin: 0,
        background: 'var(--ttn-deep)',
        borderLeft: '3px solid var(--ttn-gold)',
        borderRadius: '0 var(--radius-card) var(--radius-card) 0',
        overflow: 'hidden',
        ...style,
      }}
    >
      {image && (
        <img
          src={image}
          alt=""
          style={{ width: '100%', aspectRatio: '16 / 9', objectFit: 'cover', display: 'block' }}
        />
      )}
      <div style={{ padding: 'var(--space-3)' }}>
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '6px',
            fontFamily: 'var(--font-body)',
            fontSize: '12px',
            color: 'var(--ttn-grey)',
            marginBottom: '14px',
          }}
        >
          <Icon name="facebook" size={14} color="var(--ttn-grey)" />
          Featured from our community
        </div>
        <blockquote
          style={{
            margin: 0,
            fontFamily: 'var(--font-display)',
            fontStyle: 'italic',
            fontWeight: 400,
            fontSize: '18px',
            lineHeight: 1.5,
            color: 'var(--ttn-white)',
          }}
        >
          {quote}
        </blockquote>
        <figcaption
          style={{
            marginTop: '14px',
            fontFamily: 'var(--font-body)',
            fontSize: '13px',
            color: 'var(--ttn-grey)',
          }}
        >
          {name}, To The North Community
        </figcaption>
      </div>
    </figure>
  );
}
