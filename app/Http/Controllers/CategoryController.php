<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\Uploader;
use App\Models\Attachment;
use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\QueryBuilder;


class CategoryController extends Controller
{
    use Uploader;

    protected  $categoryRepository;

    public function __construct(Request $request, CategoryRepository $categoryRepository)
    {
        $this->setConstruct($request, CategoryResource::class);
        $this->categoryRepository = $categoryRepository;
    }

    public function index() {

        $categories = $this->categoryRepository->index($this->perPage, $this->page);

        return $this->collection($categories);
    }

    public function show($slug) {

        $category = $this->categoryRepository->show($slug);

        return $this->resource($category);
    }

    public function store(StoreCategoryRequest $request) {

        $category = $this->categoryRepository->store($request->validated());

        return $this->resource($category);
    }

    public function update(UpdateCategoryRequest $request, $slug) {

        $category = $this->categoryRepository->update($request->validated(), $slug);

        return $this->resource($category);
    }

    public function destroy($slug) {

        $this->categoryRepository->destroy($slug);

        return $this->success([], 'Deleted Successfully !');
    }
}
