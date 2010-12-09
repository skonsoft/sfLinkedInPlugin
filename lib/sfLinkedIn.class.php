<?php


class sfLinkedIn extends LinkedIn {
	
	function getAccessToken($oauth_verifier) {
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->request_token, "GET", $this->access_token_path);
	    $request->set_parameter("oauth_verifier", $oauth_verifier);
	    $request->sign_request($this->signature_method, $this->consumer, $this->request_token);
	    $headers = Array();
	    $url = $request->to_url();
	    $response = $this->httpRequest($url, $headers, "GET");
	    var_dump($response);
	    parse_str($response, $response_params);
	    if($this->debug) {
	    	if (empty($response_params['oauth_token'] ) )
	      		throw new Exception("Failed to get Access token. ".$response);
	    }
	    $this->access_token = new OAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
	    
	    //we authenticate user and saves his access tokens.
	    
	    
	    $token = new sfLinkedInToken();
	    
	}

}

?>