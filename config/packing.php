<?php

return [
    'line_locations' => collect(explode(',', env('PACKING_LINE_LOCATION_IDS', '')))
        ->filter(fn ($value) => $value !== '')
        ->map(fn ($value) => (int) trim($value))
        ->toArray(),
];
