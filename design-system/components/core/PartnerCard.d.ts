import * as React from 'react';

export interface PartnerCardProps {
  name: string;
  /** Category line in gold, e.g. "Tour Operator" */
  category?: string;
  location?: string;
  description?: string;
  ctaLabel?: string;
  href?: string;
  /** Click handler for the CTA (call e.preventDefault() when routing in-app) */
  onCta?: React.MouseEventHandler;
  style?: React.CSSProperties;
}

/**
 * TTN Certified partner card. Gold border = mark of quality.
 * @startingPoint section="Cards" subtitle="Gold-bordered certified partner" viewport="420x280"
 */
export function PartnerCard(props: PartnerCardProps): JSX.Element;
