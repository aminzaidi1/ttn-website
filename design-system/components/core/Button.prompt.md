Primary action button for To The North. Use the gold `primary` once per section; `secondary` (ghost) and `text` for supporting actions.

```jsx
<Button variant="primary" iconRight={<ArrowRight size={16} />}>Read the story</Button>
<Button variant="secondary">Contact partner</Button>
<Button variant="text">View all destinations</Button>
```

Variants: `primary` (gold fill, hover → amber), `secondary` (gold ghost, hover → gold fill), `text` (gold link, underline on hover). Sizes: `sm` / `md` / `lg`. Pass `href` to render an anchor.
