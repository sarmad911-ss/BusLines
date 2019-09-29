<?php

namespace App\Models;

use App\Http\Helpers\Document;
use App\Models\core\AcsimaModel;
use App\Models\core\File;
use App\Models\trip\Trip;
use App\Models\trip\TripFile;
use Carbon\Carbon;

/**
 * Class Transaction
 * @package App\Models
 * @property integer purpose_id
 * @property integer cost
 * @property integer date
 * @property string misc
 * @property integer file_id
 * @property integer trip_id
 */

class Transaction extends AcsimaModel
{
    protected $fillable = ['purpose_id', 'cost', 'date', 'misc', 'file_id','trip_id'];

    public function purpose()
    {
        return $this->hasOne(TransactionPurpose::class, 'id', 'purpose_id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function setDateAttribute($date)
    {
        if(!empty($date)){
            $this->attributes['date'] = Carbon::createFromFormat(config('app.date_format'), $date)->timestamp;
        }else{
            $this->attributes['date'] = Carbon::now()->timestamp;
        }
    }

    public function getDateAttribute()
    {
        $date = $this->attributes['date'] ?? null;
        if(!empty($date)){
            $date = Carbon::createFromTimestamp($date)->format(config('app.date_format'));
        }
        return $date;
    }

    public function getStoreUrlAttribute()
    {
        $url = null;
        if(!empty($this->id)){
            $url = route('storeF', $this);
        }
        return $url;
    }

    public function trip()
    {
        return $this->hasOne(Trip::class, 'id', 'trip_id');
    }

    public function storeRG()
    {
        $settings = Settings::all()->toArray();

        $data = [
            'trip.client.name' => $this->trip->client->name ?? null,
            'trip.client.address' => $this->trip->client->address ?? null,
            'trip.client.representative.name' => $this->trip->client->representative->name ?? null,
            'trip.waypoints' => $this->trip->getDocumentWaypoints() ?? null,
            'trip.start_date' => $this->trip->start_date ?? null,
            'trip.end_date' => $this->trip->end_date ?? null,
            'trip.passengers_quantity' => $this->trip->passengers_quantity ?? null,
            'trip.type.name' => $this->trip->type->name ?? null,
            'trip.bus_distance' => $this->trip->bus_distance ?? null,
            'trip.inside_distance' => $this->trip->inside_distance ?? null,
            'trip.distanceF' => $this->trip->distanceF ?? null,
            'trip.insideDistanceF' => $this->trip->insideDistanceF ?? null,
            'trip.outsideDistanceF' => $this->trip->outsideDistanceF ?? null,
            'trip.insideDistancePercent' => $this->trip->insideDistancePercent ?? null,
        ];
        $data .= [
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
            'transaction.cost' => $this->cost ?? null,
            'transaction.id' => $this->id ?? null,
            'date' => Carbon::now()->format(config('app.document_date_format'))
        ];
        $document = new Document(resource_path('documents/RG.docx'));

        /** name generation */
        $name = "RG";
        $name .= $this->id ?? '';


        /** associate with file */
        $documentInfo = $document->docx($name, $data);
        $file = new File();
        $file->path = $documentInfo['path'];
        $file->name = $documentInfo['name'];
        $file->size = $documentInfo['size'];
        $file->save();
        if(!empty($this->trip_id)){
            $tripFile = new TripFile();
            $tripFile->file_id = $file->id;
            $tripFile->trip_id = $this->id;
            $tripFile->save();
        }
        //TODO mb in tripfilereource link to pdf?

        return $file;
    }

}
