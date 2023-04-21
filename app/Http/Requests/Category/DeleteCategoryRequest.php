<?php

namespace App\Http\Requests\Category;

use App\Http\Traits\JsonErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class DeleteCategoryRequest extends FormRequest
{
    use JsonErrors;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole(Config::get('constants.ADMIN_ROLE'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
