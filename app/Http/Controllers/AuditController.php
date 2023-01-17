<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\Queue;
use App\Models\Fueltype;
use App\Models\Station;
use App\Models\User;
use App\Models\Fuelcapacity;

class AuditController extends Controller
{
    
    // This function for get Audit
    public function get_audit(Request $req){
      
        $response = [
            "amount" => '',
            "filled" => '',
        ];
        if($req->role == "station"){
            $station = Station::where('user_id',$req->id)->first();
            $response = [
                "amount" => Audit::where('station_id',$station->id)->sum('amount'),
                "filled" => Audit::where('station_id',$station->id)->count()
            ];
        }else if($req->role == "user"){
            $response = [
                "amount" => Audit::where('user_id',$req->id)->sum('amount'),
                "filled" => Audit::where('user_id',$req->id)->count(),
                "list" => Audit::join('stations','stations.id','=','audits.station_id')
                ->join('fueltypes','fueltypes.id','=','audits.fueltype_id')->join('vehicles','vehicles.id','=','audits.vehicle_id')
                ->join('users','users.id','=','audits.user_id')->where('audits.user_id',$req->id)
                ->get(['audits.id as aid','users.name as uname','stations.name as sname', 'vehicles.type as vtype','vehicles.vehicle_no as vno','fueltypes.name as ftyoe','audits.amount','audits.qty','audits.created_at'])
            ];
        }

        return response()->json(['response'=>$response]);
    }

    // This function for create a new Audit
    public function create_audit(Request $req){
      
        $fueltype = Fueltype::find($req->fueltype_id);

        $audit = new Audit;
        $audit->qty = $req->qty;
        $audit->amount = $fueltype->price * $req->qty;
        $audit->user_id = $req->user_id;
        $audit->vehicle_id = $req->vehicle_id;
        $audit->station_id = $req->station_id;
        $audit->fueltype_id = $req->fueltype_id;
        $audit->save();

        $fuelcapacity = Fuelcapacity::where('fueltype_id',$req->fueltype_id)->where('station_id',$req->station_id)->first();
        $fuelcapacity->update([
            'current_qty' => $fuelcapacity->current_qty - $req->qty,
        ]);
        
        $queue = Queue::find($req->qid);
        $queue->update([
            'status' => 1,
        ]);

        $message = 'success';
        return response()->json(['message'=>$message]);
    }

    
}
