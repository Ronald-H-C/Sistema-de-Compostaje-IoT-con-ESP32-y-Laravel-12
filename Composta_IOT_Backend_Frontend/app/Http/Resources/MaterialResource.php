<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'clasification' => $this->clasification,   // 'verde','marron','no_compostable'
            'aptitude'      => $this->aptitude,        // 'casero','industrial','no_recomendado'
            'type_category' => $this->type_category,   // 'alimentos','jardin','papel_carton','otros'
            'image_url'     => $this->image_url,
            'created_at'    => optional($this->created_at)->toISOString(),
        ];
    }
}
