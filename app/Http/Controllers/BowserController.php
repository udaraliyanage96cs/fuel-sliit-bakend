<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Bowser;
use App\Models\User;
use Auth;

class BowserController extends Controller
{

    // This function for get Bowsers
    public function get_bowser(Request $req){
        if($req->id){
            return Bowser::join('users','users.id','=','bowsers.user_id')
            ->where('bowsers.id',$req->id)->first(['bowsers.id','bowsers.name','bowsers.vehicle_no','bowsers.curent_location','bowsers.capacity','users.name as uname','users.email','users.phone']);
        }else{
            return Bowser::join('users','users.id','=','bowsers.user_id')->get(['bowsers.id','bowsers.name','bowsers.vehicle_no','bowsers.curent_location','bowsers.capacity','users.name as uname','users.email','users.phone']);
        }
    }

    // This function for create a new Bowser with user
    public function create_bowser(Request $req){

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
                $bowser = new Bowser;
                $bowser->name = $req->bname;
                $bowser->vehicle_no = $req->vehicle_no;
                $bowser->capacity = $req->capacity;
                $bowser->curent_location = $req->location;
                $bowser->user_id = $user->id;
                $bowser->station_id = $req->station_id;
                $bowser->save();

                $message = 'Bowser successfully created';


            }else{
                $message = 'Phone number must have 10 digits';
            }
        }else{
            $message = 'Name, Email, Phone and Password cannot be empty';
        }
        return $message;
    }
    
    // This function for delete a Bowser with User
    public function delete_bowser(Request $req){
        $bowser = Bowser::find($req->id);
        $user = User::find($bowser->user_id);
        $user->delete();
        $bowser->delete();
        return 'success';
    }

    // This function for update a Bowser with user
    public function update_bowser(Request $req){

        $bowser = Bowser::find($req->id);
        $user = User::find($bowser->user_id);

        $bowser->update([
            'name' => $req->bname,
            'vehicle_no' => $req->vehicle_no,
            'capacity' => $req->capacity,
            'curent_location' => $req->location,
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
