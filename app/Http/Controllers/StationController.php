<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Station;
use App\Models\User;
use Auth;

class StationController extends Controller
{
    // This function for get Stations
    public function get_stations(Request $req){
        if($req->id){
            return Station::join('users','users.id','=','stations.user_id')
            ->where('stations.id',$req->id)->first(['stations.id','stations.name','stations.location','stations.availability','users.name as uname','users.email','users.phone']);
        }else{
            return Station::join('users','users.id','=','stations.user_id')->get(['stations.id','stations.name','stations.location','stations.availability','users.name as uname','users.email','users.phone']);
        }
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
        return $message;
    }

    // This function for delete a Station with User
    public function delete_station(Request $req){
        $station = Station::find($req->id);
        $user = User::find($station->user_id);
        $user->delete();
        $station->delete();
        return 'success';
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
        return 'success';
    }

    
    // This function for check given fields are empty or not
    public function check_empty($name,$email,$phone,$pwd){
        if($name != '' && $name != null && $email != '' && $email != null && $phone != '' && $phone != null && $pwd != '' && $pwd != null){
            return true;
        }else{
            return false;
        }
    }
}
