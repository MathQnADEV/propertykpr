<?php
// Tiru query controller listings view=all: House::with(...)->latest()->paginate(9)
$listings = \App\Models\House::with(['category', 'city', 'photos', 'agent'])->latest()->paginate(9);
echo 'count on page: ' . $listings->count() . ' | total: ' . $listings->total() . PHP_EOL;
foreach ($listings as $i => $h) {
    echo $i . ' | id:' . $h->id
        . ' | ' . $h->name
        . ' | agent:' . ($h->agent->name ?? 'null')
        . ' | avail:' . $h->is_available
        . ' | thumb:' . ($h->thumbnail ? 'yes' : 'NULL')
        . PHP_EOL;
}
