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

use App\Http\Controllers\Controller;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Knowfox\Core\Models\Concept;
use Illuminate\Support\Facades\Config;

class ConfigController extends Controller
{
    private function mergeConfiguration($user_id)
    {
        $config = Concept::whereIsRoot()
            ->where('title', 'Configuration')
            ->where('owner_id', $user_id)
            ->first();

        if (!$config) {
            return;
        }

        foreach (config('knowfox') as $name => $value) {
            if (!empty($config->config->{$name})) {
                Config::set('knowfox.' . $name,
                    array_merge_recursive($config->config->{$name}, $value)
                );
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->mergeConfiguration($request->user()->id);
        return response()->json(config('knowfox'));
    }
}
