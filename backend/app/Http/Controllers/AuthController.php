<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use App\Providers\AuthServicesProvider;

class AuthController extends Controller
{
    protected $authServicesProvider;

    public function _construct(authServicesProvider $authServicesProvider){
        $this->$authServicesProvider = $authServicesProvider;
    }

    public function register(Request $request){

        try{
            $rules =[
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth'=>'required|date',
                'place_of_birth' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ];
            $messages = [
                'first_name.required' => 'The first name is required.',
                'last_name.required' => 'The last name is required.',
                'date_of_birth.required' => 'The date of birth is required.',
                'date_of_birth.date' => 'The date of birth must be a valid date.',
                'place_of_birth.required' => 'The place of birth is required.',
                'street.required' => 'The street is required.',
                'city.required' => 'The city is required.',
                'country.required' => 'The country is required.',
                'email.required' => 'The email address is required.',
                'email.email' => 'The email address must be a valid email.',
                'email.unique' => 'The email address has already been taken.',
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);  
            }
           
           $response = AuthServicesProvider::signup($request->all());
    
            return response()->json($response, 201);
        }catch(Exception $e){
            return response()->json(["message"=>$e]);
        }
        
    }
    public function login(Request $request){
        try{
            $rules = [
                'email'=>'required|string|exists:users',
                'password'=>'required|string'
            ];
            $messages = [
                'email.required'=>'Email is required',
                'password.required'=>'Password is required'
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);  
            }
            $result = AuthServicesProvider::login($request->all());
            return response()->json([
                'message'=>'Logged in successfully',
                'data'=>$result
            ],200);
        }catch(Exception $e){

        }
    }
}
