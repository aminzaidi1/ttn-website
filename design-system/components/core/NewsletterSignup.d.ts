import * as React from 'react';

export interface NewsletterSignupProps {
  /** embedded = full homepage section, footer = compact strip, inline = bottom-of-post CTA */
  variant?: 'embedded' | 'footer' | 'inline';
  eyebrow?: string;
  headline?: string;
  subline?: string;
  buttonLabel?: string;
  style?: React.CSSProperties;
}

/**
 * Email capture in three placements: homepage section, footer strip, end-of-post CTA.
 * Handles success ("You're in. See you in the north.") and error states.
 * @startingPoint section="Sections" subtitle="Newsletter signup (3 placements)" viewport="700x320"
 */
export function NewsletterSignup(props: NewsletterSignupProps): JSX.Element;
