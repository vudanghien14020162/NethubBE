<?php


namespace App\Models;

class HomeSlider extends BaseModel
{
    protected $table    = 'home_sliders';
    const TYPE_LIVE     = 1;
    const TYPE_VOD      = 2;
    const TYPE_PAY      = 3;
    const TYPE_EPG      = 4;
    const TYPE_EVENT    = 5;

    public function movie(){
        return $this->belongsTo(Movie::class, 'content_id', 'id');
    }
    public function epg(){
        return $this->belongsTo(EpgData::class, 'content_id', 'id');
    }
    public function event(){
        return $this->belongsTo(Event::class, 'content_id', 'id');
    }
}
