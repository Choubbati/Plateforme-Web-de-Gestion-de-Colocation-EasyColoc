<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Owner + Member (active) يقدرو يزيدو category
        $colocation = $this->route('colocation');

        return $colocation
            && $colocation->memberships()
                ->where('user_id', $this->user()->id)
                ->whereNull('left_at')
                ->exists();
    }

    public function rules(): array
    {
        $colocationId = $this->route('colocation')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                // unique داخل نفس colocation
                "unique:categories,name,NULL,id,colocation_id,{$colocationId}",
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique' => 'Cette catégorie existe déjà dans cette colocation.',
        ];
    }
}
