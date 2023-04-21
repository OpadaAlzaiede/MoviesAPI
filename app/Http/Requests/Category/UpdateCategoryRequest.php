<?php

namespace App\Http\Requests\Category;

use App\Http\Traits\JsonErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => ['max:100', Rule::unique('categories', 'name')->ignore($this->route('category'))],
            'images' => ['array'],
            'images.*.title' => ['required', 'string', 'max:100'],
            'images.*.image' => ['required', 'file', 'mimes:jpg,png,jfif', 'max:10000']
        ];
    }
}
