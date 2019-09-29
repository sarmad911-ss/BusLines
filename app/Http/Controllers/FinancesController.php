<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurposeResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\trip\TripFileResource;
use App\Models\core\File;
use App\Models\Transaction;
use App\Models\TransactionPurpose;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class FinancesController extends Controller
{
    function listView()
    {
        return view("pages.finances.list");
    }

    function listData(Request $request)
    {
        return TransactionResource::collection(Transaction::orderByDesc('id')->paginate(config('app.paginate')));
    }

    function storeData(Request $request)
    {
        return TransactionResource::make(Transaction::findOrNew($request->id));
    }

    function storeAction(Request $request)
    {
        if(empty($request))
            return false;
        $transaction = Transaction::findOrNew($request->id);
        $transaction->fill($request->all());
        /** Store file */
        if(empty($transaction->file_id)){
            /** generate transaction file */
//            $file = $transaction->storeRG();
//            $transaction->file_id = $file->id;
        }else{
            /** replace file */
            if(($request->file['file'] ?? null) instanceof UploadedFile){
                $file = File::findOrNew($request->file_id ?? null);
                $file->path = '/finances/';
                $file->file = $request->file['file'] ?? null;
                $file->save();
                $transaction->file_id = $file->id;
            }

        }
        $transaction->save();
        return TransactionResource::make($transaction);
    }

    public function deleteAction(Request $request)
    {
        if(empty($request))
            return false;
        $transaction = Transaction::find($request->id);
        $file = File::find($transaction->file_id ?? null);
        if(!empty($file)){
            $file->delete();
        }
        $transaction->delete();
        return response()->json(['success' => true]);
    }

    public function deleteFileAction(Request $request)
    {
        $file = File::find($request->id);
        if(empty($file)){
            return response()->json(['success' => false]);
        }
        $file->delete();
        return response()->json(['success' => true]);

    }

    public function listPurposeData(Request $request)
    {
        return PurposeResource::collection(TransactionPurpose::orderBy('name')->get());
    }

    public function storeRGAction(Request $request)
    {
        if(empty($request->transaction_id)){
            return response()->json(['error' => "Empty transaction id"]);
        }
        $transaction = Transaction::find($request->transaction_id);
        if(empty($transaction)){
            return response()->json(['error' => "No such transaction by id $request->transaction_id"]);
        }
        $file = $transaction->storeRG();
        return TripFileResource::make($file);
    }

}
