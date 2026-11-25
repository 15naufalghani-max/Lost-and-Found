<?php

namespace App\Http\Requests;

class StoreItemRequest extends ItemFormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->itemRules();
    }
}
