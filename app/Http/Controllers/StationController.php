<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Station;
use App\Models\User;
use App\Models\Fuelcapacity;
use Auth;

class StationController extends Controller
{
    // This function for get Stations
    public function get_stations(Request $req){
        $respond = "";
        if($req->id){
            $respond = Station::join('users','users.id','=','stations.user_id')
            ->where('stations.id',$req->id)
            ->first(['stations.id','stations.name','stations.location','stations.availability','users.name as uname','users.email','users.phone']);
        }else{
            $respond = Station::join('users','users.id','=','stations.user_id')->orderby('id','DESC')
            ->get(['stations.id','stations.name','stations.location','stations.availability','users.name as uname','users.email','users.phone']);
        }
        return response()->json(['respond'=>$respond]);
    }

    // This function for create a new Station with user
    public function create_station(Request $req){

        $message = '';
        $phoneLength = Str::length($req->phone);

        if($this->check_empty($req->name,$req->email,$req->phone,$req->pwd)){
            if($phoneLength == 10){
                //create new User
                $user = new User;
                $user->name = $req->name;
                $user->email = $req->email;
                $user->phone = $req->phone;
                $user->password = Hash::make($req->pwd);
                $user->role = 'station';
                $user->save();

                //create new Station
                $station = new Station;
                $station->name = $req->sname;
                $station->location = $req->location;
                $station->user_id = $user->id;
                $station->save();

                $message = 'Station successfully created';


            }else{
                $message = 'Phone number must have 10 digits';
            }
        }else{
            $message = 'Name, Email, Phone and Password cannot be empty';
        }
        return response()->json(['message'=>$message]);
    }

    // This function for delete a Station with User
    public function delete_station(Request $req){
        $station = Station::find($req->id);
        $user = User::find($station->user_id);
        $user->delete();
        $station->delete();
        return response()->json(['message'=>'success']);
    }

    // This function for update a Station with user
    public function update_station(Request $req){

        $station = Station::find($req->id);
        $user = User::find($station->user_id);

        $station->update([
            'name' => $req->sname,
            'location' => $req->location,
            'availability' => $req->availability,
        ]);

        $user->update([
            'name' => $req->name,
            'phone' => $req->phone,
        ]);
        return response()->json(['message'=>'success']);
    }

    
    // This function for check given fields are empty or not
    public function check_empty($name,$email,$phone,$pwd){
        if($name != '' && $name != null && $email != '' && $email != null && $phone != '' && $phone != null && $pwd != '' && $pwd != null){
            return true;
        }else{
            return false;
        }
    }

    // This function for get Stocks for specific station
    public function get_stocks(Request $req){
        $station = Station::where('user_id',$req->id)->first();
        $stocks = Fuelcapacity::join('fueltypes','fueltypes.id' ,'=', 'fuelcapacities.fueltype_id')->where('fuelcapacities.station_id',$station->id)
        ->orderby('fuelcapacities.id','DESC')->get(['fueltypes.name','fuelcapacities.id as fcid','fuelcapacities.ini_qty','fuelcapacities.current_qty']);
        return response()->json(['respond'=>$stocks]);
    }

    // This function for get specific Stocks
    public function get_specific_stocks(Request $req){
        $stocks = Fuelcapacity::join('fueltypes','fueltypes.id' ,'=', 'fuelcapacities.fueltype_id')->where('fuelcapacities.id',$req->id)
        ->orderby('fuelcapacities.id','DESC')->get(['fueltypes.name','fuelcapacities.id as fcid','fuelcapacities.ini_qty','fuelcapacities.current_qty']);
        return response()->json(['respond'=>$stocks]);
    }
    
    // This function for delete specific Stocks
    public function delete_stocks(Request $req){
        $stocks = Fuelcapacity::find($req->id);
        $stocks->delete();
        return response()->json(['respond'=>$stocks]);
    }

    // This function for delete specific Stocks
    public function update_stocks(Request $req){
        $stocks = Fuelcapacity::find($req->id);
        $stocks->update([
            'current_qty' => $req->current_qty
        ]);
        return response()->json(['message'=>'success']);
    }
    
    
}
