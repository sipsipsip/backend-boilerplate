<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model {

	protected $table = 't_pegawai';
	protected $primaryKey = 'nip';
	public $timestamps = false;

	public function careerplans(){
        return $this->hasMany('App\Models\RencanaKarir', 'creator');
    }

    public function surattugas(){
        return $this->belongsToMany('App\Models\SuratTugas', 'pegawai_surat_tugas', 'pegawai_id', 'surat_tugas_id');
    }

    public function jabatan_pegawai(){
        return $this->belongsTo('App\Models\Jabatan', 'kode_jabatan');
    }

    public function atasan(){
        return $this->jabatan->supervisor;
    }

    public function roles(){
        return $this->belongsToMany('App\Models\Role', 'pegawai_role', 'nip_pegawai', 'role_id');
    }


    // GETTERS

    public function getcareerplans($per_page = 2,$fields=['*'], $with=[]) {
        return $this->careerplans()->select($fields)->with($with)->paginate($per_page);
    }

    public function getsurattugas($per_page = 2, $fields=['*'], $with=[]){
        return $this->surattugas()->paginate($per_page);
    }

    public function getjabatanpegawai($per_page = 1, $fields=['*'], $with=[]){
        return $this->jabatan_pegawai;
    }

    public function getatasan($per_page = 1, $fields=['*'], $with=[]){
        return $pemegang = $this->jabatan_pegawai->supervisor->getpemegang($per_page, $fields, $with)[0];
    }

    public function getstaff($per_page = 10, $fields=['*'], $with=[]){
        return $this->jabatan_pegawai->getstaff($per_page, $fields, $with);
    }

    public function getstaffpegawai($per_page = 10, $fields=['*'], $with=[]){
        return $this->jabatan_pegawai->getstaffpegawai($per_page, $fields, $with);
    }

}
