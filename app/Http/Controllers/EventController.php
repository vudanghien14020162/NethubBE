<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\HelpersModel\EventHelper;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request){
        //Trả về danh sách event
        $datas = EventHelper::getFirstEvents();
    }

}
