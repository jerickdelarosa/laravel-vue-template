<?php

namespace App\Http\Resources\Inventory;

use App\Http\Resources\Inventory\Create\CategoryResource;
use App\Http\Resources\Inventory\Create\FundResource;
use App\Http\Resources\Inventory\Create\MeasurementResource;
use App\Http\Resources\Inventory\Create\SubcategoryResource;
use App\Http\Resources\Inventory\Create\WarrantyLengthResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'po_number' => $this->po_number,
            'acquired_at' => $this->acquired_at->format('Y-m-d'),
            'article' => $this->article,
            'description' => $this->description,
            'category' => collect(new CategoryResource($this->category))->except(['subcategories']),
            'subcategory' => new SubcategoryResource($this->subcategory),
            'fund' => new FundResource($this->fund),
            'amount' => number_format($this->amount, 2, '.', ''),
            'quantity' => $this->quantity,
            'measurement' => new MeasurementResource($this->measurement),
            'warranty_duration' => $this->warranty_duration,
            'warranty_length' => new WarrantyLengthResource($this->warranty_length)
        ];
    }
}
