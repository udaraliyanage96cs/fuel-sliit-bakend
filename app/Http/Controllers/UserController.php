<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Queue;
use Auth;

class UserController extends Controller
{

    
    public function get_users(Request $req){
        return "aaaa";
    }
    
    // This function for create a new user
    public function create_user(Request $req){

        $message = '';
        $phoneLength = Str::length($req->phone);
        if(User::Where('email',$req->email)->exists()){
            $message = 'Account Already Exists';
        }else{
            if($this->check_empty($req->name,$req->email,$req->phone,$req->pwd)){
                if($phoneLength == 10){
                    $user = new User;
                    $user->name = $req->name;
                    $user->email = $req->email;
                    $user->phone = $req->phone;
                    $user->password = Hash::make($req->pwd);
                    $user->role = 'user';
                    $user->save();
                    $message = 'success';
                }else{
                    $message = 'Phone number must have 10 digits';
                }
            }else{
                $message = 'Name, Email, Phone and Password cannot be empty';
            }
        }
        
        return response()->json(['message'=>$message]);
    }

    // This function for delete a user
    public function delete_users(Request $req){
        $user = User::find($req->id);
        $user->delete();
        return response()->json(['message'=>'success']);
    }

    // This function for update a user
    public function update_user(Request $req){
        $user = User::find($req->id);
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

    // This function for login a user
    public function login_user(Request $req){

        $message = '';
        $user_id = -1;
        $role = '';

        $userdata = array(
            'email'     => $req->email,
            'password'  => $req->password
        );
        if (Auth::attempt($userdata)) {
            $message = 'success';
            $user_id = Auth::user()->id;
            $role = Auth::user()->role;
        } else {        
            $message = 'faild';
        }

        return response()->json(['status'=>$message,'user'=>$user_id,'role'=>$role]);
    }
    
    
     // This function for get Vehicle
    public function get_vehicle(Request $req){
        $respond = "";
        if($req->id){
            $respond = Vehicle::join('users','users.id','=','vehicles.user_id')->join('fueltypes','fueltypes.id','=','vehicles.fueltype_id')
            ->where('users.id',$req->id)
            ->get(['vehicles.id as vid','vehicles.type','vehicles.vehicle_no','vehicles.capacity','fueltypes.name as ftype','users.name as uname','users.email','users.phone']);
        }else{
            $respond = Vehicle::join('users','users.id','=','vehicles.user_id')->join('fueltypes','fueltypes.id','=','vehicles.fueltype_id')->orderby('id','DESC')
              ->get(['vehicles.id as vid','vehicles.type','vehicles.vehicle_no','vehicles.capacity','fueltypes.name as ftype','users.name as uname','users.email','users.phone']);
        }
        return response()->json(['respond'=>$respond]);
    }

    // This function for create a new vehicle
    public function create_vehicle(Request $req){

        $message = '';
        if(Vehicle::Where('user_id',$req->id)->exists()){
            $message = 'Vehicle Already Exists';
        }else{
            $vehicle = new Vehicle;
            $vehicle->type = $req->type;
            $vehicle->vehicle_no = $req->vehicle_no;
            $vehicle->capacity = $req->capacity;
            $vehicle->fueltype_id = $req->fueltype_id;
            $vehicle->user_id = $req->id;
            $vehicle->save();
            $message = 'success';
        }
        
        return response()->json(['message'=>$message]);
    }

    // This function for delete a vehicle
    public function delete_vehicle(Request $req){
        $vehicle = Vehicle::find($req->id);
        $vehicle->delete();
        return response()->json(['message'=>'success']);
    }

    // This function for update a vehicle
    public function update_vehicle(Request $req){
        $vehicle = Vehicle::find($req->id);
        $vehicle->update([
            'type' => $req->type,
            'vehicle_no' => $req->vehicle_no,
            'capacity' => $req->capacity,
            'fueltype_id' => $req->fueltype_id,
        ]);
        return response()->json(['message'=>'success']);
    }
    
    // This function for get Vehicle SP
    public function get_vehicle_sp(Request $req){
        $respond = "";
        if($req->id){
            $respond = Vehicle::join('users','users.id','=','vehicles.user_id')
            ->where('vehicles.id',$req->id)
            ->first(['vehicles.id as vid','vehicles.type','vehicles.vehicle_no','vehicles.capacity','vehicles.fueltype_id','users.name as uname','users.email','users.phone']);
        }
        return response()->json(['respond'=>$respond]);
    }
    
     // This function for get Queue
    public function get_joinqueue(Request $req){
        $mytime = \Carbon\Carbon::now();
        $respond = "";
        $full = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
        ->join('stations','stations.id','=','queues.station_id')
        ->whereDate('queues.created_at',explode(" ",$mytime->toDateTimeString())[0])
        ->where('queues.station_id',$req->sid)
        ->where('queues.status',0)
        ->get(['stations.name as sname','vehicles.type','queues.no','queues.id as qid','stations.id as sid']);
        if($req->id){
            $respond = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
            ->join('stations','stations.id','=','queues.station_id')
            ->where('queues.user_id',$req->id)->where('queues.status',0)->whereDate('queues.created_at',explode(" ",$mytime->toDateTimeString())[0])
            ->first(['stations.name as sname','vehicles.type','queues.no','queues.id as qid','stations.id as sid']);
        }else{
            $respond = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
            ->join('stations','stations.id','=','queues.station_id')
            ->whereDate('queues.created_at',explode(" ",$mytime->toDateTimeString())[0])->where('queues.status',0)
            ->get(['stations.name as sname','vehicles.type','queues.no','queues.id as qid','stations.id as sid']);
        }
        return response()->json(['respond'=>$respond,'queue'=>count($full)]);
    }
    
    // This function for get Queue
    public function get_getqueue(Request $req){
        $mytime = \Carbon\Carbon::now();
        $respond = "";
        
        $respond = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
        ->join('stations','stations.id','=','queues.station_id')
        ->where('queues.user_id',$req->id)->where('queues.status',0)->whereDate('queues.created_at',explode(" ",$mytime->toDateTimeString())[0])
        ->first(['stations.name as sname','vehicles.type','queues.no','queues.id as qid','stations.id as sid']);

        $fullcount = "";
        if( $respond){
            $full = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
            ->join('stations','stations.id','=','queues.station_id')
            ->whereDate('queues.created_at',explode(" ",$mytime->toDateTimeString())[0])
            ->where('queues.station_id',$respond->sid)
            ->get(['stations.name as sname','vehicles.type','queues.no','queues.id as qid','stations.id as sid']);
            
            $fullcount = count($full);
        }else{
            $fullcount = 0;
        }

        return response()->json(['respond'=>$respond,'queue'=>$fullcount]);
    }
    
    
    // This function for join to the queue
    public function create_joinqueue(Request $req){

        $vehicle = Vehicle::where('user_id',$req->user_id)->first();
        $queue = new Queue;
        $no = 1;
        $message = "";
        if(Vehicle::where('user_id',$req->user_id)->exists()){
            if(Queue::where('vehicle_id',$vehicle->id)->whereDate('created_at',$req->date)->exists()){
                $message = "You Already In a Queue";
            }else{
                if(Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
                ->whereDate('queues.created_at',$req->date)->where('vehicles.type',$vehicle->type)->where('queues.station_id',$req->station_id)->exists()){
                    $curr = Queue::join('vehicles','vehicles.id','=','queues.vehicle_id')
                    ->whereDate('queues.created_at',$req->date)
                    ->where('vehicles.type',$vehicle->type)->where('queues.station_id',$req->station_id)->orderBy('queues.id', 'desc')->first();
                    $no = $curr->no + 1;
                }
        
        
                $queue->no = $no;
                $queue->user_id = $req->user_id;
                $queue->vehicle_id = $vehicle->id;
                $queue->fueltype_id = $vehicle->fueltype_id;
                $queue->station_id = $req->station_id;
                $queue->save();
                $message = 'success';
            }
        }else{
            $message = "Add your vehicle before join to the queue";
        }

        return response()->json(['respond'=>$message]);
    }
    
    // This function for delete a vehicle
    public function delete_joinqueue(Request $req){
        $queue = Queue::find($req->id);
        $queue->delete();
        return response()->json(['message'=>'success']);
    }

    public function get_users_list(Request $req){
        $user = User::where('role','user')->orderBy('id', 'desc')->get();
        return response()->json(['respond'=>$user]);
    }
    
}
