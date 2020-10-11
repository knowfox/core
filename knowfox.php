<?php

use Knowfox\Core\Models\Concept;

return [
    'relationships' => [],

    'languages' => [
        'de',
        'en',
    ],

    'types' => [
        'concept',
        'ebook',
        'book list',
        'journal',
        'impact map',
        'folder',
        'feed item',
        'website',
    ],

    'mercury_key' => env('MERCURY_KEY', 'secret key'),
    'presentation_base_path' => env('PRESENTATION_BASE', base_path()),

    'concept' => [
        'package' => 'core',
        'layout' => 'core::' . config('crud.theme') . '.layouts.app',
        'has_create' => true,
        'home_route' => 'concept.index',
        'model' => Concept::class,
        'order_by' => 'title',
        'entity_name' => 'concept',
        'entity_title' => ['s Concept', 'Concepts'],

        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
        ]
    ],
];
