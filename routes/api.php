<?php
Route::get('concept/toplevel', [
    'as' => 'concept.toplevel',
    'uses' => 'ConceptController@toplevel',
]);

Route::get('concept/popular', [
    'as' => 'concept.popular',
    'uses' => 'ConceptController@popular',
]);

Route::get('concept/flagged', [
    'as' => 'concept.flagged',
    'uses' => 'ConceptController@flagged',
]);

Route::get('concept/shares', [
    'as' => 'concept.shares',
    'uses' => 'ConceptController@shares',
]);

Route::get('concept/shared', [
    'as' => 'concept.shared',
    'uses' => 'ConceptController@shared',
]);

Route::get('image/{concept}/{filename}', [
    'as' => 'concept.image',
    'uses' => 'ConceptImageController@image',
])->where('concept', '[0-9]+');

Route::get('journal/{date}', [
    'as' => 'concept.journal',
    'uses' => 'ConceptController@journal',
])->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');

Route::resource('concept', 'ConceptController')
    ->except([
        'create', 'store', 'edit', 'update', 'destroy'
    ]);

Route::resource('concept', 'CreateConceptController')
    ->only(['store']);

Route::resource('concept', 'UpdateConceptController')
    ->only(['update']);

Route::resource('concept', 'DeleteConceptController')
    ->only(['destroy']);

Route::get('tags', [
    'as' => 'tags.index',
    'uses' => 'TagsController@index',
]);

Route::get('config', [
    'as' => 'config.index',
    'uses' => 'ConfigController@index',
]);

Route::fallback(function(){
    return response()->json([
        'message' => 'Page not found. If error persists, contact info@knowfox.com'], 404);
});

