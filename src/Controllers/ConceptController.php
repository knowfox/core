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
use Knowfox\Core\Requests\ConceptRequest;
use Knowfox\Core\Actions\ConceptAction;
use Illuminate\Support\Facades\Auth;
use Knowfox\Core\Services\PictureService;

class ConceptController extends Controller
{
    protected $action;

    public function __construct(ConceptAction $action)
    {
        $this->action = $action;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $special = false)
    {
        return response()->json($this->action->index($request, $special)->paginate());
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

