<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	protected $table = 'roles';

    public function member(){
        return $this->belongsToMany('App\Models\Pegawai', 'pegawai_role', 'role_id', 'nip_pegawai');
    }

}
