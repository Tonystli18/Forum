<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];

    use RecordsActivity;

    public function favorited(){
        return $this->morphTo(__FUNCTION__, 'favorited_type', 'favorited_id');
    }
}
