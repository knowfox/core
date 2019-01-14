<?php

namespace Knowfox\Core\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Knowfox\Core\Models\Concept;

use Carbon\Carbon;

class JournalController extends Controller
{
    public function date($date_string = null)
    {
        try {
            $concept = Concept::journal($date_string);
        }
        catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('concept.show', [$concept]);
    }
}
