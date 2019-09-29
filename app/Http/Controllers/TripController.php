<?php

namespace App\Http\Controllers;

use App\Helpers\SendMail;
use App\Http\Helpers\Document;
use App\Http\Resources\trip\TripCalendarResource;
use App\Http\Resources\trip\TripConditionResource;
use App\Http\Resources\trip\TripFileResource;
use App\Http\Resources\trip\TripResource;
use App\Http\Resources\trip\TripServiceResource;
use App\Http\Resources\trip\TripStatusResoure;
use App\Http\Resources\trip\TripTypeResource;
use App\Models\core\AcsimaModel;
use App\Models\core\File;
use App\Models\trip\Trip;
use App\Models\trip\TripCondition;
use App\Models\trip\TripFile;
use App\Models\trip\TripService;
use App\Models\trip\TripStatus;
use App\Models\trip\TripType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TripController extends Controller
{
    public function storeView(Request $request, Trip $trip)
    {
        return view('pages.trips.store',['trip'=>$trip]);
    }

    public function storeAction(Request $request)
    {
        /** @var Trip $trip */
        $trip = Trip::findOrNew($request->id);
        $trip->fill($request->all());
        $trip->status_id = $trip->status_id ?? TripStatus::PLANNED;
        $trip->save();
        $trip->fillWaypoints($request);
        $trip->fillSegments($request->segments);
        $trip->fillBuses($request->buses);
        $trip->fillServices($request->services_ids);
        $trip->fillConditions($request->conditions_ids);
        return TripResource::make($trip);
    }

    public function listView(Request $request)
    {
        return view('pages.trips.list');
    }


    public function listData(Request $request)
    {
        /** initialize date */
        if(!empty($request->start_date)){
            $startDate = new Carbon($request->start_date);
        }else{
            $startDate = (new Carbon())->subDay();
        };
        $startDate = $startDate->startOfDay();
        $endDate = $startDate->copy()->addMonth()->endOfDay(); /** How much scope */
        /** getting trips */
        if(!empty($request->search)){
            $trips = Trip::whereIn('trips.id', Trip::search($request->search))
                ->with('status', 'buses', 'end_waypoint',
                    'start_waypoint_reversed', 'end_waypoint_reversed', 'client')
                ->with(['start_waypoint' => function ($query) {
                    $query->orderBy('date', 'desc');
                }])
                ->get();
        }else{
            $trips = Trip::getTripsBetween($startDate->timestamp, $endDate->timestamp);
        }

        /** Calendar formatting */
        if($trips->isEmpty()) {
            return response()->json(['data' => []]);
        }
        $calendar = new Collection();
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
        foreach ($period as $k => $date) {
            foreach ($trips as $tripExample) {
                $trip = clone $tripExample;
                if($trip->isActiveInDate($date->timestamp)){

                    $trip->calendar_date = $date->format('d.m');
                    $trip->calendar_day = $date->format('D');

                    /** type of current date */
                    $now = Carbon::now();
                    $startOfThisDay = $now->startOfDay();
                    $endOfThisDay = $now->endOfDay();
                    if( ($date->timestamp >= $startOfThisDay->timestamp) && ($date->timestamp <= $endOfThisDay->timestamp)){
                        $type = 'now';
                    }elseif ($now->lt($date)){
                        $type = 'future';
                    }elseif ($now->gt($date)){
                        $type = 'past';
                    }
                    $trip->сalendar_type = $type;

                    $calendar->push($trip);
                }
            }
        }
        $trips = $calendar;
        return TripCalendarResource::collection($trips);
    }

    public function deleteAction(Request $request)
    {
        $trip = Trip::find($request->id);
        if(empty($trip)){
            return abort(404);
        }
        $trip->delete();
        return redirect()->route("listTripView");
    }

    public function storeData(Request $request)
    {
        return TripResource::make(Trip::findOrNew($request->id));
    }


    public function profileView(Request $request, Trip $trip)
    {
        return view('pages.trips.profile',['trip'=>$trip]);
    }

    public function profileData(Request $request)
    {
        $trip = Trip::find($request->id);
        if(empty($trip))
            abort(404);
        return TripResource::make($trip);
    }

    public function listTypeData(Request $request)
    {
        return TripTypeResource::collection(TripType::orderBy('id')->get());
    }

    public function listStatusData(Request $request)
    {
        return TripStatusResoure::collection(TripStatus::orderBy('id')->get());
    }

    public function listServiceData(Request $request)
    {
        return TripServiceResource::collection(TripService::orderBy('id')->get());
    }

    public function listConditionData(Request $request)
    {
        return TripConditionResource::collection(TripCondition::orderBy('id')->get());
    }

    public function storeFileAction(Request $request)
    {
        $file = File::findOrNew($request->id);
        $file->path = '/trip-documents/';
        $file->file = $request->file;
        $file->save();
        TripFile::store($request->trip_id, $file->id);
        return TripFileResource::make($file);
    }

    public function deleteFileAction(Request $request)
    {
        $file = File::find($request->id);
        if(empty($file)){
            return response()->json(['success' => false]);
        }
        TripFile::where('file_id', $file->id)->delete();
        $file->delete();
        return response()->json(['success' => true]);

    }

    public function storeABAction(Request $request)
    {
        if(empty($request->trip_id)){
            return response()->json(['error' => "Empty id"]);
        }
        $trip = Trip::find($request->trip_id);
        if(empty($trip)){
            return response()->json(['error' => "No such trip by id $request->trip_id"]);
        }
        $file = $trip->storeAB();
        return TripFileResource::make($file);
    }

    public function storeFGAction(Request $request)
    {
        if(empty($request->trip_id)){
            return response()->json(['error' => "Empty id"]);
        }
        $trip = Trip::find($request->trip_id);
        if(empty($trip)){
            return response()->json(['error' => "No such trip by id $request->trip_id"]);
        }
        $files = $trip->storeFG();
        return TripFileResource::collection($files);
    }

    public function storePdfAction(Request $request)
    {
        if(empty($request->path)){
            return response()->json(['error' => "Empty file path"]);
        }
        $document = new Document();
        return $document->pdf($request->path);
    }

    public function sendMailAction(Request $request)
    {
        if(empty($request->pdf_url) || empty($request->trip_id)){
            return response()->json(['error' => "Empty pdf url"]);
        }
        $trip = Trip::find($request->trip_id);
        if(empty($trip)){
            return response()->json(['error' => "No such trip by id $request->trip_id"]);
        }
        $trip->sendMail($request->pdf_url);
        return response()->json(['success' => "true"]);
    }

    public function listDataOld(Request $request)
    {

        $trips = Trip::orderBy($request->order_key ?? 'start_date', $request->order_value ?? 'ASC');
        if(!empty($request->order_key)){
            $trips = Trip::orderByJoin($request->order_key ?? 'trips.start_date', $request->order_value ?? 'ASC');
            if($request->order_key == 'status.name'){
                $trips->orderBy('start_date', 'ASC');
            }
        }
        if(!empty($request->start_date)){
            $trips->where('trips.start_date', '>=', AcsimaModel::toTimestamp($request->start_date));
        }else{
            $startDate = Carbon::now()->subDay(1)->startOfDay()->timestamp;
            $trips->where('start_date', '>=', $startDate);
        }
        if(!empty($request->end_date)){
            $trips->where('trips.end_date', '<=', AcsimaModel::toTimestamp($request->end_date));
        }
        if(!empty($request->status_id)){
            $trips->where('trips.status_id', $request->status_id);
        }
        if(!empty($request->search)){
            $trips->whereIn('trips.id', Trip::search($request->search));
        }
        $trips = $trips->paginate(config('app.paginate'));
        /** Calendar format */
        if($trips->isNotEmpty()){
            $calendar = new Collection();
            $startCalendarDate = Carbon::createFromFormat(config('app.datepicker_format'), $trips->first()->start_date)->startOfDay();
            $endCalendarDate = Carbon::createFromFormat(config('app.datepicker_format'), $trips->first()->end_date)->endOfDay();
            $period = CarbonPeriod::create($startCalendarDate, '1 day', $endCalendarDate);
            foreach ($period as $k => $date){
                foreach ($trips as $trip){
                    $tripClone = clone $trip;
                    $now = $date->timestamp;
                    if( ($now <= $trip->getAttributes()['end_date']) ){
                        $tripClone->dayChar = $date->format('D');
                        $tripClone->dayNumber = $date->format('d.m');
                        $calendar->push($tripClone);
                    }
                }
            }
            $trips = $calendar;
        }

        return TripResource::collection($trips);
    }




}
