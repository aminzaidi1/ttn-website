import React from 'react';
import { Tag } from './Tag.jsx';

/**
 * To The North — Destination card.
 * Full-bleed image with overlay, location name bottom-left, grey region pill top-right
 * (the gold region tag is reserved for the destination detail hero).
 * Hover: image scales 1.02.
 */
export function DestinationCard({ image, name, region, href = '#', height = 360, onClick, style = {} }) {
  const [hover, setHover] = React.useState(false);
  return (
    <a
      href={href}
      onClick={onClick}
      onMouseEnter={() => setHover(true)}
      onMouseLeave={() => setHover(false)}
      style={{
        position: 'relative',
        display: 'block',
        height: typeof height === 'number' ? `${height}px` : height,
        borderRadius: 'var(--radius-card)',
        overflow: 'hidden',
        textDecoration: 'none',
        ...style,
      }}
    >
      <img
        src={image}
        alt={name}
        style={{
          position: 'absolute',
          inset: 0,
          width: '100%',
          height: '100%',
          objectFit: 'cover',
          transform: hover ? 'scale(1.02)' : 'scale(1)',
          transition: 'transform .4s ease',
        }}
      />
      <div
        style={{
          position: 'absolute',
          inset: 0,
          background: 'linear-gradient(to bottom, rgba(10,10,10,0.10) 30%, rgba(10,10,10,0.80) 100%)',
        }}
      />
      {region && (
        <div style={{ position: 'absolute', top: '16px', right: '16px' }}>
          <Tag variant="category">{region}</Tag>
        </div>
      )}
      <h3
        style={{
          position: 'absolute',
          left: '20px',
          bottom: '18px',
          right: '20px',
          margin: 0,
          fontFamily: 'var(--font-display)',
          fontWeight: 700,
          fontSize: '26px',
          lineHeight: 1.15,
          color: 'var(--ttn-white)',
        }}
      >
        {name}
      </h3>
    </a>
  );
}
