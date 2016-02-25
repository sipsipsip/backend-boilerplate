<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CRUDController extends Controller {

	/** to get data just use the /api/vx/data **/
	/*** from ApiController@getData ***/

	/** add new Model **/
     	public function getAdd(){
     	    \Eloquent::unguard();
             // Get the query params
     	     $input = \Input::except(['model', 'btm', 'hm']);

             /** belongsTo relationship automatically handled here **/
             $modelClass = \Input::get('model');
             $modelClass = ucfirst($modelClass);
             $modelClass = 'App\\Models\\'.$modelClass;



             $result = [];

             if($objectModel = $modelClass::create($input)){

                 /** handle belongsToMany relationship **/
                 if(\Input::get('btm')){
                     // btm is belongsToMany, the value is model of belong and its ids
                     $btm = \Input::get('btm');
                     foreach($btm as $owner => $ids){
                         $objectModel->$owner()->attach($ids);
                     }
                 }


                 /** handle hasMany relationship **/
                 /** this one is only enhanced feature right now,
                     making it possible to add new related object at the same time of
                     parent creation **/
                 /** most of the time when you want to add new has many object, you'll do it
                     using belongsTo which is standard, just specify the id of the parent as
                     params **/

                 if(\Input::get('hm')){
                     // hm stands for hasMany, the value is models of belonging and its ids
                     $hm = \Input::get('hm');
                     $rels = [];
                     foreach($hm as $owned => $newhasmanyobject){
                         foreach($newhasmanyobject as $obj){
                            $relatedModel = 'App\\Models\\'.$owned;
                            $o = new $relatedModel($obj);
                            $rels[] = $o;
                         }

                         // save related has many models
                         $objectModel->$owned()->saveMany($rels);
                     }

                 }

                 $result['message'] = 'Berhasil membuat '.\Input::get('model');
                 $result['error'] = FALSE;
                 $result['success'] = TRUE;
                 return $result;
             } else {
                 $result['message'] = 'Terjadi kesalahan! '.\Input::get('model');
                 $result['error'] = TRUE;
                 $result['success'] = FALSE;
                 return $result;
             }
     	}



	/** add new Model **/
     	public function getUpdate($id){
     	    \Eloquent::unguard();

             // Get the query params
     	     $input = \Input::except(['model', 'btm', 'hm']);

             /** belongsTo relationship automatically handled here **/
             $modelClass = \Input::get('model');
             $modelClass = ucfirst($modelClass);
             $modelClass = 'App\\Models\\'.$modelClass;

              if(\Input::get('model') == 'user'){
                 $modelClass = 'App\\'.ucfirst(\Input::get('model'));
              }



             $result = [];

             // find the object edited
             // if not found exit early
             if(!$modelClass::find($id)){
                $result['message'] = 'Model not found!';
                $result['error'] = TRUE;
                $result['success'] = FALSE;
                return $result;
             }

             $objectModel = $modelClass::find($id);

             if($objectModel->update($input)){

                 /** handle belongsToMany relationship **/
                 if(\Input::get('btm')){
                     // btm is belongsToMany, the value is model of belong and its ids
                     $btm = \Input::get('btm');
                     foreach($btm as $owner => $ids){
                         $objectModel->$owner()->sync($ids);
                     }
                 }


                 /** handle hasMany relationship **/
                 /** this one is only enhanced feature right now,
                     making it possible to add new related object at the same time of
                     parent creation **/
                 /** most of the time when you want to add new has many object, you'll do it
                     using belongsTo which is standard, just specify the id of the parent as
                     params **/

                 if(\Input::get('hm')){
                     // hm stands for hasMany, the value is models of belonging and its ids
                     $hm = \Input::get('hm');
                     $rels = [];
                     foreach($hm as $owned => $newhasmanyobject){
                         foreach($newhasmanyobject as $obj){
                            $relatedModel = 'App\\Models\\'.$owned;
                            $o = new $relatedModel($obj);
                            $rels[] = $o;
                         }

                         // delete already attached has many models
                         $objectModel->$owned()->delete();

                         // save related has many models
                         $objectModel->$owned()->saveMany($rels);
                     }

                 }

                 $result['message'] = 'Berhasil mengupdate '.\Input::get('model');
                 $result['error'] = FALSE;
                 $result['success'] = TRUE;
                 return $result;
             } else {
                 $result['message'] = 'Terjadi kesalahan! '.\Input::get('model');
                 $result['error'] = TRUE;
                 $result['success'] = FALSE;
                 return $result;
             }
     	}


    /*
     * DELETE
     *
     */
     public function getDelete($id){
         // Get the query params
         $input = \Input::except(['model', 'btm', 'hm']);

         /** belongsTo relationship automatically handled here **/
         $modelClass = \Input::get('model');
         $modelClass = ucfirst($modelClass);
         $modelClass = 'App\\Models\\'.$modelClass;

         /** if not found exit early **/
         if(!$modelClass::find($id)){
             $result = [];
             $result['message'] = 'Tidak menemukan '.\Input::get('model');
             $result['error'] = TRUE;
             $result['success'] = FALSE;
             return $result;
         }

         if($object = $modelClass::find($id)){
            /**  delete related has Many first **/
            if($hm = \Input::get('hm')){
                foreach($hm as $owned => $values){
                    $owneds = $object->$owned();
                    foreach($owneds as $own){
                        $own->delete();
                    }
                }
            }

            /** finally, delete the object **/
            if($object->delete()){
                $result = [];
                $result['message'] = 'Berhasil mengapus '.\Input::get('model');
                $result['error'] = FALSE;
                $result['success'] = TRUE;
                return $result;
            } else {
                $result = [];
                $result['message'] = 'Gagal menghapus '.\Input::get('model');
                $result['error'] = TRUE;
                $result['success'] = FALSE;
                return $result;
            }
         }

     }




}
