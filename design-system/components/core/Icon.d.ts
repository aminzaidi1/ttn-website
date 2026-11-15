import * as React from 'react';

export type IconName =
  | 'mapPin' | 'mountain' | 'camera' | 'users' | 'star' | 'arrowRight'
  | 'menu' | 'x' | 'chevronDown' | 'externalLink' | 'phone' | 'mail'
  | 'check' | 'facebook' | 'instagram' | 'youtube' | 'tiktok' | 'clock'
  | 'calendar' | 'car' | 'gauge' | 'fileCheck'
  | 'phone' | 'mail' | 'bed' | 'utensils' | 'externalLink' | 'search';

export interface IconProps extends React.SVGAttributes<SVGElement> {
  name: IconName;
  /** px, default 20. Use 16 inline, 20 standalone UI, 24 feature. */
  size?: number;
  color?: string;
  strokeWidth?: number;
}

/** Lucide-style line icons. Grey for UI, gold for accents. */
export function Icon(props: IconProps): JSX.Element;
