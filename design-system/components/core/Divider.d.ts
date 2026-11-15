import * as React from 'react';

export interface DividerProps {
  /** Optional centred uppercase label between two rules */
  label?: string;
  style?: React.CSSProperties;
}

/** Thin slate rule, or a labelled section divider. */
export function Divider(props: DividerProps): JSX.Element;
