import React from 'react';

/**
 * To The North — Button
 * Variants: primary (gold fill), secondary (gold ghost), text (gold link).
 */
export function Button({
  variant = 'primary',
  size = 'md',
  href,
  icon,
  iconRight,
  disabled = false,
  children,
  style = {},
  ...rest
}) {
  const pads = {
    sm: '10px 18px',
    md: '14px 28px',
    lg: '16px 34px',
  };

  const base = {
    display: 'inline-flex',
    alignItems: 'center',
    justifyContent: 'center',
    gap: '8px',
    fontFamily: 'var(--font-body)',
    fontSize: size === 'sm' ? '13px' : 'var(--fs-ui)',
    fontWeight: 600,
    letterSpacing: 'var(--ls-ui)',
    lineHeight: 1,
    border: '1px solid transparent',
    borderRadius: 'var(--radius-button)',
    cursor: disabled ? 'not-allowed' : 'pointer',
    opacity: disabled ? 0.45 : 1,
    textDecoration: 'none',
    transition: 'background-color .18s ease, color .18s ease, border-color .18s ease',
    whiteSpace: 'nowrap',
    padding: variant === 'text' ? '0' : pads[size],
  };

  const variants = {
    primary: {
      background: 'var(--ttn-gold)',
      color: 'var(--ttn-black)',
    },
    secondary: {
      background: 'transparent',
      color: 'var(--ttn-gold)',
      borderColor: 'var(--ttn-gold)',
    },
    text: {
      background: 'transparent',
      color: 'var(--ttn-gold)',
      fontWeight: 500,
    },
  };

  const onEnter = (e) => {
    if (disabled) return;
    if (variant === 'primary') e.currentTarget.style.background = 'var(--ttn-amber)';
    else if (variant === 'secondary') {
      e.currentTarget.style.background = 'var(--ttn-gold)';
      e.currentTarget.style.color = 'var(--ttn-black)';
    } else e.currentTarget.style.textDecoration = 'underline';
  };
  const onLeave = (e) => {
    if (disabled) return;
    if (variant === 'primary') e.currentTarget.style.background = 'var(--ttn-gold)';
    else if (variant === 'secondary') {
      e.currentTarget.style.background = 'transparent';
      e.currentTarget.style.color = 'var(--ttn-gold)';
    } else e.currentTarget.style.textDecoration = 'none';
  };

  const Tag = href ? 'a' : 'button';
  return (
    <Tag
      href={href}
      disabled={href ? undefined : disabled}
      onMouseEnter={onEnter}
      onMouseLeave={onLeave}
      style={{ ...base, ...variants[variant], ...style }}
      {...rest}
    >
      {icon}
      {children}
      {iconRight}
    </Tag>
  );
}
