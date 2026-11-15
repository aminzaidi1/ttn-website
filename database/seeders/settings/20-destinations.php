<?php

// [group, key, label, value, type?, help?]
return [
    // Destinations index
    ['Destinations page', 'destinations.title', 'Index: page heading', 'Destinations'],
    ['Destinations page', 'destinations.intro', 'Index: line under the heading', 'The valleys, lakes and passes worth the drive. Filter by region, then start planning.', 'textarea'],
    ['Destinations page', 'destinations.filter_all', 'Index: label of the first filter pill', 'All'],
    ['Destinations page', 'destinations.filter_label', 'Index: filter name read out by screen readers', 'Filter destinations by region'],
    ['Destinations page', 'destinations.explore_link', 'Index: link under each destination', 'Explore :name', 'text', ':name is replaced by the destination name.'],
    ['Destinations page', 'destinations.empty', 'Index: message when a region has no destinations', 'Nothing here yet. The road is still being built.'],
    ['Destinations page', 'destinations.empty_action', 'Index: button that clears the region filter', 'Clear filter'],
    ['Destinations page', 'destinations.seo_description', 'Index: description for Google and social sharing', 'The valleys, lakes and passes of northern Pakistan worth the drive, with seasons, altitudes and drive times from the people who have driven them.', 'textarea'],

    // Destination detail
    ['Destinations page', 'destination.subnav_label', 'Detail: name of the section menu for screen readers', 'On this page'],
    ['Destinations page', 'destination.overview_heading', 'Detail: overview heading', 'Overview'],
    ['Destinations page', 'destination.getting_there_heading', 'Detail: getting there heading', 'Getting there'],
    ['Destinations page', 'destination.stay_heading', 'Detail: where to stay heading', 'Where to stay'],
    ['Destinations page', 'destination.todo_heading', 'Detail: what to do heading', 'What to do'],
    ['Destinations page', 'destination.tips_eyebrow', 'Detail: small label above community tips', 'From the group'],
    ['Destinations page', 'destination.tips_heading', 'Detail: community tips heading', 'Community tips'],
    ['Destinations page', 'destination.partners_heading', 'Detail: certified partners heading', 'TTN Certified in :region', 'text', ':region is replaced by the region name.'],
    ['Destinations page', 'destination.partner_cta', 'Detail: button on each partner card', 'View partner'],
    ['Destinations page', 'destination.related_heading', 'Detail: closing related destinations heading', 'Keep exploring'],
    ['Destinations page', 'destination.road_status_as_of', 'Detail: road status date line', 'Road status as of :date.', 'text', ':date is replaced by the date of the status.'],
    ['Destinations page', 'destination.road_status_note', 'Detail: road status standing line', 'Conditions change fast.'],
    ['Destinations page', 'destination.road_status_link', 'Detail: road status link to the Facebook group', 'Check the group before you drive.'],
    ['Destinations page', 'destination.map_route_from', 'Detail: map caption, before the starting point', 'route from'],
    ['Destinations page', 'destination.map_note', 'Detail: map caption ending', 'schematic, not to scale'],
    ['Destinations page', 'destination.photo_credit', 'Detail: photo credit when a photo has none', 'Photo: Waseem Photography'],
];
