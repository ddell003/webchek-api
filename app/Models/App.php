<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
    use  SoftDeletes, HasAccountScope;

    protected $table = 'apps';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'id' => "integer",
        //'status' => 'integer',
    ];

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
