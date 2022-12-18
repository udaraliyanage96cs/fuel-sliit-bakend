<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fueltype;
use App\Models\Fuelcapacity;
use App\Models\Station;

class FuelController extends Controller
{
    // This function for get Fuel Types
    public function get_fuel_type(Request $req){
        $fueltype = "";
        if($req->id){
            $fueltype = Fueltype::find($req->id);
        }else{
            $fueltype = Fueltype::all()->orderby('id','DESC');

        }
        return response()->json(['respond'=>$fueltype]);
    }

    
   

    // This function for create a new Fuel type
    public function create_fuel_type(Request $req){
        $message = '';

        if($this->check_empty($req->name,$req->price)){
            //create new User
            $fueltype = new Fueltype;
            $fueltype->name = $req->name;
            $fueltype->price = $req->price;
            $fueltype->save();

            $message = 'Fuel Type successfully created';
        }else{
            $message = 'Type or Price cannot be empty';
        }
        return response()->json(['message'=>$message]);
    }

    // This function for delete a Fuel type
    public function delete_fuel_type(Request $req){
        $fueltype = Fueltype::find($req->id);
        $fueltype->delete();
        return response()->json(['message'=>'success']);
    }

    // This function for update a Fuel type
    public function update_fuel_type(Request $req){

        $fueltype = Fueltype::find($req->id);

        $fueltype->update([
            'name' => $req->name,
            'price' => $req->price,
        ]);

        return response()->json(['message'=>'success']);
    }

    // This function for check given fields are empty or not
    public function check_empty($name,$price){
        if($name != '' && $name != null && $price != '' && $price != null){
            return true;
        }else{
            return false;
        }
    }

    // This function for get Fuel Types For Dropdowns
    public function get_capacity_fuel_type(Request $req){
        $fueltype = Fueltype::get(['name as label','price as value'])->orderby('id','DESC');
        return response()->json(['respond'=>$fueltype]);
    }

    // This function for create a new Fuel type
    public function create_fuel_capacity(Request $req){

        $station = Station::where('user_id',$req->uid)->first();
        $message = '';

        if(Fuelcapacity::Where('fueltype_id',$req->fid)->Where('station_id',$station->id)->exists()){
            $message = 'Already Created';
        }else{
            //create new Stock
            $fuelcapacity = new Fuelcapacity;
            $fuelcapacity->fueltype_id = $req->fid;
            $fuelcapacity->ini_qty = $req->qty;
            $fuelcapacity->current_qty = $req->qty;
            $fuelcapacity->station_id = $station->id;
            $fuelcapacity->save();

            $message = 'success';
        }

        return response()->json(['message'=>$message]);
    }

    
}