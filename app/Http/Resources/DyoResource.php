<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DyoResource extends JsonResource
{
    // Define properties
    public $status;
    public $message;
    public $resource;
    public $total;  // Add property for total

    public function __construct($status, $message, $resource, $total = null)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
        $this->total   = $total;  // Set the total property if provided
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if total is set (used for paginated data)
        $response = [
            'status'  => $this->status,
            'message' => $this->message,
            'data'    => $this->resource,
        ];

        // Include total if available (used in case of paginated data)
        if ($this->total !== null) {
            $response['total'] = $this->total;
        }

        return $response;
    }
}
