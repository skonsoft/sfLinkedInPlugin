<?php

require_once dirname(__FILE__).'/../lib/BasesfLinkedInActions.class.php';

/**
 * sfLinkedIn actions.
 * 
 * @package    sfLinkedInPlugin
 * @subpackage sfLinkedIn
 * @author     mabroukskander@gmail.com
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class sfLinkedInActions extends BasesfLinkedInActions
{
	public $consumer_key ;
	public $consumer_secret;
	
	public function preExecute(){
		$this->consumer_key = sfConfig::get("app_sf_linkedIn_plugin_consumer_key");
		$this->consumer_secret = sfConfig::get("app_sf_linkedIn_plugin_consumer_secret");
		$back_url = sfConfig::get("app_sf_linkedIn_plugin_back_url");
		$this->linkedin = new sfLinkedIn($this->consumer_key, $this->consumer_secret, $this->generateUrl($back_url, NULL, TRUE));
		if (strtoupper(sfConfig::get("app_sf_linkedIn_plugin_debug") ) == "ON"){
			$this->linkedin->debug = true;
		}else {
			$this->linkedin->debug = FALSE;
		}
	}
	
	public function executeIndex(sfWebRequest $request){
  		
  		
  		$this->linkedin->getRequestToken();
  	
  		$this->getUser()->setAttribute("request_token", $this->linkedin->request_token->key);
  		$this->getUser()->setAttribute("request_token_secret", $this->linkedin->request_token->secret);
  		
  		return $this->redirect($this->linkedin->generateAuthorizeUrl(), 200);
	}
	
	public function executeAccessToken(sfWebRequest $request){
		$oauth_verifier = $request->getParameter("oauth_verifier");
	  	$oauth_token = $request->getParameter("oauth_token");
	  	
	  	$request_token = $this->getUser()->getAttribute("request_token");
	  	$request_token_secret = $this->getUser()->getAttribute("request_token_secret");
	  	
	  	$this->linkedin->request_token = new OAuthConsumer($request_token, $request_token_secret, 1);
	  	
	  	try {
	  		$this->linkedin->getAccessToken($oauth_verifier);
	  		print_r($this->linkedIn->access_token);
	  	}catch (Exception $e){
	  		$this->msg = "oups an error was occured.";
	  		$this->logMessage($e->getMessage(), "err");
	  	}
	  	
	  	//we shoud redirect user to home page if ok.
	  	$this->setTemplate("index");
	}
}
