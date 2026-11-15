import React from 'react';
import { Icon } from './Icon.jsx';
import { Button } from './Button.jsx';

/**
 * To The North — Empty state.
 * Shown when a filtered view has nothing to show: grey mountain icon,
 * one short line in the TTN voice, ghost button to clear the filter.
 */
export function EmptyState({
  message = 'Nothing here yet. The road is still being built.',
  actionLabel = 'Clear filter',
  onAction,
  style = {},
}) {
  return (
    <div
      style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        gap: 'var(--space-3)',
        padding: 'var(--space-8) var(--space-3)',
        textAlign: 'center',
        ...style,
      }}
    >
      <Icon name="mountain" size={32} color="var(--ttn-grey)" />
      <p
        style={{
          margin: 0,
          fontFamily: 'var(--font-body)',
          fontSize: '16px',
          lineHeight: 1.6,
          color: 'var(--ttn-grey-light, #B4B4AE)',
          maxWidth: '40ch',
        }}
      >
        {message}
      </p>
      {onAction && (
        <Button variant="secondary" size="sm" onClick={onAction}>
          {actionLabel}
        </Button>
      )}
    </div>
  );
}
