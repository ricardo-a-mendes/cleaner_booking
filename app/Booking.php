<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'customer_id', 'cleaner_id'];

    public function cleaner()
    {
        return $this->belongsTo(Cleaner::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFormatedDateAttribute()
    {
        $formatedDate = Carbon::parse($this->date);
        return $formatedDate->format('m-d-Y H:i');
    }
}
