<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SelectableTrait;

class College extends Model
{
    use SelectableTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'colleges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'initials', 'location_city_id'];

    public function city(){
        return $this->belongsTo('App\Models\LocationCity', 'location_city_id');
    }

    public function courses()
    {
        return $this->hasMany('App\Models\Course');
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function setInitialsAttribute($value)
    {
        return $this->attributes['initials'] = strtoupper($value);
    }
}
