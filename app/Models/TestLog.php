<?php


namespace App\Models;

/*
 *  $table->foreignId('test_id')->constrained('tests');
            $table->string('status')->default("running"); //failed, passed
            $table->text('message')->nullable();
            $table->boolean('latest')->default(1);
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestLog extends Model
{
    use  SoftDeletes, HasAccountScope, HasFactory;

    protected $table = 'test_logs';

    protected $fillable = [
        'test_id',
        'status',
        'message',
        'latest',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'id' => "integer",
        'latest' => 'integer',
    ];

}
