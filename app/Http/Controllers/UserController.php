<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
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

        if($this->check_empty($req->name,$req->email,$req->phone,$req->pwd)){
            if($phoneLength == 10){
                $user = new User;
                $user->name = $req->name;
                $user->email = $req->email;
                $user->phone = $req->phone;
                $user->password = Hash::make($req->pwd);
                $user->role = 'user';
                $user->save();
                return $user->id;
            }else{
                $message = 'Phone number must have 10 digits';
            }
        }else{
            $message = 'Name, Email, Phone and Password cannot be empty';
        }
        return $message;
    }

    // This function for delete a user
    public function delete_users(Request $req){
        $user = User::find($req->id);
        $user->delete();
        return 'success';
    }

    // This function for update a user
    public function update_user(Request $req){
        $user = User::find($req->id);
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
