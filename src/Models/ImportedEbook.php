<?php

namespace Knowfox\Core\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedEbook extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'items';
}
