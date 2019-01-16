<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('core::welcome');
});

Route::get('cancel/{what}/{email}', [
    'as' => 'cancel',
    'uses' => 'UserController@cancel',
]);

Route::get('/home', [
    'as' => 'home',
    'uses' => 'HomeController@index'
]);

Route::get('/book', [
    'as' => 'book.find',
    'uses' => 'BookController@find',
]);

Route::post('/book', [
    'as' => 'book.save',
    'uses' => 'BookController@save',
]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/tags', [
        'as' => 'tags.index',
        'uses' => 'TagsController@index',
    ]);

    Route::get('/tagcloud', [
        'as' => 'tags.cloud',
        'uses' => 'TagsController@cloud',
    ]);

    Route::get('/concepts/toplevel', [
        'as' => 'concept.toplevel',
        'uses' => 'ConceptController@toplevel',
    ]);

    Route::get('/concepts/popular', [
        'as' => 'concept.popular',
        'uses' => 'ConceptController@popular',
    ]);

    Route::get('/concepts/flagged', [
        'as' => 'concept.flagged',
        'uses' => 'ConceptController@flagged',
    ]);

    Route::get('/concepts/shares', [
        'as' => 'concept.shares',
        'uses' => 'ConceptController@shares',
    ]);

    Route::get('/concepts/shared', [
        'as' => 'concept.shared',
        'uses' => 'ConceptController@shared',
    ]);

    Route::get('/concepts', [
        'as' => 'concept.index',
        'uses' => 'ConceptController@index',
    ]);

    Route::get('/concept/create', [
        'as' => 'concept.create',
        'uses' => 'ConceptController@create',
    ]);

    Route::post('/concept/store', [
        'as' => 'concept.store',
        'uses' => 'ConceptController@store',
    ]);

    // Route::resource('api/concept', 'ApiController');

    Route::get('/{concept}', function ($concept) {
        return redirect()->route('concept.show', [$concept]);
    })->where('concept', '[0-9]+')->name('concept.short');

    Route::get('/uuid/{uuid}', [
        'as' => 'concept.uuid',
        'uses' => 'ConceptController@uuid',
    ]);

    Route::get('/uuid/{uuid}/image', [
        'as' => 'concept.uuid-image',
        'uses' => 'ConceptController@uuidImage',
    ]);

    Route::get('/concept/{concept}', function ($concept) {
        return redirect()->route('concept.show', [$concept]);
    })->where('concept', '[0-9]+');

    // So that images without a path work
    Route::get('/{concept}/view', [
        'as' => 'concept.show',
        'uses' => 'ConceptController@show',
    ])->where('concept', '[0-9]+');

    Route::get('/{concept}/slides', [
        'as' => 'concept.slides',
        'uses' => 'ConceptController@slides',
    ])->where('concept', '[0-9]+');

    Route::delete('/concept/{concept}', [
        'as' => 'concept.destroy',
        'uses' => 'ConceptController@destroy',
    ]);

    Route::post('/concept/{concept}', [
        'as' => 'concept.update',
        'uses' => 'ConceptController@update',
    ]);

    Route::get('/{concept}/outline', [
        'as' => 'concept.outline',
        'uses' => 'OutlineController@outline',
    ])->where('concept', '[0-9]+');

    Route::get('/{concept}/reader', [
        'as' => 'book.reader',
        'uses' => 'BookController@reader',
    ])->where('concept', '[0-9]+');

    Route::get('/{concept}/versions', [
        'as' => 'concept.versions',
        'uses' => 'ConceptController@versions',
    ])->where('concept', '[0-9]+');

    Route::get('{concept}/attachments', [
        'as' => 'attachment.index',
        'uses' => 'AttachmentController@index'
    ])->where('concept', '[0-9]+');

    Route::resource('attachment', 'AttachmentController', ['except' => [
        'index'
    ]]);

    Route::get('/{concept}/{filename}', [
        'as' => 'concept.image',
        'uses' => 'ConceptController@image',
    ])->where('concept', '[0-9]+');

    Route::post('/upload/{uuid}', [
        'as' => 'concept.upload',
        'uses' => 'ConceptController@upload',
    ]);

    Route::get('/images/{concept}', [
        'as' => 'concept.images',
        'uses' => 'ConceptController@images',
    ]);

    Route::get('/opml/{concept}', [
        'as' => 'outline.opml',
        'uses' => 'OutlineController@opml',
    ]);

    Route::get('/json', [
        'as' => 'outline.json',
        'uses' => 'OutlineController@json',
    ]);

    Route::post('/json', [
        'as' => 'outline.updateJson',
        'uses' => 'OutlineController@updateJson',
    ]);

    Route::post('/opml/{concept}', [
        'as' => 'outline.update',
        'uses' => 'OutlineController@update',
    ]);

    Route::get('/bookmark', [
        'as' => 'bookmark.create',
        'uses' => 'BookmarkController@create',
    ]);

    Route::post('/bookmark', [
        'as' => 'bookmark.store',
        'uses' => 'BookmarkController@store',
    ]);

    Route::get('/{date}', function ($date) {
        return redirect()->route('journal', [$date]);
    })->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}');

    Route::get('/journal/{date?}', [
        'as' => 'journal',
        'uses' => JournalController::class . '@date',
    ]);

    Route::post('/share/{concept}', [
        'as' => 'share',
        'uses' => 'ShareController@update',
    ]);

    Route::post('/publish/{concept}', [
        'as' => 'website.publish',
        'uses' => 'WebsiteController@publish',
    ]);

    Route::get('/emails', [
        'as' => 'emails',
        'uses' => 'ShareController@emails',
    ]);

    Route::get('/token', [
        'as' => 'user.token',
        'uses' => 'UserController@token',
    ]);

    Route::get('/passport', [
        'as' => 'user.passport',
        'uses' => 'UserController@passport',
    ]);

    Route::get('/items', [
        'as' => 'item.index',
        'uses' => 'ItemController@index',
    ]);

    Route::get('/todo', [
        'as' => 'item.todo',
        'uses' => 'ItemController@todo',
    ]);

    Route::get('/done', [
        'as' => 'item.done',
        'uses' => 'ItemController@done',
    ]);

    Route::get('/ui', [
        'as' => 'ui.index',
        'uses' => 'UiController@index',
    ]);
});
