<?php

namespace App\Http\Resources;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class OrdersResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'date' => $this->date_formatted,
            'time' => $this->time_formatted,
            'total' => $this->total,
            'trackeable' => $this->trackeable,
            'is_completed' => $this->is_completed,
            'is_working' => $this->trackeable,
            'user' => new UserResource($this->whenLoaded('user')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'packages' => PackageResource::collection($this->whenLoaded('packages')),
            'job' => new JobResource($this->whenLoaded('job')),
            'payment_mode' => ucfirst($this->payment_mode),
            'status' => ucfirst($this->status),
            'full_address' => $this->full_address,
            'customer_name' => $this->customer_name,
            'customer_mobile' => $this->customer_mobile,
        ];
    }
}
