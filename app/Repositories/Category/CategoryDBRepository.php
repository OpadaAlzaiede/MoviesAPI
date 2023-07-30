<?php
namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryDBRepository implements CategoryRepository {

    public function index($perPage, $page) {

        return QueryBuilder::for(Category::class)
            ->allowedIncludes(Category::allowedIncludes())
            ->allowedFilters(Category::allowedFilters())
            ->defaultSort('-id')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($slug) {

        $category = Category::where('slug', $slug)->first();

        if(!$category) throw new ModelNotFoundException('Category Not Found With Slug: ' . $slug);

        return $category->load(Category::allowedIncludes());
    }

    public function store($data) {

        $category = Category::create($data);

        return $category->load(Category::allowedIncludes());
    }

    public function update($data, $slug){

        $category = Category::where('slug', $slug)->first();

        if(!$category) throw new ModelNotFoundException('Category Not Found With Slug: ' . $slug);

        $category->update($data);

        return $category->load(Category::allowedIncludes());
    }

    public function destroy($slug) {

        $category = Category::where('slug', $slug)->first();

        if(!$category) throw new ModelNotFoundException('Category Not Found With Slug: ' . $slug);

        $category->delete();
    }
}


