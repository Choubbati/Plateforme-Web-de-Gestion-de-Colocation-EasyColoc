<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $colocation = $this->route('colocation');

        return $colocation
            && $colocation->memberships()
                ->where('user_id', $this->user()->id)
                ->whereNull('left_at')
                ->exists();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
