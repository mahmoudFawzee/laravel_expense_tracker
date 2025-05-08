<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = Category::where('id',$this->category_id)->first();

        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'category'=>[
                'name'=>$category->name,
                'id'=>$category->id,
            ],
        ];
    }
}
