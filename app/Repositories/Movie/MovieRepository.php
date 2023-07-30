<?php
namespace App\Repositories\Movie;

interface MovieRepository {

    public function index($perPage, $page);
    public function show($slug);
    public function store($data);
    public function update($data, $slug);
    public function destroy($slug);
}
