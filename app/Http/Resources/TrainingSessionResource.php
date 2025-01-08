<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'difficulty_level' => $this->difficulty_level,
            'start_time' => $this->start_time->toDateTimeString(),
            'end_time' => $this->end_time->toDateTimeString(),
            'current_capacity' => $this->current_capacity,
            'max_capacity' => $this->max_capacity,
            'status' => $this->status,
            'package' => [
                'id' => $this->package->id,
                'name' => $this->package->name,
                'price' => $this->package->price,
                'duration' => $this->package->duration,
            ],
            'trainer' => [
                'id' => $this->trainer->id,
                'name' => $this->trainer->user->name,
                'specialization' => $this->trainer->specialization,
            ],
            'attendance' => AttendanceLogResource::collection($this->attendance),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
