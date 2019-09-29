<?php

namespace App\Models\trip;

use App\Helpers\SendMail;
use App\Http\Helpers\Data;
use App\Http\Helpers\Document;
use App\Models\Bus;
use App\Models\core\AcsimaModel;
use App\Models\core\File;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Trip
 * @package App\Models\trip
 * @property integer id
 * @property integer passengers_quantity
 * @property boolean bus_inside
 * @property integer client_id
 * @property integer status_id
 * @property string misc
// * @property integer start_date
// * @property integer end_date
// * @property integer start_date_reversed
// * @property integer end_date_reversed
 * @property integer type_id
 * @property string from
 * @property string to
 * @property float cost
 * @property integer inside_distance
 * @property integer outside_distance
 * @property integer distance
 * @property integer insideDistanceF
 * @property integer outsideDistanceF
 * @property integer distanceF
 * @property boolean abroad - поездка за границу
 */

class Trip extends AcsimaModel
{

    protected $fillable = ['passengers_quantity', 'bus_inside', 'client_id', 'misc', 'type_id', 'cost', 'name',
        'status_id', 'inside_distance', 'outside_distance', 'maut', 'parken', 'spesen'];

    public $calendar_day;
    public $calendar_date;
    public $calendar_type;

    public function getStartDateAttribute()
    {
        $date = $this->start_waypoint->date ?? null;
        return $date;
    }

    public function getStartDateFAttribute()
    {
        $date = $this->start_date ?? null;
        if(!empty($date)){
            $date = Carbon::createFromFormat(config("app.datepicker_format"),$date)->format(config("app.datepicker_format_for_users"));
        }
       return $date;
    }

    public function getEndDateAttribute()
    {
        $date = $this->end_waypoint->date ?? null;
        if($this->type_id == TripType::TRANSFER && !empty($this->end_waypoint_reversed->date))
            $date = $this->end_waypoint_reversed->date;
        return $date;
    }

    public function getEndDateFAttribute()
    {
        $date = $this->end_date ?? null;
        if(!empty($date)){
            $date = Carbon::createFromFormat(config("app.datepicker_format"), $date)->format(config("app.datepicker_format_for_users"));
        }
        return $date;
    }

