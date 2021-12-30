<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Models\Movie;
use App\Models\WatchHistory;

class WatchHistoryHelper extends BaseHelper
{
    const STATUS_ACTIVE = 1;
    const NOT_DELETED   = 0;
    public static function userWatchingMovies($user_id, $offset=0, $limit=10){
        $data = WatchHistory::query()
            ->select('movies.*', 'watch_histories.watch_duration', 'watch_histories.watching_percent', 'watch_histories.finish_watching')
            ->join('movies', 'watch_histories.movie_id', '=', 'movies.id')
            ->where([
                'watch_histories.user_id' => $user_id,
                'movies.status' => Movie::STATUS_ACTIVE,
                'movies.live' => 0,
                'watch_histories.deleted' => 0
            ])
            ->where('watch_histories.watching_percent', "<=", ConstResponse::$watched_percent)
            ->where('watch_histories.watch_duration', '>', 0)
            ->orderBy('watch_histories.updated_at', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $data;
    }

    public static function checkWatchHistory($user_id){
        $check = WatchHistory::query()
            ->select('movies.*', 'watch_histories.watch_duration',
                'watch_histories.watching_percent', 'watch_histories.finish_watching')
            ->join('movies', 'watch_histories.movie_id', '=', 'movies.id')
            ->where([
                'watch_histories.user_id' => $user_id,
                'movies.status' => Movie::STATUS_ACTIVE,
                'movies.live' => 0,
                'watch_histories.deleted' => Movie::NOT_DELETED
            ])
            ->where('watch_histories.watching_percent', '<=', ConstResponse::$watched_percent)
            ->where('watch_histories.watch_duration', '>', 0)
            ->orderBy('watch_histories.updated_at', 'desc')
            ->first();
        return $check;
    }
}
