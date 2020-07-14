<?php namespace Qcsoft\App\Models;

use Illuminate\Database\Eloquent\Model;

class Auser extends Model
{
    protected $table = 'qcsoft_app_auser';

    public $timestamps = false;

    public function aphone()
    {
        return $this->hasOne(Aphone::class);
    }

}