    public function client()
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function type()
    {
        return $this->hasOne(TripType::class, 'id', 'type_id');
    }

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class)
            ->orderBy('id')
            ->where('id', '!=', $this->start_waypoint->id ?? null)
            ->where('id', '!=', $this->end_waypoint->id ?? null);
    }

    public function allWaypoints()
    {
        return $this->hasMany(Waypoint::class)
            ->orderBy('id');
    }

    public function segments()
    {
        return $this->hasMany(TripSegment::class)->orderBy('id');
    }

    public function start_waypoint()
    {
        return $this->hasOne(Waypoint::class)->where('type','start_waypoint');
    }

    public function end_waypoint()
    {
        return $this->hasOne(Waypoint::class)->where('type','end_waypoint');
    }

    public function start_waypoint_reversed()
    {
        return $this->hasOne(Waypoint::class)->where('type','start_waypoint_reversed');
    }

    public function end_waypoint_reversed()
    {
        return $this->hasOne(Waypoint::class)->where('type','end_waypoint_reversed');
    }

    public function border_waypoint(){
        return $this->hasOne(Waypoint::class)->where('type','border_waypoint');
    }

    public function border_waypoint_reversed(){
        return $this->hasOne(Waypoint::class)->where('type','border_waypoint_reversed');
    }

    public function start_waypoints(){
        return $this->hasMany(Waypoint::class)->where('type','start_waypoints');
    }

    public function end_waypoints(){
        return $this->hasMany(Waypoint::class)->where('type','end_waypoints');
    }

    public function start_waypoints_reversed(){
        return $this->hasMany(Waypoint::class)->where('type','start_waypoints_reversed');
    }

    public function end_waypoints_reversed(){
        return $this->hasMany(Waypoint::class)->where('type','end_waypoints_reversed');
    }

    public function fillWaypoints(Request $request)
    {

        /** Remove not matching waypoints */
        Waypoint::where('trip_id', $this->id)->delete();

        if($request->has("start_waypoint")) {
            $start_waypoint = $request->get("start_waypoint");
            $start_waypoint = new Waypoint($start_waypoint);
            $start_waypoint->trip_id = $this->id;
            $start_waypoint->type = 'start_waypoint';
            $start_waypoint->save();
        }
        if($request->has("end_waypoint")) {
            $end_waypoint = $request->get("end_waypoint");
            $end_waypoint = new Waypoint($end_waypoint);
            $end_waypoint->trip_id = $this->id;
            $end_waypoint->type = 'end_waypoint';
            $end_waypoint->save();
        }
        if($request->has("start_waypoint_reversed")) {
            $start_waypoint = $request->get("start_waypoint_reversed");
            $start_waypoint = new Waypoint($start_waypoint);
            $start_waypoint->trip_id = $this->id;
            $start_waypoint->type = 'start_waypoint_reversed';
            $start_waypoint->save();
        }
        if($request->has("end_waypoint_reversed")) {
            $end_waypoint = $request->get("end_waypoint_reversed");
            $end_waypoint = new Waypoint($end_waypoint);
            $end_waypoint->trip_id = $this->id;
            $end_waypoint->type = 'end_waypoint_reversed';
            $end_waypoint->save();
        }
        if($request->has("border_waypoint")) {
            $border_waypoint = $request->get("border_waypoint");
            $border_waypoint = new Waypoint($border_waypoint);
            $border_waypoint->trip_id = $this->id;
            $border_waypoint->type = 'border_waypoint';
            $border_waypoint->save();
        }
        if($request->has("border_waypoint_reversed")) {
            $border_waypoint_reversed = $request->get("border_waypoint_reversed");
            $border_waypoint_reversed = new Waypoint($border_waypoint_reversed);
            $border_waypoint_reversed->trip_id = $this->id;
            $border_waypoint_reversed->type = 'border_waypoint_reversed';
            $border_waypoint_reversed->save();
        }

        if($request->has("start_waypoints")) {
            foreach ($request->get("start_waypoints") as $waypoint) {
                $waypoint = new Waypoint($waypoint);
                $waypoint->trip_id = $this->id;
                $waypoint->type = 'start_waypoints';
                $waypoint->save();
            }
        }
        if($request->has("end_waypoints")) {
            foreach ($request->get("end_waypoints") as $waypoint) {
                $waypoint = new Waypoint($waypoint);
                $waypoint->trip_id = $this->id;
                $waypoint->type = 'end_waypoints';
                $waypoint->save();
            }
        }
        if($request->has("start_waypoints_reversed")) {
            foreach ($request->get("start_waypoints_reversed") as $waypoint) {
                $waypoint = new Waypoint($waypoint);
                $waypoint->trip_id = $this->id;
                $waypoint->type = 'start_waypoints_reversed';
                $waypoint->save();
            }
        }
        if($request->has("end_waypoints_reversed")) {
            foreach ($request->get("end_waypoints_reversed") as $waypoint) {
                $waypoint = new Waypoint($waypoint);
                $waypoint->trip_id = $this->id;
                $waypoint->type = 'end_waypoints_reversed';
                $waypoint->save();
            }
        }
        return true;
    }

    public function fillSegments($segments)
    {
        if(empty($this->id) || empty($segments)){
            return false;
        }

        /** Remove not matching segments */
        $ids = array_column($segments, 'id');
        TripSegment::whereNotIn('id', $ids)->where('trip_id', $this->id)->delete();

        /** Store segments */
        foreach ($segments ?? [] as $segmentData){
            $segment = TripSegment::findOrNew($segmentData['id'] ?? null);
            $segment->fill($segmentData);
            $segment->trip_id = $this->id;
            $segment->save();
        }

        return true;
    }

    public function fillBuses($buses)
    {
        if(empty($this->id) || empty($buses)){
            return false;
        }

//        /** Remove all trip buses */
//        TripBus::where('trip_id', $this->id)->delete();

        /** Store trip buses */
        foreach ($buses ?? [] as $busData){
            $tripBus = TripBus::findOrNew($busData['id'] ?? null);
            $tripBus->fill($busData);
            $tripBus->trip_id = $this->id;
            $tripBus->save();
        }
        return true;
    }

    public function fillServices ($servicesIds)
    {
        if(empty($this->id) || empty($servicesIds)){
            return false;
        }
        $this->services()->sync($servicesIds);
        return true;
    }

    public function fillConditions($conditionsIds)
    {
        if(empty($this->id) || empty($conditionsIds)){
            return false;
        }
        $this->conditions()->sync($conditionsIds);
        return true;
    }

    public function getPaidAttribute()
    {
        //TODO when finish finances
        return 0;
    }

    public function status()
    {
        return $this->hasOne(TripStatus::class, 'id', 'status_id');
    }

    public function getActiveStatusAttribute()
    {
        $status = null;
        $nowTimestamp = Carbon::now()->timestamp;
        if(
            ($this->status_id == TripStatus::APPROVED) &&
            ($nowTimestamp >= $this->start_date) &&
            ($nowTimestamp <= $this->end_date )
        ){
            $status = new TripStatus();
            $status->name = 'Aktiv';
        }
        return $status;
    }

    public function trip_buses()
    {
        return $this->hasMany(TripBus::class, 'trip_id', 'id');
    }

    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'trip_buses')->orderBy('id');
    }

    public function bus()
    {
        return $this->hasMany(TripBus::class, 'trip_id', 'id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'trip_files')->orderBy('id');
    }

    public function getProfileUrlAttribute()
    {
        $url = null;
        if(!empty($this->id)){
            $url = route('profileTripView', $this);
        }
        return $url;
    }

    public function getStoreUrlAttribute()
    {
        $url = null;
        if(!empty($this->id)){
            $url = route('storeTripView', $this);
        }
        return $url;
    }

    public static function search($value)
    {
        if(empty($value)){
            return false;
        }
        $tripIds = Trip::where("name", "ILIKE", "%$value%")
                ->OrWhereIn('client_id', User::searchClient($value))
                ->OrWhereIn('id', Bus::search($value))
                ->OrWhere('id', "ILIKE", "$value%")
                ->pluck('id')
                ->toArray();
//        /** If user insert numbers then search only in ID */
//        if( intval($value) == false){
//            /** value is not integer */
//            $tripIds = Trip::where("name", "ILIKE", "%$value%")
//                ->OrWhereIn('client_id', User::searchClient($value))
//                ->OrWhereIn('id', Bus::search($value))
//                ->pluck('id')
//                ->toArray();
//        }else{
//            /** value is integer, searching ID */
//            $tripIds = Trip::where("id", "ILIKE", "$value%")
//                ->pluck('id')
//                ->toArray();
//        }
        return $tripIds;
    }

    public function getDistanceAttribute()
    {
        return $this->inside_distance + $this->outside_distance;
    }

    public function getInsideDistanceFAttribute()
    {
        if(!empty($this->inside_distance))
            return round($this->inside_distance/1000, 3);
    }

    public function getOutsideDistanceFAttribute()
    {
        if(!empty($this->outside_distance))
            return round($this->outside_distance/1000, 3);
    }

    public function getDistanceFAttribute()
    {
        if(!empty($this->distance))
            return round($this->distance/1000, 3);
    }

    public function getTimesLeftAttribute()
    {
        $now = Carbon::now();
        $startDateTimestamp = $this->attributes['start_date'] ?? null;
        $endDateTimestamp = $this->attributes['end_date'] ?? null;
        $timeLeft = null;
        if(!empty($startDateTimestamp) || !empty($endDateTimestamp)){
            $timeLeft = 'Jetzt aktiv';
            if($now->timestamp < $startDateTimestamp && !empty($startDateTimestamp)){
                /** Поездка ещё не состоялась */
                $startDate = Carbon::createFromTimestamp($startDateTimestamp);
                $remainDays = $now->diffInDays($startDate);
                $startDate->subDays($remainDays);
                $remainHours = $now->diffInHours($startDate);
                $timeLeft = "Übrig geblieben $remainDays"."t. $remainHours"."s.";
            }elseif ($now->timestamp > $endDateTimestamp && !empty($endDateTimestamp)){
                /** Поездка уже прошла */
                $endDate = Carbon::createFromTimestamp($endDateTimestamp);
                $remainDays = $now->diffInDays($endDate);
                $endDate->addDays($remainDays);
                $remainHours = $now->diffInHours($endDate);
                $timeLeft = "Bestanden $remainDays". "t. $remainHours"."s.";
            }
        }
        return $timeLeft;
    }

    public function getInsideDistancePercentAttribute()
    {
        if(!empty($this->distance) && !empty($this->inside_distance)){
            $percent = ($this->inside_distance * 100) / $this->distance;
            return round($percent, 2);
        }
    }

    public function getOutsideDistancePercentAttribute()
    {
        if(!empty($this->distance) && !empty($this->outside_distance)){
            $percent = ($this->outside_distance * 100) / $this->distance;
            return round($percent, 2);
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'trip_id', 'id')->orderBy('created_at');
    }

    public function setMautAttribute($data)
    {
        $this->attributes['maut'] = json_encode($data);
    }

    public function getMautAttribute()
    {
        $data['value'] = 0;
        $data['client_pay'] = 0;
        $data['document_display'] = 0;
        $dbData = json_decode($this->attributes['maut']);
        if(empty($dbData)) return $data;
        /** default data if empty db column */
        $data['value'] = $dbData->value ?? 0;
        $data['client_pay'] = (int) ($dbData->client_pay == "true" || $dbData->client_pay == 1);
        $data['document_display'] = (int) ($dbData->document_display == "true" || $dbData->document_display == 1);
        return $data;
    }

    public function setParkenAttribute($data)
    {
        $this->attributes['parken'] = json_encode($data);
    }

    public function getParkenAttribute()
    {
        $data['value'] = 0;
        $data['client_pay'] = 0;
        $data['document_display'] = 0;
        $dbData = json_decode($this->attributes['parken']);
        if(empty($dbData)) return $data;
        /** default data if empty db column */
        $data['value'] = $dbData->value ?? 0;
        $data['client_pay'] = (int) ($dbData->client_pay == "true" || $dbData->client_pay == 1);
        $data['document_display'] = (int) ($dbData->document_display == "true" || $dbData->document_display == 1);

        return $data;
    }

    public function setSpesenAttribute($data)
    {
        $this->attributes['spesen'] = json_encode($data);
    }

    public function getSpesenAttribute()
    {
        $data['value'] = 0;
        $data['client_pay'] = 0;
        $data['document_display'] = 0;
        $dbData = json_decode($this->attributes['spesen']);
        if(empty($dbData)) return $data;
        /** default data if empty db column */
        $data['value'] = $dbData->value ?? 0;
        $data['client_pay'] = (int) ($dbData->client_pay == "true" || $dbData->client_pay == 1);
        $data['document_display'] = (int) ($dbData->document_display == "true" || $dbData->document_display == 1);

        return $data;
    }

    public function storeAB()
    {
        $settings = Settings::all()->toArray();
        $data = [
            'trip.client.name' => $this->client->name ?? null,
            'trip.client.address' => $this->client->address ?? null,
            'trip.client.representative.name' => $this->client->representative->name ?? null,
            'trip.waypoints' => $this->getDocumentWaypoints() ?? null,
            'trip.start_date' => $this->start_date ?? null,
            'trip.end_date' => $this->end_date ?? null,
            'trip.passengers_quantity' => $this->passengers_quantity ?? null,
            'trip.type.name' => $this->type->name ?? null,
            'trip.bus_distance' => $this->bus_distance,
            'trip.inside_distance' => $this->inside_distance,
//            'trip.buses' => $this->buses,
            'trip.cost' => $this->cost,
            'settings.company_name' => Settings::getValueFromDataByKey('company_name', $settings),
            'settings.street' => Settings::getValueFromDataByKey('street', $settings),
            'settings.city' => Settings::getValueFromDataByKey('city', $settings),
            'settings.phone' => Settings::getValueFromDataByKey('phone', $settings),
            'settings.mobile' => Settings::getValueFromDataByKey('mobile', $settings),
            'settings.fax' => Settings::getValueFromDataByKey('fax', $settings),
            'settings.email' => Settings::getValueFromDataByKey('email', $settings),
            'settings.site' => Settings::getValueFromDataByKey('site', $settings),
            'settings.bank_name' => Settings::getValueFromDataByKey('bank_name', $settings),
            'settings.iban' => Settings::getValueFromDataByKey('iban', $settings),
            'settings.bic' => Settings::getValueFromDataByKey('bic', $settings),
            'date' => Carbon::now()->format(config('app.document_date_format'))
        ];
        $document = new Document(resource_path('documents/AB.docx'));
        /** name generation */
        $name = "Auftragsbestätigung";
        $name .= $this->id ?? '';
        $name .= $this->client->name ?? '';

        /** associate with file */
        $documentInfo = $document->docx($name, $data);
        $file = new File();
        $file->path = $documentInfo['path'];
        $file->name = $documentInfo['name'];
        $file->size = $documentInfo['size'];
        $file->save();
        $tripFile = new TripFile();
        $tripFile->file_id = $file->id;
        $tripFile->trip_id = $this->id;
        $tripFile->save();

        //TODO mb in tripfilereource link to pdf?

        return $file;
    }

    public function storeFG()
    {
        $settings = Settings::all()->toArray();
        $files = new Collection();
        foreach ($this->buses as $bus){
            $data = [
                'trip.client.name' => $this->client->name ?? null,
                'trip.client.representative.name' => $this->client->representative->name ?? null,
                'trip.waypoints' => $this->getDocumentWaypoints() ?? null,
                'trip.start_date' => $this->start_date ?? null,
                'trip.end_date' => $this->end_date ?? null,
                'trip.passengers_quantity' => $this->passengers_quantity ?? null,
                'trip.type.name' => $this->type->name ?? null,
                'trip.bus_distance' => $this->bus_distance ?? null,
                'trip.inside_distance' => $this->inside_distance ?? null,
                'trip.bus.plate_number' => $bus->plate_number ?? null,
                'settings.company_name' => Settings::getValueFromDataByKey('company_name', $settings),
                'settings.street' => Settings::getValueFromDataByKey('street', $settings),
                'settings.city' => Settings::getValueFromDataByKey('city', $settings),
                'settings.phone' => Settings::getValueFromDataByKey('phone', $settings),
                'settings.mobile' => Settings::getValueFromDataByKey('mobile', $settings),
                'settings.fax' => Settings::getValueFromDataByKey('fax', $settings),
                'settings.email' => Settings::getValueFromDataByKey('email', $settings),
                'settings.site' => Settings::getValueFromDataByKey('site', $settings),
                'settings.bank_name' => Settings::getValueFromDataByKey('bank_name', $settings),
                'settings.iban' => Settings::getValueFromDataByKey('iban', $settings),
                'settings.bic' => Settings::getValueFromDataByKey('bic', $settings),
                'date' => Carbon::now()->format(config('app.document_date_format'))
            ];
            $document = new Document(resource_path('documents/FG.docx'));

            /** name generation */
            $name = "Fahrauftrag";
            $name .= $this->client->name ?? '';
            $name .= $bus->plate_number ?? '';

            /** associate with file */
            $documentInfo = $document->docx($name, $data);
            $file = new File();
            $file->path = $documentInfo['path'];
            $file->name = $documentInfo['name'];
            $file->size = $documentInfo['size'];
            $file->save();
            $tripFile = new TripFile();
            $tripFile->file_id = $file->id;
            $tripFile->trip_id = $this->id;
            $tripFile->save();

            //TODO mb in tripfilereource link to pdf?
            $files->push($file);
        }
        return $files;
    }

    public function getDocumentWaypoints()
    {
        $waypoints = '';
        if($this->allWaypoints->isEmpty())
            return $waypoints;
        $enterChar = '<w:t><w:br/></w:t>';
        $locations = $this->allWaypoints->pluck('location')->toArray();
        $waypoints = implode($enterChar, $locations);
        return $waypoints;
    }

    public function sendMail($pdfUrl)
    {
        if(empty($pdfUrl) || empty($this->client_id) || empty($this->client->email))
            return false;
        $data = [
            'pdf_name' => Document::getNameFromPath($pdfUrl),
            'pdf_url' => $pdfUrl,
            'client' => $this->client
        ];
        SendMail::sendEmail($this->client->email, 'Dokument', 'mail.document', $data);
        return true;
    }

    public function services()
    {
        return $this->belongsToMany(TripService::class, 't_s', 'trip_id', 'service_id')->orderBy('id');

    }

    public function conditions()
    {
        return $this->belongsToMany(TripCondition::class, 't_c', 'trip_id', 'condition_id')->orderBy('id');
    }

    public function getServicesStringAttribute()
    {
        $string = '';
        if(!empty($this->services)){
            $string = $this->services->pluck('name')->implode(',');
        }
        return $string;
    }

    public function getConditionsStringAttribute()
    {
        $string = '';
        if(!empty($this->conditions)){
            $string = $this->conditions->pluck('name')->implode(',');
        }
        return $string;
    }

    public function getAbroadAttribute(){
        return !empty($this->border_waypoint) || !empty($this->border_waypoint_reversed);
    }

    public static function getTripsBetween(int $startDate, int $endDate)
    {
        $tripIds = Waypoint::whereBetween('date', [$startDate, $endDate])->pluck('trip_id')->unique()->toArray();
        return Trip::whereIn('id', $tripIds)->with('status', 'buses', 'end_waypoint',
            'start_waypoint_reversed', 'end_waypoint_reversed', 'client')
            ->with(['start_waypoint' => function ($query) {
                $query->orderBy('date', 'desc');
            }])
            ->get();
    }

    public function getStartDepotTimeAttribute()
    {
        return $this->start_waypoint->date ?? null;
    }

    public function getEndDepotTimeAttribute()
    {
        return $this->end_waypoint->date ?? null;
    }

    public function getStartDepotTimeReversedAttribute()
    {
        return $this->start_waypoint_reversed->date ?? null;
    }

    public function getEndDepotTimeReversedAttribute()
    {
        return $this->end_waypoint_reversed->date ?? null;
    }

    public function isActiveInDate(int $date)
    {
        $isActive = false;
        $startDepotTime = $this->start_waypoint->attributes['date'];
        $endDepotTime = $this->end_waypoint->attributes['date'];
        if( ($startDepotTime <= $date) || ($date <= $endDepotTime) ){
            $isActive = true;
        }
        if( ($this->type_id == TripType::TRANSFER) && ($isActive == false)){
            $startDepotTimeReversed = $this->start_waypoint_reversed->attributes['date'];
            $endDepotTimeReversed = $this->end_waypoint_reversed->attributes['date'];
            if( ($startDepotTimeReversed <= $date) || ($date <= $endDepotTimeReversed)){
                $isActive = true;
            }
        }
        return $isActive;
    }

}
