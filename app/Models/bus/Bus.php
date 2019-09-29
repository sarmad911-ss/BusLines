<?php

namespace App\Models;

use App\Models\core\AcsimaModel;
use App\Models\core\File;
use App\Models\trip\Trip;
use App\Models\trip\TripBus;
use Carbon\Carbon;
use Composer\DependencyResolver\Request;
use Illuminate\Http\UploadedFile;

/**
 * Class Bus
 * @package App\Models
 * @property integer id
 * @property integer type_id !
 * @property string model
 * @property string plate_number — номера машины
 * @property integer seats_quantity
 * @property integer owner_id !
 * @property string vin — id номер машины
 * @property string misc
 * @property float mileage_start
 * @property float mileage
 * @property integer euro_norm_id !
 * @property integer release_date
 *w
 */
class Bus extends AcsimaModel
{
    protected $fillable = ['type_id', 'model', 'plate_number', 'seats_quantity', 'owner_id', 'vin', 'misc',
        'mileage_start', 'euro_norm_id', 'release_date'];


    public function getMileageAttribute()
    {
        $km_start = TripBus::where('bus_id', $this->id)->max('km_start');
        $km_end = TripBus::where('bus_id', $this->id)->max('km_end');
        return max($km_start, $km_end);
    }

    public function setReleaseDateAttribute($date)
    {
        if (!empty($date))
            $this->attributes['release_date'] = (new Carbon($date))->timestamp;
    }

    public function getReleaseDateAttribute()
    {
        $date = $this->attributes['release_date'] ?? null;
        if (!empty($date))
            $date = Carbon::createFromTimestamp($date)->format(AcsimaModel::DATE_PICKER_FORMAT);
        return $date;
    }

    public function getOwnerAttribute()
    {
        if(empty($this->owner_user)){
            $name = 'Bus-lines';
        }else{
            $name = $this->owner_user->name;
        }
        return $name;
    }

    public function fillDates($request)
    {
        foreach ($request->dates ?? BusDate::getDefaultDates() as $date) {
            $busDate = BusDate::findOrNew($date['id'] ?? null);
            $busDate->fill($date);
            $busDate->bus_id = $this->id;
            $busDate->save();
        }
        return true;
    }

    public function fillDocuments($fileArray)
    {
        if(empty($fileArray[0]))
            return false;

        /** Delete not matching files */
        $fIds = array_column($fileArray, 'id');
        $busFile = BusFile::where('bus_id', $this->id);
        if(!empty($fIds[0]))
            $busFile = $busFile->whereNotIn('file_id', $fIds);
        $fileIdsToDelete = $busFile->pluck('file_id')->toArray();
        foreach ($fileIdsToDelete ?? [] as $fileId){
            $file = File::find($fileId);
            if(!empty($file))
                $file->delete();
        }
        BusFile::where('bus_id', $this->id)->delete();

        /** Store files */
        foreach ($fileArray ?? [] as $fileDocument) {
            $file = File::findOrNew($fileDocument['id'] ?? null);
            $file->path = '/bus-documents/';
            $file->file = $fileDocument['file'];
            $this->files()->save($file);
            $fIds[] = $file->id;
        }
        return true;
    }

    public function getDatesAttribute()
    {
        $dates = $this->bus_dates;
        if($dates->isEmpty()){
            $dates = BusDate::getDatesArray();
        }
        return $dates;
    }

    public function bus_dates()
    {
        return $this->hasMany(BusDate::class, 'bus_id')->orderBy('id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'bus_files')->orderBy('id');
    }

    public function type()
    {
        return $this->hasOne(BusType::class, 'id', 'type_id');
    }

    public function owner_user()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function getProfileUrlAttribute()
    {
        $url = null;
        if(!empty($this->id)){
            $url = route('profileBusView', $this);
        }
        return $url;
    }

    public function getStoreUrlAttribute()
    {
        $url = null;
        if(!empty($this->id)){
            $url = route('storeDepotView', $this);
        }
        return $url;
    }

    public static function search($value)
    {
        if(empty($value)){
            return false;
        }
        $busIds = Bus::where('plate_number', 'ILIKE', "%$value%")
            ->pluck('id')
            ->toArray();
        return TripBus::whereIn('bus_id', $busIds)
            ->pluck('trip_id')
            ->toArray();
    }

    public static function getAvailableByDate($startDate = null, $endDate = null)
    {
        if(empty($startDate) || empty($endDate)) {
            return Bus::orderBy('plate_number')->get();
        }
        $startDate = Carbon::createFromFormat(config('app.datepicker_format'), $startDate);
        $endDate = Carbon::createFromFormat(config('app.datepicker_format'), $endDate);
        $startDateTimestamp = $startDate->timestamp;
        $endDateTimestamp = $endDate->timestamp;
        $tripIdsInsideTrip = Trip::where('start_date', '>=', $startDateTimestamp)
            ->where('end_date', '<=', $endDateTimestamp)
            ->where('bus_inside', true)
            ->pluck('id');

        $tripIdsInDate = Trip::whereBetween('start_date', [$startDate->startOfDay()->timestamp, $startDate->endOfDay()->timestamp])
            ->whereBetween('end_date', [$endDate->startOfDay()->timestamp, $endDate->endOfDay()->timestamp])
            ->where('bus_inside', false)
            ->pluck('id');

        $tripIds = $tripIdsInsideTrip->merge($tripIdsInDate);
        $tripIds->unique();
        $tripIds->toArray();

        $ids = TripBus::whereIn('trip_id', $tripIds)->pluck('bus_id')->toArray();
        $buses = Bus::whereNotIn('id', $ids)
            ->orderBy('plate_number')
            ->orderBy('seats_quantity')
            ->get();
        return $buses;
    }
}
