<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
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
                            ->allowedIncludes(['category'])
                            ->allowedFilters(['name', 'category.name', AllowedFilter::exact('rate')])
                            ->defaultSort('-id')
                            ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($movies);
    }

    public function show($id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        return $movie->load('category');
    }

    public function store(StoreMovieRequest $request) {

        $movie = Movie::create($request->validated());

        return $this->resource($movie->load('category'));
    }

    public function update(UpdateMovieRequest $request, $id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        $movie->update($request->validated());

        return $movie->load('category');
    }

    public function destroy($id) {

        $movie = Movie::find($id);

        if(!$movie) return $this->error(404, 'Not Found !');

        $movie->delete();

        return $this->success([], 'Deleted Successfully !');
    }
}
