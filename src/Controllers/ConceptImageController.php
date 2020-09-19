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

use Illuminate\Support\Facades\Auth;
use Knowfox\Core\Models\Concept;
use Illuminate\Http\Request;
use Knowfox\Core\Services\PictureService;
use Symfony\Component\HttpFoundation\File\File;

class ConceptImageController extends Controller
{
    public function image(PictureService $picture, Request $request, Concept $concept, $filename)
    {
        /*
        $this->middleware('auth:api');
        $this->authorize('view', $concept);
        */

        $args = [];
        if ($request->has('style')) {
            $style = $request->input('style');
        }
        else
        if ($request->has('width')) {
            $style = 'width';
            $args[] = $request->input('width');
        }
        else {
            $style = 'original';
        }
        return $picture->image($concept->uuid, $filename, $style, $args);
    }

    public function upload(PictureService $picture, Request $request, $uuid)
    {
        $concept = Concept::where('uuid', $uuid)->firstOrFail();
        $this->authorize('update', $concept);

        $path = $picture->upload($request->file('file'), $uuid);

        $file = new File($path);
        if (strpos($file->getMimeType(), 'image/') === 0) {
            $parts = pathinfo($path);

            if (strpos($concept->body, $parts['basename']) === false) {
                $concept->body .= "\n\n<a data-featherlight=\"{$parts['basename']}\">![{$parts['filename']}]({$parts['basename']}?style=square)</a>\n";
                $concept->save();
            }
        }

        return response()->json(['success' => $path]);
    }
}