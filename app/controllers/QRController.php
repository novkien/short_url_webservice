
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

        /**
     * Generate and Save QR Code
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.3
     * @param \Core\Request $request
     * @return void
     */
    public function save(Request $request){

/*

        if(Auth::user()->teamPermission('qr.create') == false && true){
            return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
		}
    
        if(!\Helpers\QR::typeExists($request->type)) return  back()->with('danger',  e('Invalid QR format or missing data'));

        if(!$request->name) return back()->with('danger', e('Please enter a name for your QR code.'));

        $count = DB::qrs()->where('userid', Auth::user()->rID())->count();

        $total = Auth::user()->hasLimit('qr');


*/

        try{
            if($request->type == 'file' && false){
            
                $input = call_user_func([\Helpers\QR::class, 'type'.ucfirst($request->type)]);
                $data = uploads('qr/files/'.$input);

            }else {
                $input = $request->{$request->type} ? $request->{$request->type} : $request->text; //$input = text da nhap vao

                
                $data = call_user_func([\Helpers\QR::class, 'type'.ucfirst($request->type)], clean($input));

            }  
        }  catch(\Exception $e){
            return back()->with('danger',  $e->getMessage());
        }


        $qrdata = [];

        $qrdata['type'] = clean($request->type);

        $qrdata['data'] = $input;



        if($request->mode == 'gradient'){
            $qrdata['gradient'] = [
                [clean($request->gradient['start']), clean($request->gradient['stop'])], 
                clean($request->gradient['bg']), 
                clean($request->gradient['direction'])
            ];
        } else {
            $qrdata['color'] = ['bg' => clean($request->bg), 'fg' => clean($request->fg)];
            
        }


        if($request->selectlogo){
            $qrdata['definedlogo'] = $request->selectlogo.'.png';
        }
        

        if($image = $request->file('logo')){
            
            if(!$image->mimematch || !in_array($image->ext, ['jpg', 'png'])) return Helper::redirect()->back()->with('danger', e('Logo must be either a PNG or a JPEG (Max 500kb).'));

            $filename = "qr_logo".Helper::rand(6).$image->name;

			$request->move($image, appConfig('app.storage')['qr']['path'], $filename);

            $qrdata['custom'] = $filename;
        }

        if($request->matrix){
            $qrdata['matrix'] = clean($request->matrix);
        }

        if($request->eye){
            $qrdata['eye'] = clean($request->eye);
            $qrdata['eyecolor'] = $request->eyecolor ?? null;
        }

        if($request->margin && is_numeric($request->margin) && $request->margin <= 10){
            $qrdata['margin'] = $request->margin;
        }

        $url = null;


        $alias = \substr(md5(rand(0,100).Helper::rand(12)), 0, 15);

        if(!in_array($request->type, ['text', 'sms','wifi','staticvcard'])){       
      
                       
            $url = DB::url()->create();
            $url->userid = Auth::user()->rID();
            $url->url = $data;
            $url->alias = \substr(md5(rand(0,100)), 0, 6);

            if($request->domain && $this->validateDomainNames(trim($request->domain), Auth::user(), false)){
                $url->domain = clean($request->domain);
            }

            $url->date = Helper::dtime();
            $url->save();
            
        }

        $qr = DB::qrs()->create();


        if (Auth::user() !== null && Auth::user()->rID() !== null ) {
            $qr->userid = Auth::user()->rID();
        } else {
            $qr->userid = 1;
        }


        $qr->alias = $alias;
        $qr->urlid = $url ? $url->id : null;
        $qr->name = clean($request->name);
        $qr->data = json_encode($qrdata);
        $qr->status = 1;
        $qr->created_at = Helper::dtime();
        $qr->save();

        if($url){

            $url->qrid = $qr->id;
            $url->save();
        }
        
        echo $qr->data.'<br>';
        echo $qr->urlid;


        return Helper::redirect()->to(route('qr.edit', [$qr->id]))->with('success',  e('QR Code has been successfully generated.'));

    }

        /**
     * Edit QR
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function edit(int $id){

        echo 'Edit '. $id .'<br>';

        if(!$qr = DB::qrs()->where('id', $id)->where('userid', 1)->first()){
            return back()->with('danger', 'QR does not exist.');
        }    

        
        $qr->data = json_decode($qr->data);
        
        $url = null;
        if($qr->urlid){
            $url = DB::url()->first($qr->urlid);
        }

        \Helpers\CDN::load("spectrum");
		
		View::push('<script type="text javascript">

	    				$("#bg").spectrum({
					        color: "'.(isset($qr->data->color->bg) ? $qr->data->color->bg : 'rba(255,255,255)').'",
					        showInput: true,
					        preferredFormat: "rgb"
						});	
                        $("#fg").spectrum({
					        color: "'.(isset($qr->data->color->fg) ? $qr->data->color->fg : 'rgb(0,0,0)').'",
					        showInput: true,
					        preferredFormat: "rgb"
						});
                    </script>', 'custom')->tofooter(); 

        if(\Helpers\QR::hasImagick()){
            View::push('<script type="text/javascript">
                            $("#gbg").spectrum({
                                color: "'.(isset($qr->data->gradient) ? $qr->data->gradient[1] : 'rgb(255,255,255)').'",
                                showInput: true,
                                preferredFormat: "rgb"
                            });	
                            $("#gfg").spectrum({
                                color: "'.(isset($qr->data->gradient) ? $qr->data->gradient[0][0] : 'rgb(0,0,0)').'",
                                showInput: true,
                                preferredFormat: "rgb"
                            });
                            $("#gfgs").spectrum({
                                color: "'.(isset($qr->data->gradient) ? $qr->data->gradient[0][1] : 'rgb(0,0,0)').'",
                                showInput: true,
                                preferredFormat: "rgb"
                            });
                            $("#eyecolor").spectrum({
                                color: "'.(isset($qr->data->eyecolor) ? $qr->data->eyecolor : '').'",
                                preferredFormat: "rgb",
                                allowEmpty:true                            
                            });
                        </script>', 'custom')->tofooter();                 
        }

        View::set('title', e("Edit QR").' '. $qr->name);

        $domains = false;
        if(!in_array($qr->data->type, ['text', 'sms','wifi','staticvcard'])){      
            $domains = [];
            foreach(array_reverse(\Helpers\App::domains(), true) as $domain){
                $domains[] = $domain;
            }  
        }
        
        return View::with('qr.edit', compact('qr', 'url', 'domains'))->extend('layouts.dashboard');
    }

}