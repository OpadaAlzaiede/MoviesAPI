<?php


namespace App\Http\Traits;

use Illuminate\Http\Request;

trait GroupAll
{
    use JSONResponse, Pagination, Uploader;

    protected $perPage;
    protected $page;
    protected $keyword;

    public function setConstruct(Request $request, $resource)
    {
        $this->setResource($resource);
        $this->perPage = $this->checkPerPageValue($request);
        $this->page = $this->checkPageValue($request);
        $this->keyword = $request->input('keyword', '');
    }

}
