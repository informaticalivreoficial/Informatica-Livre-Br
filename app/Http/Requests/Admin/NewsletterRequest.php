<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->segment(4);

        return [
            'nome' => ['required', 'string', 'min:3', 'max:191'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:191', "unique:newsletter,email,{$id},id"],
        ];
    }
}
