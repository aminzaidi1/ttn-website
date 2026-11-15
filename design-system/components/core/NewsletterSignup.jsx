import React from 'react';
import { Button } from './Button.jsx';
import { Icon } from './Icon.jsx';

/**
 * To The North — Newsletter signup.
 * variant: embedded (full section), footer (compact strip), inline (post CTA).
 * Inline input + button on desktop, stacked on mobile (handled by consumer width).
 */
export function NewsletterSignup({
  variant = 'embedded',
  eyebrow = 'The TTN Newsletter',
  headline = 'The north, delivered to your inbox',
  subline = 'One email a week. New stories, destinations and certified partners.',
  buttonLabel = 'Subscribe',
  style = {},
}) {
  const [state, setState] = React.useState('idle'); // idle | success | error
  const [value, setValue] = React.useState('');

  const submit = (e) => {
    e.preventDefault();
    if (!value || !/.+@.+\..+/.test(value)) {
      setState('error');
      return;
    }
    setState('success');
  };

  const compact = variant === 'footer' || variant === 'inline';

  const field = (
    <form
      onSubmit={submit}
      style={{ display: 'flex', alignItems: 'stretch', maxWidth: compact ? '420px' : '480px', width: '100%' }}
    >
      <input
        type="email"
        value={value}
        onChange={(e) => { setValue(e.target.value); if (state === 'error') setState('idle'); }}
        placeholder="you@email.com"
        style={{
          flex: 1,
          minWidth: 0,
          background: 'var(--ttn-slate)',
          border: `1px solid ${state === 'error' ? 'var(--ttn-error)' : 'var(--ttn-border-lift)'}`,
          borderRight: 'none',
          borderRadius: 'var(--radius-input) 0 0 var(--radius-input)',
          color: 'var(--ttn-white)',
          fontFamily: 'var(--font-body)',
          fontSize: compact ? '14px' : '15px',
          padding: compact ? '11px 14px' : '14px 16px',
          outline: 'none',
        }}
      />
      <Button variant="primary" size={compact ? 'sm' : 'md'} style={{ borderRadius: '0 var(--radius-input) var(--radius-input) 0' }}>
        {buttonLabel}
      </Button>
    </form>
  );

  const success = (
    <div
      style={{
        display: 'flex',
        alignItems: 'center',
        gap: '10px',
        fontFamily: 'var(--font-body)',
        fontSize: '15px',
        color: 'var(--ttn-white)',
      }}
    >
      <Icon name="check" size={20} color="var(--ttn-gold)" />
      You're in. See you in the north.
    </div>
  );

  const errorMsg = state === 'error' && (
    <div style={{ marginTop: '8px', fontFamily: 'var(--font-body)', fontSize: '13px', color: 'var(--ttn-error)' }}>
      Please enter a valid email address.
    </div>
  );

  // INLINE (bottom of blog post): thin gold top border
  if (variant === 'inline') {
    return (
      <div style={{ borderTop: '2px solid var(--ttn-gold)', paddingTop: 'var(--space-3)', ...style }}>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '16px', alignItems: 'center', justifyContent: 'space-between' }}>
          <p style={{ margin: 0, fontFamily: 'var(--font-body)', fontSize: '15px', color: 'var(--ttn-white)', maxWidth: '36ch' }}>
            Enjoyed this story? Get more like it every week.
          </p>
          {state === 'success' ? success : field}
        </div>
        {errorMsg}
      </div>
    );
  }

  // FOOTER: label left, field right
  if (variant === 'footer') {
    return (
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '16px', alignItems: 'center', justifyContent: 'space-between', ...style }}>
        <span style={{ fontFamily: 'var(--font-body)', fontSize: '14px', fontWeight: 500, letterSpacing: 'var(--ls-ui)', color: 'var(--ttn-white)' }}>
          Get the weekly dispatch from the north
        </span>
        <div>
          {state === 'success' ? success : field}
          {errorMsg}
        </div>
      </div>
    );
  }

  // EMBEDDED: full section
  return (
    <div style={{ background: 'var(--ttn-deep)', padding: 'var(--space-8) var(--space-6)', ...style }}>
      <div style={{ maxWidth: '640px', margin: '0 auto', textAlign: 'center' }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: '12px', fontWeight: 600, letterSpacing: 'var(--ls-eyebrow)', textTransform: 'uppercase', color: 'var(--ttn-gold)' }}>
          {eyebrow}
        </div>
        <h2 style={{ margin: '14px 0 0', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: '36px', lineHeight: 1.15, color: 'var(--ttn-white)' }}>
          {headline}
        </h2>
        <p style={{ margin: '12px 0 24px', fontFamily: 'var(--font-body)', fontSize: '16px', lineHeight: 1.6, color: 'var(--ttn-grey)' }}>
          {subline}
        </p>
        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          {state === 'success' ? success : field}
          {errorMsg}
        </div>
      </div>
    </div>
  );
}
