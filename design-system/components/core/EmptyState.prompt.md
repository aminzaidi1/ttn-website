Empty state for any filtered view with no results. Centred grey Mountain icon (32px), one short line in the TTN voice, and a ghost button to clear the filter. Deliberately contains no gold: an empty view has not earned an accent.

Keep messages short and honest, never apologetic corporate voice.

```jsx
<EmptyState
  message="Nothing here yet. The road is still being built."
  actionLabel="Clear filter"
  onAction={() => setFilter('All')}
/>
```
