<?php

namespace App\Http\Requests\Movie;

use App\Http\Traits\JsonErrors;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
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
            'name' => ['required', 'max:100', Rule::unique('movies', 'name')],
            'hours' => ['required', 'numeric', 'min:1'],
            'minutes' => ['required', 'numeric', 'min:0', 'max:59'],
            'seconds' => ['required', 'numeric', 'min:0', 'max:59'],
            'date' => ['required', 'date'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
        ];
    }
}
