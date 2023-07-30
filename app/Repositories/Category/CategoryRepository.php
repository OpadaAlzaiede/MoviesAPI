<?php
namespace App\Repositories\Category;

interface CategoryRepository {

    public function index($perPage, $page);
    public function show($slug);
    public function store($data);
    public function update($data, $slug);
    public function destroy($slug);
}
