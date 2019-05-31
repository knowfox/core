<?php

namespace Knowfox\Core\Listeners;

use Illuminate\Auth\Events\Registered;
use Knowfox\Core\Models\Concept;

class NewUserListener
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $owner_id = $event->user->id;
        
        $config = Concept::create([
            'title' => 'Configuration',
            'owner_id' => $owner_id,
            'config' => [],
        ]);
        $journal = Concept::create([
            'title' => 'Journal',
            'owner_id' => $owner_id,
        ]);
    }
}
