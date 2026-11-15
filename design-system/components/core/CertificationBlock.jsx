import React from 'react';
import { Tag } from './Tag.jsx';

/**
 * To The North — Certification block.
 * The signed endorsement that makes TTN Certified a human verdict,
 * not a directory listing. Gold 1px border on TTN Deep; the verdict
 * is written in first person plural by the TTN team.
 */
export function CertificationBlock({ since, verdict, signature = 'The To The North team', style = {} }) {
  return (
    <div
      style={{
        background: 'var(--ttn-deep)',
        border: '1px solid var(--ttn-gold)',
        borderRadius: 'var(--radius-card)',
        padding: 'var(--space-6, 40px) var(--space-6, 40px) var(--space-4, 32px)',
        ...style,
      }}
    >
      <div style={{ display: 'flex', alignItems: 'center', gap: '16px', flexWrap: 'wrap' }}>
        <Tag variant="certified">TTN Certified</Tag>
        {since && (
          <span
            style={{
              fontFamily: 'var(--font-body)',
              fontSize: '13px',
              color: 'var(--ttn-grey)',
            }}
          >
            Certified since {since}
          </span>
        )}
      </div>
      <p
        style={{
          margin: '24px 0 0',
          fontFamily: 'var(--font-display)',
          fontStyle: 'italic',
          fontWeight: 400,
          fontSize: '20px',
          lineHeight: 1.6,
          color: 'var(--ttn-white)',
        }}
      >
        {verdict}
      </p>
      <div
        style={{
          marginTop: '20px',
          fontFamily: 'var(--font-body)',
          fontSize: '13px',
          color: 'var(--ttn-grey)',
        }}
      >
        {signature}
      </div>
    </div>
  );
}
