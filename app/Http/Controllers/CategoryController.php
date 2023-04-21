<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\QueryBuilder;


class CategoryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->setConstruct($request, CategoryResource::class);
    }

    public function index() {

        $categories =QueryBuilder::for(Category::class)
                                ->allowedIncludes(['movies'])
                                ->allowedFilters(['name'])
                                ->defaultSort('-id')
                                ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($categories);
    }

    public function show($id) {

        $category = Category::find($id);

        if(!$category) return $this->error(404, 'Not Found !');

        return $this->resource($category->load(Category::getIncludes()));
    }

    public function store(StoreCategoryRequest $request) {

        $category = Category::create($request->validated());

        return $this->resource($category);
    }

    public function update(UpdateCategoryRequest $request, $id) {

        $this->authorize('update', Category::class);

        $category = Category::find($id);

        if(!$category) return $this->error(404, 'Not Found !');

        $category->update($request->validated());

        return $this->resource($category->load(Category::getIncludes()));
    }

    public function destroy(DeleteCategoryRequest $request, $id) {

        $category = Category::find($id);

        if(!$category) return $this->error(404, 'Not Found !');

        if(count($category->movies()->get())) {

            return $this->error(401, 'This Category Has Related Movies, Please Delete Them First !');
        }

        $category->delete();

        return $this->success([], 'Deletd Successfully !');
    }
}
