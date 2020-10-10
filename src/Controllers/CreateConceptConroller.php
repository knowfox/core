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

use Knowfox\Core\Models\Concept;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Knowfox\Core\Requests\ConceptRequest;

class CreateConceptController extends Controller
{
    private function registerObserver($type)
    {
        if ($type != 'concept') {
            $observer = "\\Knowfox\\Core\\Observers\\" . ucfirst($type) . "Observer";
            if (class_exists($observer)) {
                Concept::observe($observer);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ConceptRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConceptRequest $request)
    {
        $this->registerObserver($request->input('type'));

        $concept = new Concept($request->all());

        $concept->owner_id = $request->user()->id;

        $concept->is_flagged = $request->has('is_flagged');

        DB::transaction(function () use ($concept, $request) {
            $concept->save();

            if ($request->has('tags')) {
                $concept->tag($request->input('tags'));
            }
            else {
                $concept->untag();
            }
        });

        return response([
            'concept' => new ConceptResource($concept),
            'status' => 'Concept created',
        ]);
    }
}
