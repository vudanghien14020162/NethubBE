<?php


namespace App\Models\HelpersModel;
use App\Helpers\ConstResponse;
use App\Helpers\HelperChangeTime;
use App\Models\Movie;
use App\Models\WatchHistory;

class WatchHistoryHelper extends BaseHelper
{
    const STATUS_ACTIVE = 1;
    const NOT_DELETED   = 0;
    const FINISH_WATCHING = 1;
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

    public static function updateWatchHistory($movie, $user, $watch_duration = 0){
        if(isset($movie->live) && $movie->live){
            //return true;
        }
        $duration_mms = !empty($movie->duration)
            ? HelperChangeTime::helperChangeTime((string)$movie->duration) : 0;
        $history = WatchHistory::where(array('movie_id' => $movie->id, 'user_id' => $user->id))->first();
        //calculate watching percent
        $watch_percent = 0;
        $finish_watching = 0;
        if($duration_mms > 0){
            $watch_duration = min(100, round(($watch_duration / $duration_mms) * 100, 2));
            if($watch_percent > ConstResponse::$watched_percent){
                $finish_watching = self::FINISH_WATCHING;
            }
        }
        if($history){
            if($watch_duration > 0){
                WatchHistory::query()
                    ->where('id', $history->id)->update(
                        array(
                            'duration' => $duration_mms,
                            'watch_duration' => $watch_duration,
                            'watching_percent' => $watch_percent,
                            'finish_watching' => $finish_watching,
                            'deleted' => 0,
                            'updated_at' => date("Y-m-d H:i:s")
                        )
                    );
            }
        }else{
            $ins_data = array(
                'movie_id' => $movie->id,
                'user_id' => $user->id,
                'duration' => $duration_mms,
                'watch_duration' => $watch_duration,
                'watching_percent' => $watch_percent,
                'finish_watching' => $finish_watching,
                'deleted' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date('Y-m-d H:i:s')
            );
            WatchHistory::insert($ins_data);
        }
        return true;
    }
}
