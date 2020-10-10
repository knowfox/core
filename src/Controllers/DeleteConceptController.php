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

class DeleteConceptController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Knowfox\Models\Concept  $concept
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concept $concept)
    {
        $this->authorize('delete', $concept);

        $title = "#{$concept->id} \"{$concept->title}\"";
        $parent_id = $concept->getParentId();

        $concept->delete();

        return response([
            'parent_id' => $parent_id ? $parent_id : null,
            'status' => 'Concept ' . $title . ' deleted',
        ]);
    }
}