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
	
	use Traits\Paste;
	
	/**
	* Static path to grab favicon
	*/
   	const ICOPATH = "https://icons.duckduckgo.com/ip3/{{url}}.ico";	

	/**
	 * Add paste text
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function paste_box(string $alias){		
	
		View::set('title', e('Paste'));

        View::set('description', e('Easy archive and share your text simply'));




		//$data = DB::paste()->where('alias', $alias)->first();


        if(!$data = DB::paste()->where('alias', $alias)->first()){
            return back()->with('danger', 'Paste does not exist.');
        }    


		








		echo 'Test<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';

		//echo 'Debug:<br>'.'<br>'. var_dump($data);

		echo 'Data->name '.$data->name;

		$data[] = $data;



		return View::with('paste.paste_box', compact('data'))->extend('layouts.main');     

	}

	/**
	 * Add paste send data
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function paste_send(Request $request){		
	
		$alias = \substr(md5(rand(0,100)), 0, 8); // get unique alias for url
		$pasteLife = $request->pasteLife; // get the value of the selected option
		
		
		
		
		
		
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
		   	$timestamp = strtotime(Helper::dtime());
		}


		

		$data = DB::paste()->create();
		$data->name = clean($request->pasteAuthor);
		$data->password = clean($request->pastePass);
		$data->content = base64_encode($request->pasteContent);
		$data->lifetime =  date('Y-m-d H:i:s', $timestamp);
		$data->isOneTimeOpen = ($request->pasteLife == 'oneload') ? 1 : 0;
		$data->alias = $alias;
		$data->save();


		View::set('title', e('Paste'));

        View::set('description', e('Easy archive and share your text simply'));

		//$data = DB::paste()->where('alias', $alias)->first();

        return Helper::redirect()->to(route('paste.paste_box', [$data->alias]))/* ->with('success',  e('QR Code has been successfully generated.')) */;

	}
    /**
     * Redirect Link
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function redirect(Request $request, string $alias){

		if(!$url = $this->getURL($request, $alias)){
			return $this->notFound($request);
		}

		// Plugin
		Plugin::dispatch('link.redirect', $url);

		// Check if URL is disabled
		if(!$url->status) return Gate::inactive();

		// Check blacklist domain
		if(!$url->qrid && !$url->profileid && ($this->domainBlacklisted($url->url) || $this->wordBlacklisted($url->url))) return Gate::disabled();

		// Check with Google Web Risk
		if(!$url->qrid && !$url->profileid && !$this->safe($url->url)) {
			$url->status = 0;
			$url->save();
			return Gate::disabled();
		}

		// Check with Phish
		if(!$url->qrid && !$url->profileid && $this->phish($url->url)) {
			$url->status = 0;
			$url->save();
			return Gate::disabled();
		}
		
		// Check with VirusTotal
		if(!$url->qrid && !$url->profileid && $this->virus($url->url)) {
			$url->status = 0;
			$url->save();
			return Gate::disabled();
		}			

		// Password check is stored in a session. User will have access until the browser is closed.
		if($request->isPost() && $request->password){
			// if encrypted Password (old version)
			if(strlen($url->pass) == 32) $request->password = md5($request->password);

			// Check Password
			if($request->password != $url->pass){
				return back()->with("danger", e("The password is invalid or does not match."));
			}

			// Set Session after successful attempt
			$request->session("{$url->id}_passcheck", true);
		}	

		// Let's check if it is password-protected
		if(!empty($url->pass) && $request->session("{$url->id}_passcheck") == false) return Gate::password($url);

		if($url->profileid && $profile = DB::profiles()->where('id', $url->profileid)->first()){
			$this->updateStats($request, $url, null);
			return Gate::profile($profile, null, $url);
		}
		
		// Check if expired
		if(!empty($url->expiry) && strtotime("now") > strtotime($url->expiry)) return Gate::expired();			

		$user = null;

		// Get User info
		if($url->userid != 0 && $user = \Models\User::first($url->userid)){			
			
			// Disable URLs of user is banned
			if($user->banned) return stop(404);

			// If membership expired, switch to free
			if($user->pro && time() > strtotime($user->expiration)){
				$user->pro = 0;
				$user->trial = 0;
				$user->save();
			}
			
			$hasMedia = $user->media;
			$isPro = $user->admin ? 1 : $user->pro;

		}else{

			$hasMedia = config('show_media');
			$isPro = 0;

		}

		if(!config("pro")){
			$isPro = 1;
		}

		// Update Stats
		$this->updateStats($request, $url, $user);
		
		// Check if URL is geo targeted
		if(!empty($url->location) && config("geotarget")){			

			$geo = $request->country();

			$country = strtolower($geo['country']);
			$state = strtolower($geo['state']);
			$location = json_decode($url->location, true);

			if($country && isset($location[$country])) {
				$redirect = isset($location[$country]['all']) ? $location[$country]['all'] : $location[$country];
			}	
			
			if($state && isset($location[$country][$state])) {
				$redirect = $location[$country][$state];
			}
			if(isset($redirect) && !is_array($redirect)) $url->url = $redirect;
		}

		// Check if URL is device targeted
		if(!empty($url->devices) && config("devicetarget")){
			
			if(strpos($request->device(), ' ') !== false){
				$device = strtolower(implode(' ', explode(' ',$request->device(), -1)));
			} else {
				$device = strtolower($request->device());
			}
			
			$devices = json_decode($url->devices, true);
			if(isset($devices[$device]) && $device) {
				$url->url = $devices[$device];
			}
		}


		if(!empty($url->options)){
			$browser_language = substr($request->server('http_accept_language'), 0, 2);
			if(strpos($browser_language, ' ') !== false){
				$language = strtolower(implode(' ', explode(' ',$browser_language, -1)));
			} else {
				$language = strtolower($browser_language);
			}
			
			$options = json_decode($url->options, true);
			if(isset($options['languages'][$language]) && $language) {
				$url->url = $options['languages'][$language];
			}
		}

		if(DB::reports()->whereRaw('bannedlink LIKE ?', ['%'.$url->url.'%'])->first()){
			return Gate::disabled();		    
		}

		// Replace encoded ampersand 
		$url->url = str_replace("&amp;", "&", $url->url);

		// Append parameters
		if(!empty($url->parameters) && $params = json_decode($url->parameters, false)){
			if(strpos($url->url, "?")){
				$url->url = $url->url."&".http_build_query($params);
			}else{
				$url->url = $url->url."?".http_build_query($params);
			}
		}

		// Forward queries if any
		if($request->query()){
			if(strpos($url->url, "?")){
				$url->url = $url->url."&".http_build_query($request->query());
			}else{
				$url->url = $url->url."?".http_build_query($request->query());
			}
		}
		
		if($url->qrid){

			$qr = DB::qrs()->first($url->qrid);

			$data = json_decode($qr->data);

			if($data->type == 'vcard'){
				return \Core\File::contentDownload('vcard.vcf', function() use ($data){
					echo \Helpers\QR::typeVcard($data->data);
				});
			}

			return Gate::direct($url, null);
		}

		if(!empty($url->meta_title)) View::set("title",$url->meta_title);
		if(!empty($url->meta_description)) View::set("description",$url->meta_description);

		View::set("url", Helpers\App::shortRoute($url->domain, $url->alias.$url->custom));
		if($url->meta_image){
			View::set("image", uploads($url->meta_image, 'images'));
		} else {
			View::set("image", Helpers\App::shortRoute($url->domain, $url->alias.$url->custom).'/i');
		}
		
		// Check if overlay
		if(preg_match("~overlay-(.*)~", $url->type) && $overlay = DB::overlay()->where("id",  str_replace("overlay-", "", $url->type))->where("userid", $user->id)->first()){
			return Gate::overlay($url, $overlay);	
		}	

		// Custom Splash Page
		if(is_numeric($url->type) && $splash = DB::splash()->where('id', $url->type)->where('userid', $url->userid)->first()){
			return Gate::custom($url, $splash, $user);
		}

		if($hasMedia && $media = $this->isMedia($url->url)){
			return Gate::media($url, $media, $user);
		}		

		// Check redirect method
		if(config("frame") == "3" || $isPro){
			if(empty($url->type)){
				
				return Gate::direct($url, $user);

			}elseif(in_array($url->type, array("direct","frame","splash"))){

				$fn = $url->type;
				
				return Gate::$fn($url, $user);
			}			
		}
		

		// Switch to a method
		$methods = array("0" => "direct", "1" => "frame", "2" => "splash", "3" =>  "splash");		
		$fn = $methods[config("frame")];
		return Gate::$fn($url, $user);
    }    	
    /**
     * Capture Screenshot
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function image(Request $request, string $alias){
		if(!$url = $this->getURL($request, $alias)){
			stop(404);
		}

		
		header("Cache-Control: max-age=31556926");
		header("Etag: ".md5($url->url));

		if($url->meta_image){
			header("Location: ".uploads('images/'.$url->meta_image));
			exit;
		}

		$lurl = urlencode($url->url);

		$list = [
			// "https://s.wordpress.com/mshots/v1/$lurl?w=800",
			// "https://api.pagepeeker.com/v2/thumbs.php?size=l&url=$lurl",
			// "https://api.miniature.io/?width=800&height=600&screen=1024&url=$lurl",
			"https://image.thum.io/get/width/600/crop/900/".urldecode($lurl)
		];

		$api_url = $list[array_rand($list, 1)];

		header("Location: $api_url");	
		exit;
    }

    /**
     * Generate Favicon
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function icon(int $id){

      	if(!$url = DB::url()->where('id', $id)->first()){
			header("Location: ".assets('images/unknown.svg'));	
			exit;	
		}   
		
		if(!$url->url || empty($url->url)){
			header("Location: ".assets('images/unknown.svg'));	
			exit;	
		}   

		if(!in_array(Helper::parseUrl($url->url, 'scheme'), ["http", "https"])){         
			header("Location: ".assets('images/unknown.svg'));	
			exit;	
		}


		header("Cache-Control: max-age=31556926");
		header("Etag: ".md5($url->url));

      	$host = Helper::parseUrl($url->url, 'host');
      
		header("Location: ".str_replace('{{url}}', trim($host), self::ICOPATH));	
		exit;			      
    }

    /**
     * Generate QR Code
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param string $alias
     * @return void
     */
    public function qr(Request $request, string $alias, int $size = 300, $action = "view"){		

		if(!$url = $this->getURL($request, $alias)){
			stop(404);
		}
		
		$qrsize = 300;

		if(is_numeric($size) && $size > 50 && $size <= 1000) $qrsize = $size;

		$url = \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom);		
		
		return \Helpers\QR::factory($url, $qrsize)->format('png')->create();
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
	public function qrDownload(Request $request, string $alias, string $format, int $size = 300){
		
		if(!$url = $this->getURL($request, $alias)){
			stop(404);
		}

		$qrsize = 300;

		if(is_numeric($size) && $size > 50 && $size <= 1000) $qrsize = $size;

		$url = \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom);		
		
		$qr = \Helpers\QR::factory($url, $qrsize)->format($format);

		return \Core\File::contentDownload('QR-code-'.$alias.'.'.$qr->extension(), function() use ($qr) {
			return $qr->string();
		});
	}
	/**
	 * Delete Link
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param integer $id
	 * @param string $nonce
	 * @return void
	 */
	public function delete(int $id, string $nonce){

		if(Auth::user()->teamPermission('links.delete') == false){
            return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
        }

		if(!Helper::validateNonce($nonce, 'link.delete')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

		if(!$this->deleteLink($id, Auth::user())){
			return Helper::redirect()->back()->with('danger', e('Link not found. Please try again.'));
		}

		return Helper::redirect()->back()->with('success', e('Link has been deleted.'));
	}
	/**
	 * Delete Many
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function deleteMany(Request $request){

		if(Auth::user()->teamPermission('links.delete') == false){
            return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
        }

        $ids = json_decode($request->selected);

        if(!$ids || empty($ids)) return Helper::redirect()->back()->with('danger', e('No link was selected. Please try again.')); 

        foreach($ids as $id){
            $this->deleteLink($id, Auth::user());
        }
        
        return Helper::redirect()->back()->with('success', e('Selected Links have been deleted.'));
	}
	/**
	 * Archive Selected
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @return void
	 */
	public function archiveSelected(Request $request){

		if(Auth::user()->teamPermission('links.edit') == false){
			return Response::factory(['error' => true, 'message' => e('You do not have this permission. Please contact your team administrator.')]);
        }

		if($request->link){
			DB::url()->where('id', $request->link)->where('userid', Auth::user()->rID())->update(['archived' => 1]);
		} else {
			$ids = json_decode(html_entity_decode($request->selected));
			if(!$ids){
				return Response::factory(['error' => true, 'message' => e('You need to select at least 1 link.')])->json();
			}
			foreach($ids as $id){
				DB::url()->where('id', $id)->where('userid', Auth::user()->rID())->update(['archived' => 1]);
			}			
		}
		

		return Response::factory(['error' => false, 'message' => e('Selected links have been archived.')])->json();
	}
	/**
	 * UnArchive Selected
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function unarchiveSelected(Request $request){
		
		if(Auth::user()->teamPermission('links.edit') == false){
			return Response::factory(['error' => true, 'message' => e('You do not have this permission. Please contact your team administrator.')]);
        }

		if($request->link){
			DB::url()->where('id', $request->link)->where('userid', Auth::user()->rID())->update(['archived' => 0]);
		} else {
			$ids = json_decode(html_entity_decode($request->selected));
			if(!$ids){
				return Response::factory(['error' => true, 'message' => e('You need to select at least 1 link.')])->json();
			}
			foreach($ids as $id){
				DB::url()->where('id', $id)->where('userid', Auth::user()->rID())->update(['archived' => 0]);
			}
		}

		return Response::factory(['error' => false, 'message' => e('Selected links have been removed from archive.')])->json();
	}
	/**
	 * Public Selected
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @return void
	 */
	public function publicSelected(Request $request){

		if(Auth::user()->teamPermission('links.edit') == false){
			return Response::factory(['error' => true, 'message' => e('You do not have this permission. Please contact your team administrator.')]);
        }

		if($request->link){
			DB::url()->where('id', $request->link)->where('userid', Auth::user()->rID())->update(['public' => 1]);
		} else {
			$ids = json_decode(html_entity_decode($request->selected));
			if(!$ids){
				return Response::factory(['error' => true, 'message' => e('You need to select at least 1 link.')])->json();
			}
			foreach($ids as $id){
				DB::url()->where('id', $id)->where('userid', Auth::user()->rID())->update(['public' => 1]);
			}			
		}
		

		return Response::factory(['error' => false, 'message' => e('Selected links have been set to public.')])->json();
	}
	/**
	 * Private Selected
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function privateSelected(Request $request){
		
		if(Auth::user()->teamPermission('links.edit') == false){
			return Response::factory(['error' => true, 'message' => e('You do not have this permission. Please contact your team administrator.')]);
        }

		if($request->link){
			DB::url()->where('id', $request->link)->where('userid', Auth::user()->rID())->update(['public' => 0]);
		} else {
			$ids = json_decode(html_entity_decode($request->selected));
			if(!$ids){
				return Response::factory(['error' => true, 'message' => e('You need to select at least 1 link.')])->json();
			}
			foreach($ids as $id){
				DB::url()->where('id', $id)->where('userid', Auth::user()->rID())->update(['public' => 0]);
			}
		}

		return Response::factory(['error' => false, 'message' => e('Selected links have been set to private.')])->json();
	}
	 /**
     * Edit Link
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param integer $id
     * @return void
     */
    public function edit(int $id){

		if(Auth::user()->teamPermission('links.edit') == false){
            return Helper::redirect()->to(route('links'))->with('danger', e('You do not have this permission. Please contact your team administrator.'));
        }
        
        if(!$url = DB::url()->where('id', $id)->where("userid",  \Core\Auth::user()->rID())->first()) return Helper::redirect()->back()->with('danger', e('Link does not exist.'));
        
        View::set('title', e('Update Link'));

		$locations = [];
		if($url->location && $url->location != "null"){
			foreach(json_decode($url->location, true) as $country => $location){
				if(is_array($location)){
					foreach($location as $city => $data){
						$locations[$country.'-'.$city] = $data;
					}
				} else {
					$locations[$country] = $location;
				}
			}
		}
		
		$url->devices = $url->devices && $url->devices != "null" ? json_decode($url->devices, true) : [];
		$url->languages = [];
		if($url->options && $url->options != "null"){
			$options = json_decode($url->options, true);
			if(isset($options['languages'])){
				$url->languages = $options['languages'];
			}
		} 
		$url->parameters = $url->parameters && $url->parameters != "null" ? json_decode($url->parameters, true) : [];
		$url->pixels = $url->pixels && $url->pixels != "null" ? explode(',', $url->pixels) : [];
		
		$channels = []; 
		
		foreach(DB::tochannels()->where('type', 'links')->where('itemid', $url->id)->findMany() as $channel){
			$channels[] = $channel->channelid;
		}

		View::push(assets('frontend/libs/clipboard/dist/clipboard.min.js'), 'js')->toFooter();
		
        \Helpers\CDN::load('datetimepicker');

        return View::with('user.edit', compact('url', 'locations', 'channels'))->extend('layouts.dashboard');
    }
    /**
     * Update Link
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param \Core\Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id){

        \Gem::addMiddleware('DemoProtect');

		if(Auth::user()->teamPermission('links.edit') == false){
            return back()->with('danger', e('You do not have this permission. Please contact your team administrator.'));
        }

        if(!$url = DB::url()->where('id', $id)->where("userid", \Core\Auth::user()->rID())->first()) return Helper::redirect()->back()->with('danger', e('URL does not exist.'));
        
		if($image = $request->file('metaimage')){
			$request->metaimage = $image;
		}
		
        try{
            
			$this->updateLink($request, $url, \Core\Auth::user());

			$channels = []; 
		
			if(is_null($request->channels)) $request->channels = [];

			foreach(DB::tochannels()->where('type', 'links')->where('itemid', $url->id)->findMany() as $channel){
				if(!in_array($channel->id, $request->channels)){
					$channel->delete();
				}
			}

			foreach($request->channels as $channel){
				if(!DB::tochannels()->where('type', 'links')->where('itemid', $url->id)->where('channelid', $channel)->first()){
					$tochannel = DB::tochannels()->create();
	
					$tochannel->userid = user()->rID();
					$tochannel->channelid = $channel;
					$tochannel->itemid = $url->id;
					$tochannel->type = 'links';
					$tochannel->save();			
				}
			}

        } catch(\Exception $e){
            return Helper::redirect()->back()->with('danger', $e->getMessage());
        }

        return Helper::redirect()->back()->with('success', e('Link has been updated successfully.'));
    }
	/**
	 * Add to campaign
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function addtocampaign(Request $request){
		
		if(!is_numeric($request->campaigns)) return Response::factory(['error' => true, 'message' => e('Invalid campaign. Please choose a valid campaign.'), 'token' => csrf_token()])->json();
	
		$campaignid = 0;

		if($campaign = DB::bundle()->where('id', $request->campaigns)->where('userid', Auth::user()->rID())->first()){
			$campaignid = $campaign->id;
		}

		$ids = json_decode(html_entity_decode($request->bundleids));

		if(!$ids){
			return Response::factory(['error' => true, 'message' => e('You need to select at least 1 link.'), 'token' => csrf_token()])->json();
		}

		foreach($ids as $id){
			DB::url()->where('id', $id)->where('userid', Auth::user()->rID())->update(['bundle' => $campaignid]);
		}

		return Response::factory(['error' => false, 'message' => $campaignid ? e('Selected links have been added to the {c} campaign.', null, ['c' => $campaign->name]) : e('Selected links have been removed from campaigns.'), 'token' => csrf_token(), 'html' => '<script>refreshlinks('.json_encode($ids).')</script>'])->json();

	}	
	/**
	 * Bookmark
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */	
	public function bookmark(Request $request){

		if(_STATE == 'DEMO') return Response::factory(["error" => 1, "msg" => "This has been disabled in demo."])->json();
		
		if(!$user = \Models\User::whereRaw('MD5(api) = ?', clean($request->token))->first()){
            return Response::factory(clean($request->callback).'('.json_encode(['error' => 1, 'msg' => 'Invalid request. Please update bookmarklet.']).')')->send();
        }

		try{
			$link = $this->createLink($request, $user);
		} catch(\Exception $e){
			return Response::factory(clean($request->callback).'('.json_encode(['error' => 1, 'msg' => $e->getMessage()]).')')->send();
		}

		return Response::factory(clean($request->callback).'('.json_encode(['error' => 0, 'short' => $link['shorturl']]).')')->send();
	}
	/**
	 * Script Js
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	public function scriptjs(Request $request){
		
		if(_STATE == 'DEMO') return Response::factory(["error" => 1, "msg" => "This has been disabled in demo."])->json();

		header("Content-type: text/javascript");
		ob_start(function($content) { return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content); });

		$js = file_get_contents(STORAGE."/app/jShortener.js");
		$js = str_replace("__URL__", config('url'), $js);

		echo $js;
		ob_end_flush(); 
	}

	/**
     * Full Page Script
     *
     * @author GemPixel <https://gempixel.com> 
     * @version 6.0
     * @param \Core\Request $request
     * @return void
     */
    public function fullpage(Request $request){

		if(!$request->key || !$request->url) return Response::factory(['error' => 1, 'message' => 'Invalid Request. Please try again.'])->json();

        if(!$user = \Models\User::whereRaw('MD5(api) = ?', clean($request->key))->first()){
            return Response::factory(['error' => 1, 'message' => 'Invalid Request. Please try again.'])->json();
        }

        try{
			$link = $this->createLink($request, $user);
		} catch(\Exception $e){
			return Response::factory(clean($request->callback).'('.json_encode(['error' => 1, 'msg' => $e->getMessage()]).')')->send();
		}
        return Response::factory(clean($request->callback).'('.json_encode(['error' => 0, 'short' => $link['shorturl']]).')')->send();
    }
	/**
	 * Quick Shortening
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.3.3
	 * @param \Core\Request $request
	 * @return void
	 */
	public function quick(Request $request){

		if(_STATE == 'DEMO') return  Helper::redirect()->to(route('home'))->with('danger', e("This has been disabled in demo."));

		if(!Auth::logged()) return  Helper::redirect()->to(route('login'))->with('danger', e("You need to be logged in to use this feature."));

		$user = Auth::logged() ? Auth::user() : null;
		
		$request->url = $request->u;

		try{
			$link = $this->createLink($request, $user);
		} catch(\Exception $e){
			return Helper::redirect()->to(route('dashboard'))->with('danger', $e->getMessage());
		}

		return Helper::redirect()->to($request->u);
	}
	/**
	 * Not Found
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param \Core\Request $request
	 * @return void
	 */
	protected function notFound(Request $request){

		$currenturi = trim(str_replace($request->path(), '', $request->uri(false)), '/');

        if(config('url') != $currenturi){
            
            $host = \idn_to_utf8(Helper::parseUrl($request->host(), 'host'));

            if($domain = \Core\DB::domains()->whereRaw("domain = ? OR domain = ?", ["http://".$host,"https://".$host])->first()){
                if($domain->redirect404){
                    header("Location: {$domain->redirect404}");
                    exit;
                }
            }
		}

		return stop(404);
	}
	/**
	 * Redirect Rotator
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @return void
	 */
	public function campaign($alias){
		if(!$bundle = DB::bundle()->where('slug', clean($alias))->first()){
			stop(404);
		}

		if($bundle->access == "private") stop(404);

		if(!$url = DB::url()->where('bundle', $bundle->id)->orderByExpr('RAND()')->first()){
			stop(404);
		}

		$bundle->view++;
		$bundle->save();

		return Helper::redirect()->to(\Helpers\App::shortRoute($url->domain, $url->alias.$url->custom));
	}
	/**
	 * Campaign List
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.0
	 * @param string $username
	 * @param string $alias
	 * @return void
	 */
	public function campaignList(string $username, string $alias){

		if(!$user = \Models\User::where("username", clean($username))->first()){
            stop(404);
        }
		
		if($user->banned) {
			return Gate::disabled();
		}

        if(!$user->public || !$user->defaultbio) stop(404);

        if(!$profile = DB::profiles()->where('id', $user->defaultbio)->first()){
            stop(404);
		}
		$id = explode('-', clean($alias));

		if(!$bundle = DB::bundle()->where('userid', $user->id)->where('id', end($id))->first()){
			stop(404);
		}

		if($bundle->access == "private") stop(404);

		$bundle->view++;
		$bundle->save();
		
		return \Helpers\Gate::bundle($profile, $bundle, $user);
	}
	/**
	 * User Profile
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.4.1
	 * @param \Core\Request $request
	 * @param string $username
	 * @return void
	 */
	public function profile(Request $request, string $username){
        if(!$user = \Models\User::where("username", clean($username))->first()){
            stop(404);
        }

		if($user->banned) {
			return Gate::disabled();
		}

        if(!$user->public || !$user->defaultbio) {
			
			if(Auth::logged() && Auth::user()->rID() == $user->id) return Helper::redirect()->to(route('settings'))->with('warning', e('You have to make your profile public or set a default bio for this page to be accessible.'));

			stop(404);
		}
		

        if(!$profile = DB::profiles()->where('id', $user->defaultbio)->first()){
            stop(404);
		}

        if(!$url = DB::url()->first($profile->urlid)){
			stop(404);
		}

        $this->updateStats($request, $url, null);
        return \Helpers\Gate::profile($profile, $user);
    }
	
	/**
	 * Reset Stats
	 *
	 * @author GemPixel <https://gempixel.com> 
	 * @version 6.1.6
	 * @param integer $id
	 * @param string $nonce
	 * @return void
	 */
	public function reset(int $id, string $nonce){
		
		if(!Helper::validateNonce($nonce, 'link.reset')){
            return Helper::redirect()->back()->with('danger', e('An unexpected error occurred. Please try again.'));
        }

		$user = Auth::user();
		
		if(!$url = DB::url()->where('id', $id)->where("userid",  $user->rID())->first()) return Helper::redirect()->back()->with('danger', e('Link does not exist.'));

		DB::stats()->where('urlid', $url->id)->deleteMany();

		$url->click = 0;
		$url->uniqueclick = 0;

		$url->save();

		return Helper::redirect()->back()->with('success', e('Statistics have been successfully reset.'));

	}
}