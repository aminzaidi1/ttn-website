import * as React from 'react';

export interface TagProps extends React.HTMLAttributes<HTMLSpanElement> {
  /** category = neutral slate pill, region = gold fill, certified = gold-bordered uppercase, gold = bare gold label */
  variant?: 'category' | 'region' | 'certified' | 'gold';
  icon?: React.ReactNode;
  children?: React.ReactNode;
}

/**
 * Pills, region tags and the TTN Certified badge.
 * @startingPoint section="Core" subtitle="Category, region and certified tags" viewport="700x140"
 */
export function Tag(props: TagProps): JSX.Element;
