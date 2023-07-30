<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Rate;
use Carbon\Carbon;

class CustomRatingService implements MovieRateService {



    public function rate($movie, $user, $rate)
    {
        $oldRate = $movie->rates()->where('user_id', $user->id)->first();

        if($oldRate)
        {
            $oldRate->rate = $rate;
            $oldRate->date = Carbon::now();
        }
        else {
            Rate::create([
                'movie_id' => $movie->id,
                'user_id' => $user->id,
                'rate' => $rate,
                'date' => Carbon::now()
            ]);
        }

        return $movie->load(Movie::allowedIncludes());
    }
}
