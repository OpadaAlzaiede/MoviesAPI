<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\DeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\Uploader;
use App\Models\Attachment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\QueryBuilder;


class CategoryController extends Controller
{
    use Uploader;

    public function __construct(Request $request)
    {
        $this->setConstruct($request, CategoryResource::class);
    }

    public function index() {

        $categories =QueryBuilder::for(Category::class)
                                ->allowedIncludes(Category::allowedIncludes())
                                ->allowedFilters(Category::allowedFilters())
                                ->defaultSort('-id')
                                ->paginate($this->perPage, ['*'], 'page', $this->page);

        return $this->collection($categories);
    }

    public function show($id) {

        $category = Category::find($id);

        if(!$category) return $this->error(404, 'Not Found !');

        return $this->resource($category->load(Category::allowedIncludes()));
    }

    public function store(StoreCategoryRequest $request) {

        DB::beginTransaction();

        try {

            $category = Category::create($request->validated());

            foreach($request->images as $image) {

                $path = $this->uploadAttachment($image['image'], 2);

                $attachment = new Attachment();
                $attachment->path = $path;
                $attachment->title = $image['title'];
                $attachment->attachable_type = Category::class;
                $attachment->attachable_id = $category->id;
                $attachment->save();
            }

            DB::commit();

            return $this->resource($category);
        }

        catch(\Exception $e) {

            DB::rollBack();
            throw $e;
        }

    }

    public function update(UpdateCategoryRequest $request, $id) {

        $category = Category::find($id);

        if(!$category) return $this->error(404, 'Not Found !');

        $category->update($request->validated());

        if(!is_null($request->images)) {

            if(count($category->attachments()->get())) {
                $category->attachments()->delete();
            }

            foreach($request->images as $image) {

                $path = $this->uploadAttachment($image['image'], 2);

                $attachment = new Attachment();
                $attachment->path = $path;
                $attachment->title = $image['title'];
                $attachment->attachable_type = Category::class;
                $attachment->attachable_id = $category->id;
                $attachment->save();
            }
        }

        return $this->resource($category->load(Category::allowedIncludes()));
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
