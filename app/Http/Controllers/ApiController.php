<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Aplikasi;
use App\Models\AppTag;

class ApiController extends Controller {


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
     ** GET all applications
     *
     */

    public function apiApplications(){
        $aplikasi = Aplikasi::all();
        return $aplikasi;
    }



    /*
     ** GET current logged in user
     *
     */
    public function apiCurrentUser(){
        $user = [];
        $user = \Auth::user();
        $nip = $user->nip;


        $user = \App\User::with(['roles'])->find($nip);

        return $user;
    }


    /*
     ** ADD new application
     *
     */
    public function apiApplicationsAdd(){
        \Eloquent::unguard();
        $aplikasi = new \App\Models\Aplikasi();
        $aplikasi->nama_aplikasi = \Input::get('namaAplikasi');
        $aplikasi->url = \Input::get('urlAplikasi');
        $aplikasi->deskripsi = \Input::get('deskripsi');


        if(\Input::hasFile('image')){
            $image = \Input::file('image');
            $filename = rand(777,999999999).rand(111,44444444);
            $extension = $image->getClientOriginalExtension();
            $image->move('images/ikon_aplikasi/',$filename);
            $uploadedPath = $image->getRealPath();
            $aplikasi->icon_url = 'images/ikon_aplikasi/'.$filename;
        }



        if($aplikasi->save()){
           return 1;
        } else {
           return 0;
        }

    }

    /*
     ** UPDATE an application
     *
     */
    public function apiApplicationsUpdate($application_id){
        \Eloquent::unguard();
        $aplikasi = Aplikasi::findOrFail($application_id);
        $aplikasi->nama_aplikasi = \Input::get('namaAplikasi');
        $aplikasi->url = \Input::get('urlAplikasi');
        $aplikasi->deskripsi = \Input::get('deskripsi');

        // kalo ubah gambar juga
        if(\Input::hasFile('image')){
            $image = \Input::file('image');
            $filename = rand(777,999999999).rand(111,44444444);
            $extension = $image->getClientOriginalExtension();
            $image->move('images/ikon_aplikasi/',$filename);
            $uploadedPath = $image->getRealPath();
            $aplikasi->icon_url = 'images/ikon_aplikasi/'.$filename;
        }


        if($aplikasi->save()){
           return 1;
        } else {
           return 0;
        }

    }


    /*
     * DELETE an application
     *
     */
    public function apiApplicationsDelete($id){

        $application = Aplikasi::find($id);

        if(!$application){
            return 0;
        }

        if($application->delete()){
            return 1;
        } else {
            return 0;
        }
    }


    /*
     * FIND an application
     *
     */
    public function apiApplicationsFind($id){
        $application = Aplikasi::findOrFail($id);

        return $application;
    }


}
