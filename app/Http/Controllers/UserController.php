<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try {
            if ($token = Auth::basic($credentials)) {
                
                return response()->json(compact('token'));
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['error' => 'invalid_credentials'], 401);
    }


    public function loginAPI(Request $request){
    	$login = User::where('username', $request['username'])->where('password', $request['password'])->first(['firstname','lastname', 'gender', 'date_of_birth']);
    	if ($login) {
    		return response()->json(array('success'=>'Login was successful.', 'data'=>$login, 'code'=>200));
    	} else {
    		return response()->json(['error'=>'invalid_credentials'], 401);
    	}
    	
    	
    }
    public function createUser(Request $request){
        if ($request['username'] == '') {
            return response()->json(array('error' => 'No Value was entered. Input a value in the textbox.'));
        } else {
            if (User::where('username', '=', Input::get('username'))->count() > 0) {
                return response()->json(array(
                    'error' => $request['username'] . " is already in the database."
                ));
            } else {
                $input = $request->all();
                $store = User::create($input);
                return response()->json(array(
                    'data'=>$store,
                    'code'=> 200
                ));
            }
        }
    }
    public function getAllUser($firstname, $sort, $page){
    	if (empty($firstname)) {
    		$getAllUser = User::all();
    	} else {
    		$getAllUser = User::orderBy('created_at', $sort)->where('firstname', $firstname)->limit($page)->get(['firstname', 'lastname', 'gender', 'date_of_birth', 'created_at', 'updated_at']);
    	}
    	return response()->json(array(
            'data' => $getAllUser,
            'code'=> 200
        ));
    }
    public function getUserById($id){
    	$getUserById = User::where('id', $id)->get(['firstname', 'lastname', 'gender', 'date_of_birth', 'created_at', 'updated_at']);
    	if($getUserById){
    		return response()->json(array(
                'data' => $getUserById,
                'code' => 200
            ));
    	}else{
    	return response()->json(['error' => 'Error fetching your record'], ['code'=>401]);
    }
    }
    public function editUser(Request $request, $id){
    	$editUser = User::where('id', $id)->update(['firstname' => $request['firstname'], 'lastname'=>$request['lastname']]);
    	if($editUser == 1){
    		return response()->json(User::select('id','firstname','lastname', 'gender', 'date_of_birth')->get());
    	}
    	return response()->json(['error'=>'Not Successful']);
    }
    public function deleteUser($id){
    	$deleteUser = User::find($id);
    	if($deleteUser == null){
    		return response()->json(['error'=>'No records found'],401);
    	}
    	$deleteUser->delete();
    	return response()->json(['success'=>$deleteUser]);
    }
}
