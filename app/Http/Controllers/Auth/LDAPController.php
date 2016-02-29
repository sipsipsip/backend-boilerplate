<?php namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LDAPController extends Controller {

	// Show login form LDAP
	public function getLogin()
	{
//        $key = rand(777,9999);
//        \Session::put('login_key', $key);
//        $return_url = 'http://localhost:3000/kantor/pola-karir/public/app';
//        $remote_auth = 'http://localhost:3000/kantor/pola-karir/public/remote-auth';
//	    return \Redirect::to('http://localhost:3000/kantor/accounts/public/check-auth?key='.$key.'&return_url='.$return_url.'&remote_auth='.$remote_auth);

		return view('auth/ldap');
	}



	// Process LDAP Login
	public function postLogin()
	{
		$username = \Request::get('username');
		$username_kemenkeu = "kemenkeu\\".$username;
		$password = \Request::get('password');

		$ldapconn = ldap_connect ('kemenkeu.go.id') or die('can not connect'); //

		if($ldapconn){
			$ldapbind = ldap_bind($ldapconn, $username_kemenkeu, $password) or die(' wrong credential');

			if($ldapbind){
				$user = \App\User::where('kemenkeu', $username)->first();
				\Auth::loginUsingId($user->id);
				return \Redirect::to('/');
			}
		}
	}

	public function getLogout(){
	    \Auth::logout();
	    return \Redirect::to("/");
	}

}
