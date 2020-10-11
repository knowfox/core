<?php
/**
 * Knowfox - Personal Knowledge Management
 * Copyright (C) 2017 .. 2019  Olav Schettler
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Knowfox\Core\Controllers;

use Illuminate\Http\Request;
use Knowfox\Core\Models\Concept;
use App\Http\Controllers\Controller;
use Knowfox\Core\Resources\Concept as ConceptResource;
use Illuminate\Support\Facades\Auth;
use Knowfox\Core\Services\PictureService;
use Knowfox\Crud\Services\Crud;

class ConceptController extends Controller
{
    protected $crud;

    public function __construct(Crud $crud)
    {
        $this->crud = $crud;
        $this->crud->setup('knowfox.concept');
    }

    public function toplevel(Request $request)
    {
        return $this->index($request, 'toplevel');
    }

    public function flagged(Request $request)
    {
        return $this->index($request, 'flagged');
    }

    public function popular(Request $request)
    {
        return $this->index($request, 'popular');
    }

    public function shares(Request $request)
    {
        return $this->index($request, 'shares');
    }

    public function shared(Request $request)
    {
        return $this->index($request, 'shared');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $special = false)
    {
        if ($request->has('concept_id') && $request->concept_id) {
            // 'Concepts in #' . $root->id . ', ' . $root->title;

            $root = Concept::findOrFail($request->concept_id);
            $concepts = $root->descendants()
                ->withDepth()
                ->with('tagged');
        }
        else {
            // 'Concepts';

            $concepts = Concept::withDepth()
                ->with('tagged');
        }

        if ($special) {
            switch ($special) {
                case 'flagged':
                    // 'Flagged concepts';
                    $concepts->where('is_flagged', 1);
                    break;
                case 'toplevel':
                    // 'Toplevel concepts';
                    $concepts->whereIsRoot();
                    break;
                case 'popular':
                    // 'Popular concepts';
                    $concepts->orderBy('viewed_count', 'desc');
                    break;
                case 'shares':
                    // 'Concepts shared by me';
                    $concepts->has('shares');
                    break;
                case 'shared':
                    // 'Concepts shared with me';
                    $concepts->whereHas('shares', function ($query) {
                        $query->where('users.id', Auth::id());
                    });
                    break;
            }
        }

        $concepts
            ->orderBy('viewed_at', "desc")
            ->orderBy('updated_at', "desc");

        if (!$special || $special != 'shared') {
            $concepts->where('owner_id', Auth::id());
        }

        if ($request->has('tag')) {
            $concepts->withAllTags([$request->input('tag')]);
            // .= ' with tag "' . $request->input('tag') . '"';
        }

        $search_term = '';

        // https://dev.mysql.com/doc/refman/5.7/en/fulltext-query-expansion.html

        if ($request->has('q')) {
            $search_term = $request->input('q');
            $concepts->whereRaw(
                'MATCH(title,summary,body) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term]
            );
        }

        // jquery-ui.autocomplete
        if ($request->has('term')) {
            $search_term = $request->input('term');
            $concepts->whereRaw(
                'MATCH(title,summary,body) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term]
            );
        }

        if ($request->has('except')) {
            $concepts->where('id', '!=', $request->input('except'));
        }

        if ($request->has('limit')) {
            $concepts->limit((int)$request->input('limit'));
        }

        $items = $concepts
            ->select('id', 'title', 'parent_id', '_lft', '_rgt')
            ->with('ancestors')
            ->paginate()
            ->appends($request->except(['page']));

        $items->each(function (Concept $item, $key) {
            $item->path = $item->ancestors->count()
                ? ('/' . implode('/', $item->ancestors->pluck('title')->toArray()))
                : '';
            $item->path .= '/' . $item->title;
        });

        if ($request->wantsJson()) {
            return response()->json($items);
        }
        else {
            return $this->crud->index($request);
        }
    }

    public function show(Concept $concept, Request $request)
    {
        /*
        \DB::listen(function($sql) {
            error_log(json_encode($sql). "\n", 3, "/tmp/knowfox.log");
        });
        */

        //$this->authorize('view', $concept);
        //@todo Model binding does not work
        //$concept = Concept::findOrFail($request->concept);

        $concept->load('related', 'ancestors', 'inverseRelated', 'tagged', 'shares');

        $children = $concept->children();
        if (!empty($this->config->sort)) {
            switch ($this->config->sort) {
                case 'alpha':
                    $children->orderBy('title', 'asc');
                    break;
                case 'created':
                    $children->orderBy('created_at', 'desc');
                    break;
                default:
                    $children->defaultOrder();
            }
        }

        if ($request->has('tag')) {
            $children->withAllTags([$request->input('tag')]);
        }

        if ($request->has('q')) {
            $search_term = $request->input('q');
            $children->whereRaw(
                'MATCH(title,summary,body) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term]
            );
        }

        return response([
            'concept' => new ConceptResource($concept),
            'children' => $children->paginate(),
        ]);
    }

    public function journal($date_string, Request $request)
    {
        try {
            $concept = Concept::createJournal($date_string);
        }
        catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return $this->show($concept, $request);
    }
}

