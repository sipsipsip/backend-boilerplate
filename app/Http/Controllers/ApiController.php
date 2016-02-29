<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class ApiController extends Controller {

    public function getNode($node, $id = null, $edge = null){

        $baseModelClass = 'App\\Models\\'.$node;
        $per_page = \Input::get('per_page') ? \Input::get('per_page') : 10;
        $result = null;

        $fields = ['*'];
        if(\Input::get('fields')){
            $fields = \Input::get('fields');
            $fields = explode(",",$fields);
        }

        $with = [];
        if(\Input::get('with')){
            $with = \Input::get('with');
            $with = explode(",", $with);
        }

        if($edge){
            $edge = 'get'.$edge;
            $result = $baseModelClass::find($id);
            $result = $result->$edge($per_page, $fields, $with);

            return $result;
        }


        if($id){
            $result = $baseModelClass::select($fields);

            if($with){
                $result = $result->with($with);
            }

            $result = $result->find($id);

        } else {
            $result = $baseModelClass::select($fields);

            if($with){
                $result = $result->with($with);
            }
            $result = $result->paginate($per_page);
        }

        return $result;
    }

   /*
    * GET DATA ENDPOINT
    *
    */
    public function getData(){
         $result;

          // Get the query params
         $page = \Input::get('page') ? \Input::get('page') : 1;
         $per_page = \Input::get('per_page') ? \Input::get('per_page') : 100;
         $sort_by = \Input::get('sort_by') == NULL ? NULL : \Input::get('sort_by');
         $q = \Input::get('q');
         $q_identifier = \Input::get('q_identifier');
         $modelClass = \Input::get('model');
         $modelClass = ucfirst($modelClass);
         $modelClass = 'App\\Models\\'.$modelClass;
         $with = \Input::get('with');


         if(\Input::get('model') == 'user'){
            $modelClass = 'App\\'.ucfirst(\Input::get('model'));
         }

         $result = $modelClass::paginate($per_page);

         // Handle relation on Non Searching
         if($with){
           $result = $modelClass::with($with)->paginate($per_page);
         }


         // Handle searching based on keyword

         if($q){
             $result = $modelClass::where($q_identifier, 'like', '%'.$q.'%');

             // Handle relation on Searching
             if($with){
                $result = $modelClass::with($with)->where($q_identifier, 'like', '%'.$q.'%');
             }

             $result = $result->paginate($per_page);
         }


         return $result;
    }
    
    
    /*
     ** GET current logged in user
     *
     */
    public function apiCurrentUser(){
        $user = [];
        $user = \Auth::user();
        $nip = $user->nip;
        $user = \App\Models\Pegawai::with('roles')->select(['nip', 'nama'])->find($nip);

        return $user;
    }

}
