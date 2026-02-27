<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=> ['required', 'string'],
            'amount'=>['required', 'numeric', 'min:0.01'],
            'expense_date'=> ['required', 'date'],
            'category_id' => ['required','exists:category,id'],
        ];
    }


    public function messages():array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'amount.required' => 'Le montant est obligatoire.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
            'expense_date.required' => 'La date est obligatoire.',
            'category_id.required' => 'La catégorie est obligatoire.',
        ];
    }
}
