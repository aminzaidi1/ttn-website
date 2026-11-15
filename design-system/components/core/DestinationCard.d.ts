import * as React from 'react';

export interface DestinationCardProps {
  image: string;
  name: string;
  /** Gold region tag, top-right */
  region?: string;
  href?: string;
  height?: number | string;
  onClick?: React.MouseEventHandler;
  style?: React.CSSProperties;
}

/**
 * Full-bleed destination card with overlay. Photography dominates.
 * @startingPoint section="Cards" subtitle="Full-bleed destination card" viewport="420x360"
 */
export function DestinationCard(props: DestinationCardProps): JSX.Element;
