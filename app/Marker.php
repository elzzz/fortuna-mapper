<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    // Table Name
    protected $table = 'markers';

    // PK
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\User');
    }
}
