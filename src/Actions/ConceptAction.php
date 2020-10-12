<?php

namespace Knowfox\Core\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Knowfox\Core\Models\Concept;

class ConceptAction
{
    public function index(Request $request, $special)
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
        return $concepts;
    }

    function postProcess(Request $request, $concepts) 
    {
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

        return $items;
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

        return [
            'concept' => $concept,
            'children' => $children->paginate(),
        ];
    }
}