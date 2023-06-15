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
use Core\DB;
use Core\Auth;
use Core\Helper;
use Core\View;
use Models\User;

class QR {

    /**
     * Generate QR
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function generate(string $alias){

        if(!$qr = DB::qrs()->where('alias', $alias)->first()){
            die();
        }
        
        $qr->data = json_decode($qr->data);

        if($qr->urlid && $url = DB::url()->where('id', $qr->urlid)->first()){        
            $data = ['type' => 'link', 'data' =>  \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom)];
        } else {        
            $data = ['type' => $qr->data->type, 'data' => $qr->data->data];
        }

        try {

            if($qr->filename && file_exists(View::storage($qr->filename, 'qr'))){
                header('Location: '.uploads($qr->filename, 'qr'));
                exit;
            }

            $margin = isset($qr->data->margin) && is_numeric($qr->data->margin) && $qr->data->margin <= 10 ? $qr->data->margin : 0;

            $data = \Helpers\QR::factory($data, 400, $margin)->format('png');

            if(isset($qr->data->gradient)){
                if(isset($qr->data->eyecolor) && $qr->data->eyecolor){
                    $qr->data->gradient[] = $qr->data->eyecolor;
                }

                $data->gradient(...$qr->data->gradient);

            } else {
                $data->color($qr->data->color->fg, $qr->data->color->bg, $qr->data->eyecolor ?? null);
            }

            if(isset($qr->data->matrix)){
                $data->module($qr->data->matrix);
            }

            if(isset($qr->data->eye)){
                $data->eye($qr->data->eye);
            }
            

            if(isset($qr->data->definedlogo) && $qr->data->definedlogo){
                $data->withLogo(PUB.'/static/images/'.$qr->data->definedlogo, ($margin > 0) ? (80 - $margin*4) : 80);
            }  

            if(isset($qr->data->custom) && $qr->data->custom && file_exists(View::storage($qr->data->custom, 'qr'))){
                $data->withLogo(View::storage($qr->data->custom, 'qr'), ($margin > 0) ? (80 - $margin*4) : 80);
            }

            $qr->filename = $qr->alias.\Core\Helper::rand(6).'.png';
            $qr->data = json_encode($qr->data);
            $qr->save();

            $data->create('file', appConfig('app.storage')['qr']['path'].'/'.$qr->filename);
            
            $data->create('raw');

        } catch(\Exception $e){
            return \Core\Response::factory($e->getMessage())->send();
        }
    }

    /**
	 * Download QR
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @param string $alias
	 * @param string $format
	 * @param integer $size
	 * @return void
	 */
	public function download(Request $request, string $alias, string $format, int $size = 300){
		
        if(!$qr = DB::qrs()->where('alias', $alias)->first()){
            stop(404);
        }
        
        $qr->data = json_decode($qr->data);

        if($qr->urlid && $url = DB::url()->where('id', $qr->urlid)->first()){        
            $data = ['type' => 'link', 'data' =>  \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom)];
        } else {        
            $data = ['type' => $qr->data->type, 'data' => $qr->data->data];
        }
		
        $qrsize = 300;

        $margin = isset($qr->data->margin) && is_numeric($qr->data->margin) && $qr->data->margin <= 10 ? $qr->data->margin : 0;

		if(is_numeric($size) && $size > 50 && $size <= 1000) $qrsize = $size;
		
		$data = \Helpers\QR::factory($data, $qrsize, $margin)->format($format);

        if(isset($qr->data->gradient)){
            
            if(isset($qr->data->eyecolor) && $qr->data->eyecolor){
                $qr->data->gradient[] = $qr->data->eyecolor;
            }

            $data->gradient(...$qr->data->gradient);
        } else {
            $data->color($qr->data->color->fg, $qr->data->color->bg, $qr->data->eyecolor ?? null);
        }

        if($qr->data->matrix){
            $data->module($qr->data->matrix);
        }

        if($qr->data->eye){
            $data->eye($qr->data->eye);
        }
        $baseLogoSize = ($margin > 0) ? (80 - $margin*4) : 80;

        if(isset($qr->data->definedlogo) && $qr->data->definedlogo){
            $data->withLogo(PUB.'/static/images/'.$qr->data->definedlogo, $baseLogoSize * $qrsize/300);
        }  

        if(isset($qr->data->custom) && $qr->data->custom && file_exists(View::storage($qr->data->custom, 'qr'))){
            $data->withLogo(View::storage($qr->data->custom, 'qr'), $baseLogoSize);
        }

		return \Core\File::contentDownload('QR-code-'.$alias.'.'.$data->extension(), function() use ($data) {
			return $data->string();
		});
	}
}