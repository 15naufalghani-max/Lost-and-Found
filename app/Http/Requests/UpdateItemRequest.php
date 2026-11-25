<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends ItemFormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('item');

        return $item instanceof Item
            && $this->user() !== null
            && $item->user_id === $this->user()->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge($this->itemRules(), [
            'status' => ['sometimes', 'string', Rule::in(Item::STATUSES)],
        ]);
    }
}
