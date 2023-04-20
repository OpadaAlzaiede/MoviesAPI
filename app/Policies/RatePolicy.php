<?php

namespace App\Policies;

use App\Models\Rate;
use App\Models\Movie;
use Illuminate\Auth\Access\HandlesAuthorization;

class RatePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */

    public function rate(Movie $movie) {

        $ipAddress = request()->ip();

        return $this->checkForOldRate($ipAddress, $movie->id);
    }

    private function checkForOldRate($ipAddress, $movieId) {

        $oldRate = Rate::where([['movie_id', $movieId], ['ip', $ipAddress]])->first();

        return !is_null($oldRate);
    }
}
