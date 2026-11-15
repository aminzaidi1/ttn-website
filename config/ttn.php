<?php

return [
    // Full-page cache for public pages. Turn off while developing templates.
    'page_cache' => env('TTN_PAGE_CACHE', false),

    // The styleguide is a working reference, not a public page.
    'styleguide' => env('TTN_STYLEGUIDE', true),
];
