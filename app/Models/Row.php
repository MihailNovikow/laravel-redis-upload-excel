<?php

namespace App\Models;

use App\Events\RowCreated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $external_id
 * @property mixed $name
 * @property Carbon $date
 */
class Row extends Model
{
    use HasFactory;
    protected $fillable = ['row_id', 'name', 'date'];
    protected $table = 'rows';
    protected $dateFormat = 'd.m.Y';
    public $timestamps = false;

    public function getDateAttribute($date = null): Carbon
    {
        return $date instanceof Carbon ? $date : Carbon::parse($date);
    }

    protected static function boot()
    {
        parent::boot();
    }

}
