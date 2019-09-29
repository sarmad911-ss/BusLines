<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = ['start_waypoints','end_waypoints','start_waypoints_reversed','end_waypoints_reversed','border_waypoint','border_waypoint_reversed',"inside_distance","outside_distance","profit_percent","km_inside","km_inside_with_nds","other_costs","days","type","stage","cost"];
    protected $table = 'calculations';

    public function setStartWaypointsAttribute($value){
        $this->attributes['start_waypoints'] = json_encode($value);
    }
    public function setEndWaypointsAttribute($value){
        $this->attributes['end_waypoints'] = json_encode($value);
    }
    public function getStartWaypointsAttribute(){
        return json_decode($this->attributes['start_waypoints']);
    }
    public function getEndWaypointsAttribute(){
        return json_decode($this->attributes['end_waypoints']);
    }

    public function setStartWaypointsReversedAttribute($value){
        $this->attributes['start_waypoints_reversed'] = json_encode($value);
    }
    public function setEndWaypointsReversedAttribute($value){
        $this->attributes['end_waypoints_reversed'] = json_encode($value);
    }
    public function getStartWaypointsReversedAttribute(){
        return json_decode($this->attributes['start_waypoints_reversed']);
    }
    public function getEndWaypointsReversedAttribute(){
        return json_decode($this->attributes['end_waypoints_reversed']);
    }


    public function setBorderWaypointAttribute($value){
        $this->attributes['border_waypoint'] = json_encode($value);
    }
    public function setBorderWaypointReversedAttribute($value){
        $this->attributes['border_waypoint_reversed'] = json_encode($value);
    }
    public function getBorderWaypointAttribute(){
        return json_decode($this->attributes['border_waypoint']);
    }
    public function getBorderWaypointReversedAttribute(){
        return json_decode($this->attributes['border_waypoint_reversed']);
    }


}
