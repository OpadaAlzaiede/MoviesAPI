<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\RateMovieRequest;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Rate;
use App\Repositories\Movie\MovieRepository;
use Carbon\Carbon;

class MovieController extends Controller
{
    protected $movieRepository;

    public function __construct(Request $request, MovieRepository $movieRepository)
    {
        $this->setConstruct($request, MovieResource::class);
        $this->movieRepository = $movieRepository;
    }

    public function index() {

        $movies = $this->movieRepository->index($this->perPage, $this->page);

        return $this->collection($movies);
    }

    public function show($slug) {

        try {
            $movie = $this->movieRepository->show($slug);

            return $this->resource($movie);
        }
        catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(StoreMovieRequest $request) {

        $movie = $this->movieRepository->store($request->validated());

        return $this->resource($movie);
    }

    public function update(UpdateMovieRequest $request, $slug) {

        $movie = $this->movieRepository->update($request->validated(), $slug);

        return $this->resource($movie);

    }

    public function destroy($slug) {

        $this->movieRepository->destroy($slug);

        return $this->success([], 'Deleted Successfully !');
    }

    public function rate(RateMovieRequest $request, Movie $movie, Rate $rate) {

        $rate->fill($request->validated());
        $rate->ip = $request->ip();
        $rate->movie_id = $movie->id;
        $rate->save();

        $movie->calcNewRate($rate->rate);

        return $this->resource($movie->load(Movie::allowedIncludes()));
    }
}
