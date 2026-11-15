Newsletter email capture. Place it in three spots: the homepage `embedded` section, the `footer` strip, and the `inline` end-of-post CTA (thin gold top border). Attached input + gold button on desktop; built-in success and error states.

```jsx
<NewsletterSignup variant="embedded" />
<NewsletterSignup variant="footer" />
<NewsletterSignup variant="inline" />
```

Success replaces the field with a gold check and "You're in. See you in the north." Error turns the border red with a message below.
