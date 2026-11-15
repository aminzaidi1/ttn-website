import * as React from 'react';

export interface StoryCardProps {
  image: string;
  /** Gold category label above the title */
  tag?: string;
  title: string;
  excerpt?: string;
  author?: string;
  date?: string;
  readTime?: string;
  href?: string;
  style?: React.CSSProperties;
}

/**
 * Editorial blog/story card. 16:9 image, gold tag, Playfair title.
 * @startingPoint section="Cards" subtitle="Editorial story card" viewport="380x420"
 */
export function StoryCard(props: StoryCardProps): JSX.Element;
