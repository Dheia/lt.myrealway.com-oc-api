<?php namespace Qcsoft\App\Models;

use Illuminate\Database\Eloquent\Model;

class Aphone extends Model
{
    protected $table = 'qcsoft_app_aphone';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(Auser::class);
    }

}
