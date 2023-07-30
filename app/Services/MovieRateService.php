<?php
namespace App\Services;


interface MovieRateService {

    public function rate($movie, $user, $rate);
}
