<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\DeleteMovieRequest;
use App\Http\Requests\Movie\RateMovieRequest;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Rate;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class MovieController extends Controller
{
    public function __construct(Request $request)
    {
        $this->setConstruct($request, MovieResource::class);
    }

    public function index() {

        $movies = QueryBuilder::for(Movie::class)
                            ->allowedIncludes(Movie::allowedIncludes())
                            ->allowedFilters(Movie::allowedFilters())
                            ->defaultSort('-id')
                            ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($movies);
    }

    public function show($id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        return $movie->load(Movie::allowedIncludes());
    }

    public function store(StoreMovieRequest $request) {

        $movie = Movie::create($request->validated());

        return $this->resource($movie->load(Movie::allowedIncludes()));
    }

    public function update(UpdateMovieRequest $request, $id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        $movie->update($request->validated());

        return $movie->load(Movie::allowedIncludes());
    }

    public function destroy(DeleteMovieRequest $request, $id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        $movie->delete();

        return $this->success([], 'Deleted Successfully !');
    }

    public function rate(RateMovieRequest $request, Movie $movie, Rate $rate) {

        $rate->fill($request->validated());
        $rate->date = Carbon::now();
        $rate->ip = $request->ip();
        $rate->movie_id = $movie->id;
        $rate->save();

        $movie->calcNewRate($rate->rate);

        return $this->resource($movie->load(Movie::allowedIncludes()));
    }
}
