<?php


namespace App\Models;


class RelationMenuGenre extends BaseModel
{
    protected $table = 'avg_relations_menu_genre';
    public function genre(){
        return $this->hasOne(Genre::class, 'genre_id', 'id');
    }
    public function menu(){
        return $this->hasOne(Menu::class, 'menu_id', 'id');
    }
}
