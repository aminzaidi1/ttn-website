import * as React from 'react';

export interface CertificationBlockProps {
  /** e.g. "March 2022" */
  since?: string;
  /** 2–3 sentences, first person plural, written by the TTN team */
  verdict: string;
  /** Defaults to "The To The North team" */
  signature?: string;
  style?: React.CSSProperties;
}

/**
 * The signed TTN endorsement. Gold border on Deep; Playfair italic verdict.
 * This block IS the product of TTN Certified — one per page, and it is
 * that section's single gold element.
 * @startingPoint section="Cards" subtitle="Signed certification verdict" viewport="640x300"
 */
export function CertificationBlock(props: CertificationBlockProps): JSX.Element;
