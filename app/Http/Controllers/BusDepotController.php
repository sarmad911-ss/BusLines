<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusEuroNormsResource;
use App\Http\Resources\BusFileResource;
use App\Http\Resources\BusResource;
use App\Http\Resources\BusTypeResource;
use App\Http\Resources\trip\BusSelectCollection;
use App\Http\Resources\trip\BusSelectResource;
use App\Models\Bus;
use App\Models\BusFile;
use App\Models\BusType;
use App\Models\core\File;
use App\Models\EuroNorm;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class BusDepotController extends Controller
{
    public function depotView()
    {
        return view('pages.bus-depot.list');
    }

    public function listData(Request $request)
    {
        if(!empty($request->all)){
            $paginate = null;
        }
        $buses = Bus::orderBy('plate_number')->paginate(config('app.paginate'));
        return BusResource::collection($buses);
    }

    public function listSelectData(Request $request)
    {
        $availableBuses = Bus::getAvailableByDate($request->start_date, $request->end_date);
        $available = BusSelectResource::collection($availableBuses);
        $busyBuses = Bus::whereNotIn('id', $availableBuses->pluck('id')->toArray())->get();
        $busy = BusSelectResource::collection($busyBuses);
        return response()->json(['data' => ['available' => $available, 'busy' => $busy]]);
    }

    public function storeView(Request $request, Bus $bus)
    {
        return view('pages.bus-depot.store',['bus'=>$bus]);
    }

    public function storeAction(Request $request)
    {
        $bus = Bus::findOrNew($request->id);
        $bus->fill($request->all());
        $bus->save();
        $bus->fillDates($request);
        $bus->fillDocuments($request->documents);
        session()->put("notification",'Изменения сохранены');
        return BusResource::make($bus);
    }

    public function storeData(Request $request)
    {
        return BusResource::make( Bus::findOrNew($request->id) );
    }

    public function profileView(Bus $bus)
    {
        return view('pages.bus-depot.profile',['bus'=>$bus]);
    }

    public function profileData(Request $request)
    {
        $bus = Bus::find($request->id);
        if(empty($bus))
            return response()->json(['success' => false]);
        return BusResource::make($bus);
    }

    public function deleteAction(Request $request)
    {
        $bus = Bus::findOrNew($request->id);
        if(!$bus->exists)
            return abort(404);
        $bus->delete();
        return redirect()->route('depotView');
    }

    public function howManyData(Request $request)
    {
        return response()->json(['quantity' => Bus::getAvailable($request->q, $request->stat_date, $request->end_date)]);
    }

    public function listTypesData(Request $request)
    {
        return BusTypeResource::collection(BusType::orderBy('name')->get());
    }

    public function listEuroNormsData(Request $request)
    {
        return BusEuroNormsResource::collection(EuroNorm::orderBy('name')->get());
    }

    public function fileTest(Request $request)
    {
        return view('pages.bus-depot.file-test');
    }

    public function storeFileAction(Request $request)
    {
        $file = File::findOrNew($request->id);
        $file->path = '/bus-documents/';
        $file->file = $request->file;
        $file->save();
        BusFile::store($request->bus_id, $file->id);
        return BusFileResource::make($file);
    }

    public function deleteFileAction(Request $request)
    {
        $file = File::find($request->id);
        if(empty($file)){
            return response()->json(['success' => false]);
        }
        BusFile::where('file_id', $file->id)->delete();
        $file->delete();
        return response()->json(['success' => true]);

    }
}
