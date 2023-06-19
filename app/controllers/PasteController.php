<?php
/**
 * =======================================================================================
 *                           GemFramework (c) GemPixel                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  GemPixel. If you find that this framework is packaged in a software not distributed 
 *  by GemPixel or authorized parties, you must not use this software and contact GemPixel
 *  at https://gempixel.com/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package GemPixel\Premium-URL-Shortener
 * @author GemPixel (https://gempixel.com) 
 * @license https://gempixel.com/licenses
 * @link https://gempixel.com  
 */

use Core\Request;
use Core\Response;
use Core\DB;
use Core\Helper;
use Core\View;
use Core\Plugin;
use Core\Auth;
use Helpers\Gate;
use Models\User;

class Paste {
	
	use \Traits\Links;


	public function paste_box(string $alias){		
	
		View::set('title', e('Paste'));

        View::set('description', e('Easy archive and share your text simply'));



		$datas = DB::paste()->where('alias', $alias)->first();

        if(!$datas) return back()->with('danger', 'Paste does not exist.');
	  
		if (strtotime($datas->lifetime) < strtotime(Helper::dtime())) return back()->with('danger', 'Paste does not exist.');
		
		if ($datas->password != null) return View::with('paste.paste_box_pass', compact('datas'))->extend('layouts.main');




		
		return View::with('paste.paste_box', compact('datas'))->extend('layouts.main');     

	}

	public function paste_pass(Request $request){		
	
		View::set('title', e('Paste'));

        View::set('description', e('Easy archive and share your text simply'));

		$alias = $request->pasteAlias;
		$pass = md5($request->pastePass);


		$datas = DB::paste()->where('alias', $alias)->first();

        if(!$datas){
            return back()->with('danger', 'Paste does not exist.');
        } elseif ($datas->password == $pass) {
			return View::with('paste.paste_box', compact('datas'))->extend('layouts.main');     
		} else {
			return back()->with('danger', 'Wrong password');
		}


	

	}

	public function paste_send(Request $request){		
	
		//$alias = \substr(md5(rand(0,100)), 0, 8);
		$alias = \bin2hex(openssl_random_pseudo_bytes(4)); // get unique alias for url
		$pasteLife = $request->pasteLife; // get the value of the selected option
		
		while(DB::paste()->where('alias', $alias)->first()){
            $alias = \bin2hex(openssl_random_pseudo_bytes(4));
        }
		

		switch ($pasteLife) {
		  case 'forever':
			$timestamp = strtotime('+10 year'); // no expiration
			break;
		  case 'onehour':
			$timestamp = strtotime('+1 hour'); // add one hour to current time
			break;
		  case 'oneday':
			$timestamp = strtotime('+1 day'); // add one day to current time
			break;
		  case 'oneweek':
			$timestamp = strtotime('+1 week'); // add one week to current time
			break;
		  case 'onemonth':
			$timestamp = strtotime('+1 month'); // add one month to current time
			break;
		  default:
		   	$timestamp = strtotime('+10 year');
		}


		

		$data = DB::paste()->create();
		$data->name = clean($request->pasteAuthor);
		if ($request->pastePass == null) $data->password = null;
			else $data->password = md5($request->pastePass);
		$data->content = base64_encode($request->pasteContent);
		$data->lifetime =  date('Y-m-d H:i:s', $timestamp);
		$data->alias = $alias;
		$data->save();


		View::set('title', e('Paste'));

        View::set('description', e('Easy archive and share your text simply'));


        return Helper::redirect()->to(route('paste.paste_box', $alias))/* ->with('success',  e('QR Code has been successfully generated.')) */;

	}




	public function paste_raw(Request $request, string $alias, string $pass){

		$datas = DB::paste()->where('alias', $alias)->first();
		$text = htmlspecialchars(base64_decode($datas->content));


		if (!$datas) return back()->with('danger', 'Paste does not exist.');
		if ($datas->password != null && $pass != $datas->password && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) View::with('paste.paste_box_pass', compact('datas'))->extend('layouts.main');


		if ($pass == $datas->password && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) echo $text;
		elseif ($datas->password == null && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) echo $text;
		else return back()->with('danger', 'Paste does not exist.');

	}



	public function paste_download(Request $request, string $alias, string $pass){
		$datas = DB::paste()->where('alias', $alias)->first();

		if (!$datas) return back()->with('danger', 'Paste does not exist.');
		if ($datas->password != null && $pass != $datas->password && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) View::with('paste.paste_box_pass', compact('datas'))->extend('layouts.main');

		$text = base64_decode($datas->content);
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=" . $alias.".txt");
		header("Content-Length: " . strlen($text));

		if ($pass == $datas->password && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) echo $text;
		elseif ($datas->password == null && (strtotime($datas->lifetime) > strtotime(Helper::dtime()))) echo $text;
		else return back()->with('danger', 'Paste does not exist.');

	}

}