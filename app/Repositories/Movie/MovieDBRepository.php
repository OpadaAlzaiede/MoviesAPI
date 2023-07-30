<?php
namespace App\Repositories\Movie;

use App\Models\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\QueryBuilder;

class MovieDBRepository implements MovieRepository {

    public function index($perPage, $page) {

        return QueryBuilder::for(Movie::class)
                            ->allowedIncludes(Movie::allowedIncludes())
                            ->allowedFilters(Movie::allowedFilters())
                            ->defaultSort('-id')
                            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($slug) {

        $movie = Movie::where('slug', $slug)->first();

        if(!$movie) throw new ModelNotFoundException('Movie Not Found With Slug: ' . $slug);

        return $movie->load(Movie::allowedIncludes());
    }

    public function store($data) {

        $movie = Movie::create($data);

        return $movie->load(Movie::allowedIncludes());
    }

    public function update($data, $slug){

        $movie = Movie::where('slug', $slug)->first();

        if(!$movie) throw new ModelNotFoundException('Movie Not Found With Slug: ' . $slug);

        $movie->update($data);

        return $movie->load(Movie::allowedIncludes());
    }

    public function destroy($slug) {

        $movie = Movie::where('slug', $slug)->first();

        if(!$movie) throw new ModelNotFoundException('Movie Not Found With Slug: ' . $slug);

        $movie->delete();
    }
}


