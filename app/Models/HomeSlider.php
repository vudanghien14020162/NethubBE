<?php


namespace App\Models;


class HomeSlider extends BaseModel
{
    const TYPE_LIVE = 1;
    const TYPE_VOD = 2;
    const TYPE_PAY = 3;
    const TYPE_EPG = 4;
    const TYPE_EVENT = 5;
    protected $table = 'home_sliders';
}
