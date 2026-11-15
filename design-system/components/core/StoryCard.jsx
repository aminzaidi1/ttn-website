import React from 'react';
import { Tag } from './Tag.jsx';
import { Icon } from './Icon.jsx';

/**
 * To The North — Story / Blog card.
 * 16:9 image, grey category pill (gold is reserved for the single featured story), Playfair title, grey excerpt + meta.
 * Hover: border lifts, title shifts to gold. Tag is grey; gold is featured-only.
 */
export function StoryCard({
  image,
  tag,
  title,
  excerpt,
  author,
  date,
  readTime,
  href = '#',
  style = {},
}) {
  const [hover, setHover] = React.useState(false);
  return (
    <a
      href={href}
      onMouseEnter={() => setHover(true)}
      onMouseLeave={() => setHover(false)}
      style={{
        display: 'block',
        background: 'var(--ttn-deep)',
        border: `1px solid ${hover ? 'var(--ttn-border-lift)' : 'var(--ttn-slate)'}`,
        borderRadius: 'var(--radius-card)',
        overflow: 'hidden',
        textDecoration: 'none',
        transition: 'border-color .2s ease',
        ...style,
      }}
    >
      <div style={{ aspectRatio: '16 / 9', overflow: 'hidden', background: 'var(--ttn-slate)' }}>
        <img
          src={image}
          alt={title}
          style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
        />
      </div>
      <div style={{ padding: 'var(--space-3)' }}>
        {tag && (
          <div style={{ marginBottom: '12px' }}>
            <Tag variant="category">{tag}</Tag>
          </div>
        )}
        <h3
          style={{
            margin: 0,
            fontFamily: 'var(--font-display)',
            fontWeight: 700,
            fontSize: '22px',
            lineHeight: 1.2,
            color: hover ? 'var(--ttn-gold)' : 'var(--ttn-white)',
            transition: 'color .2s ease',
          }}
        >
          {title}
        </h3>
        {excerpt && (
          <p
            style={{
              margin: '10px 0 0',
              fontFamily: 'var(--font-body)',
              fontSize: '15px',
              lineHeight: 1.6,
              color: 'var(--ttn-grey-light, #B4B4AE)',
              display: '-webkit-box',
              WebkitLineClamp: 2,
              WebkitBoxOrient: 'vertical',
              overflow: 'hidden',
            }}
          >
            {excerpt}
          </p>
        )}
        <div
          style={{
            marginTop: '16px',
            display: 'flex',
            alignItems: 'center',
            gap: '8px',
            fontFamily: 'var(--font-body)',
            fontSize: '13px',
            color: 'var(--ttn-grey)',
          }}
        >
          {author && <span>{author}</span>}
          {date && <span aria-hidden="true">·</span>}
          {date && <span>{date}</span>}
          {readTime && (
            <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', marginLeft: 'auto' }}>
              <Icon name="clock" size={13} color="var(--ttn-grey)" />
              {readTime}
            </span>
          )}
        </div>
      </div>
    </a>
  );
}
