<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use  SoftDeletes, HasAccountScope;

    protected $table = 'tests';


    protected $fillable = [
        'name',
        "app_id",
        'status',
        'url',
        "curl",
        "frequency_amount",
        "frequency",
        "expected_status_code",
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'id' => "integer",
        'app_id' => "integer",
        //'active' => "integer",
        //'status' => 'boolean',
    ];

    public function latest()
    {
        return $this->hasOne(TestLog::class, 'test_id', 'id')->where("latest", "=", 1);
    }

    public function users()
    {
       return $this->belongsToMany(User::class, "test_users", "test_id", "user_id");
    }
}
