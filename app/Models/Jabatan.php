<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model {

    protected $table = 't_kode_jabatan';

    public function unit2(){}
	public function unit3(){}
	public function unit4(){}

	public function pemegang(){
	    return $this->hasMany('App\Models\Pegawai', 'kode_jabatan');
	}
	public function supervisor(){
	    return $this->belongsTo('App\Models\Jabatan', 'atasan');
	}
    public function staff(){
        return $this->hasMany('App\Models\Jabatan', 'atasan');
    }

    public function staffpegawai(){
        return $this->hasManyThrough('App\Models\Pegawai', 'App\Models\Jabatan', 'atasan', 'kode_jabatan');
    }


    // GETTERS
    public function getatasan($per_page = 10, $fields=['*'], $with=[]){
        return $this->supervisor()->select($fields)->with($with)->paginate($per_page);
    }

    public function getpemegang($per_page = 10, $fields=['*'], $with=[]){
        return $this->pemegang()->select($fields)->with($with)->paginate($per_page);
    }

    public function getstaff($per_page = 10, $fields= ['*'], $with=[]){
        return $this->staff()->with($with)->select($fields)->paginate($per_page);
    }

    public function getstaffpegawai($per_page = 10, $fields=['*'], $with=[]){
        return $this->staffpegawai()->select($fields)->with($with)->paginate($per_page);
    }

}
