<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Parceiro extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255'],
            'email' => (!empty($this->request->all()['id']) ? 'required|email|unique:parceiros,email,' . $this->request->all()['id'] : 'required|email|unique:parceiros,email'),
            'content' => 'nullable|min:3',
            'uf' => 'required',
            'cidade' => 'required',
            //'bairro' => 'required',
            //'rua' => 'required',
            //'num' => 'required',
            //'cep' => 'required|min:8|max:10',
            'celular' => 'required'
        ];
    }
}
