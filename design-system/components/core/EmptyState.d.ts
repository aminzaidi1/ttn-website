import * as React from 'react';

export interface EmptyStateProps {
  /** One short line in the TTN voice */
  message?: string;
  /** Ghost button label; button renders only when onAction is given */
  actionLabel?: string;
  onAction?: () => void;
  style?: React.CSSProperties;
}

/**
 * Empty state for filtered views. Grey Mountain icon at 32px, short line,
 * ghost clear-filter button. No gold — an empty view earns no accent.
 * @startingPoint section="Feedback" subtitle="Filtered view with no results" viewport="480x260"
 */
export function EmptyState(props: EmptyStateProps): JSX.Element;
