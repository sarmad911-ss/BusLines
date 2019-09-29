<?php

namespace App\Http\Resources\trip;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\BusResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['start_date_f'] = $this->start_date_f ?? null;
        $data['end_date_f'] = $this->end_date_f ?? null;
        $data['times_left'] = $this->times_left ?? null;
        $data['type'] = $this->type->name ?? null;
        $data['distance'] = $this->distance ?? null;
        $data['distanceF'] = $this->distanceF ?? null;
        $data['insideDistanceF'] = $this->insideDistanceF ?? null;
        $data['outsideDistanceF'] = $this->outsideDistanceF ?? null;
        $data['insideDistancePercent'] = $this->insideDistancePercent ?? null;
        $data['outsideDistancePercent'] = $this->outsideDistancePercent ?? null;
        $data['start_waypoint'] = WaypointResource::make($this->start_waypoint);
        $data['start_waypoint_reversed'] = WaypointResource::make($this->start_waypoint_reversed);
        $data['waypoints'] = WaypointResource::collection($this->waypoints);
        $data['end_waypoint'] = WaypointResource::make($this->end_waypoint);
        $data['end_waypoint_reversed'] = WaypointResource::make($this->end_waypoint_reversed);
        $data['start_waypoints'] = WaypointResource::collection($this->start_waypoints);
        $data['start_waypoints_reversed'] = WaypointResource::collection($this->start_waypoints_reversed);
        $data['end_waypoints'] = WaypointResource::collection($this->end_waypoints);
        $data['end_waypoints_reversed'] = WaypointResource::collection($this->end_waypoints_reversed);
        $data['border_waypoint'] = WaypointResource::make($this->border_waypoint);
        $data['border_waypoint_reversed'] = WaypointResource::make($this->border_waypoint_reversed);
        $data['segments'] = $this->segments;
        $data['client'] = $this->client;
        $data['paid'] = $this->paid;
        $data['status'] = $this->ActiveStatus ?? $this->status;
        $data['buses'] = TripBusResourse::collection($this->trip_buses()->orderBy("id")->get());
        $data['files'] = TripFileResource::collection($this->files);
        $data['transactions'] = TransactionResource::collection($this->transactions);
        $data['services'] = $this->services ?? null;
        $data['conditions'] = $this->conditions ?? null;
        $data['services_ids'] = ($this->services ?? collect([]))->pluck("id")->toArray();
        $data['conditions_ids'] = ($this->conditions ?? collect([]))->pluck("id")->toArray();
        $data['servicesString'] = $this->servicesString ?? null;
        $data['conditionsString'] = $this->conditionsString ?? null;
        $data['profile_url'] = $this->profile_url;
        $data['store_url'] = $this->store_url;
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;

    }
}
