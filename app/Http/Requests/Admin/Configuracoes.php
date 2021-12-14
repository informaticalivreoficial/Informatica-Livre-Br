<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Configuracoes extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
              'logomarca' => 'image',
              'logomarca_admin' => 'image',
              'metaimg' => 'image',
              'favicon' => 'image',
              'imgheader' => 'image',
              'marcadagua' => 'image',
        ];
    }
}
