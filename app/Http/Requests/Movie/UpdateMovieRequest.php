<?php

namespace App\Http\Requests\Movie;

use App\Http\Traits\JsonErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
            'name' => ['max:100', Rule::unique('movies', 'name')->ignore($this->route('movie'))],
            'hours' => ['numeric', 'min:1'],
            'minutes' => ['numeric', 'min:0', 'max:59'],
            'seconds' => ['numeric', 'min:0', 'max:59'],
            'date' => ['date'],
            'category_id' => [Rule::exists('categories', 'id')]
        ];
    }
}
