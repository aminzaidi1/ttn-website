import * as React from 'react';

export interface ButtonProps extends React.HTMLAttributes<HTMLElement> {
  /** Visual style. primary = gold fill, secondary = gold ghost, text = gold link */
  variant?: 'primary' | 'secondary' | 'text';
  size?: 'sm' | 'md' | 'lg';
  /** Renders as an anchor when provided */
  href?: string;
  /** Leading icon node (Lucide recommended) */
  icon?: React.ReactNode;
  /** Trailing icon node, e.g. ArrowRight */
  iconRight?: React.ReactNode;
  disabled?: boolean;
  children?: React.ReactNode;
}

/**
 * To The North primary action. Gold is rare — one primary per section.
 * @startingPoint section="Core" subtitle="Primary, ghost and text buttons" viewport="700x160"
 */
export function Button(props: ButtonProps): JSX.Element;
