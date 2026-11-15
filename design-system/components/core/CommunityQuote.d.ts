import * as React from 'react';

export interface CommunityQuoteProps {
  /** The post text, set in Playfair italic */
  quote: string;
  /** Member name; rendered as "{name}, To The North Community" */
  name: string;
  /** Optional image shown full-width above the text */
  image?: string;
  style?: React.CSSProperties;
}

/**
 * Featured Facebook community post, quote-styled with a gold left border.
 * @startingPoint section="Cards" subtitle="Featured community post" viewport="420x240"
 */
export function CommunityQuote(props: CommunityQuoteProps): JSX.Element;
