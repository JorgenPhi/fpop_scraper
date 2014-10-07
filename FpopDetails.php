<?php
Class FpopDetails
{
    private $login_url = "https://www.freedompop.com/login.htm";
    private $home_url = "http://www.freedompop.com/home.htm";
    private $fpop_username = "";
    private $fpop_password = "";
    private $cookie_file = "";
    private $post_fields = "";
    
    // Variables to be set later
    public $days_left = "";
    public $data_usage = "";
    public $total_data = "";

    function __construct( /*string*/ $user, /*string*/ $pass )
    {
        if (!($user && $pass))
        {
            throw new Exception('You must pass a username and password for authentication on www.freedompop.com');
        }
        $this->fpop_username = $user;
        $this->fpop_password = $pass;
        $this->cookie_file = tempnam("./tmp", "FPOPCOOKIE");
        $this->post_fields = "signin-username=" .
            urlencode($this->fpop_username) .
            "&signin-password=" . urlencode($this->fpop_password);
        $this->getDetails();
    }

    public function getDetails()
    {
        $login_curl = curl_init();
        curl_setopt($login_curl, CURLOPT_URL, $this->login_url);
        curl_setopt($login_curl, CURLOPT_RETURNTRANSFER, true);
        //Uncomment if CA is out of date OR SSL Errors
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($login_curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($login_curl, CURLOPT_POSTFIELDS, $this->post_fields);
        
        $login_out = curl_exec($login_curl);
        if (curl_errno($login_curl))
        {
            throw new Exception('Unable to login at ' . $this->login_url);
        }
        curl_close($login_curl);
        
        $usage_curl = curl_init();
        curl_setopt($usage_curl, CURLOPT_URL, $this->home_url);
        curl_setopt($usage_curl, CURLOPT_RETURNTRANSFER, true);
        //Uncomment if CA is out of date OR SSL Errors
        //curl_setopt($usage_curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($usage_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($usage_curl, CURLOPT_COOKIEFILE, $this->cookie_file);
        
        $usage = curl_exec($usage_curl);
        if (curl_errno($usage_curl))
        {
            throw new Exception('Unable to retrieve page: ' . $this->home_url);
        }
        
        curl_close($usage_curl);
        
        if (preg_match('/<strong>(.*?)<\/strong> days left in your plan period./', $usage, $matches))
        {
            $this->days_left = $matches[1];
        }
        
        if (preg_match('/<h4>You\'ve used <strong>(.*?)<\/strong>/', $usage, $matches))
        {
            $this->data_usage = $matches[1];
        }
        
        if (preg_match('/<div class="productTitle" style="margin-bottom:5px;">(.*?) Total<\/div>/', $usage, $matches))
        {
            $this->total_data = $matches[1];
        }
    }
}

Class FpopPhoneDetails
{
    private $login_url = "https://www.freedompop.com/login.htm";
    private $home_url = "http://www.freedompop.com/home.htm";
    private $fpop_username = "";
    private $fpop_password = "";
    private $cookie_file = "";
    private $post_fields = "";
    
    // Variables to be set later
    public $days_left = "";
    public $data_usage = "";
    public $total_data = "";

    function __construct( /*string*/ $user, /*string*/ $pass )
    {
        if (!($user && $pass))
        {
            throw new Exception('You must pass a username and password for authentication on www.freedompop.com');
        }
        $this->fpop_username = $user;
        $this->fpop_password = $pass;
        $this->cookie_file = tempnam("./tmp", "FPOPCOOKIE");
        $this->post_fields = "signin-username=" .
            urlencode($this->fpop_username) .
            "&signin-password=" . urlencode($this->fpop_password);
        $this->getDetails();
    }

    public function getDetails()
    {
        $login_curl = curl_init();
        curl_setopt($login_curl, CURLOPT_URL, $this->login_url);
        curl_setopt($login_curl, CURLOPT_RETURNTRANSFER, true);
        //Uncomment if CA is out of date OR SSL Errors
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($login_curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($login_curl, CURLOPT_POSTFIELDS, $this->post_fields);
        
        $login_out = curl_exec($login_curl);
        if (curl_errno($login_curl))
        {
            throw new Exception('Unable to login at ' . $this->login_url);
        }
        curl_close($login_curl);
        
        $usage_curl = curl_init();
        curl_setopt($usage_curl, CURLOPT_URL, $this->home_url);
        curl_setopt($usage_curl, CURLOPT_RETURNTRANSFER, true);
        //Uncomment if CA is out of date OR SSL Errors
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($login_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($usage_curl, CURLOPT_COOKIEFILE, $this->cookie_file);
        
        $usage = curl_exec($usage_curl);
        if (curl_errno($usage_curl))
        {
            throw new Exception('Unable to retrieve page: ' . $this->home_url);
        }
        
        curl_close($usage_curl);
        
        if (preg_match('/<h4>Billing Cycle: <span>(.*?)<\/span><\/h4>/', $usage, $matches))
        {
            $this->days_left = $matches[1];
        }
        
        if (preg_match('/
                           <div class="remainingUnits">
                               <span id="remainingData">
                                   
                                       
                                       
                                           (.*?) GBs left
                                       
                                   
                               <\/span>/', $usage, $matches))
        {
            $this->data_usage = $matches[1] . " GBs";
        }

        if (preg_match('/
                           <div class="remainingUnits">
                               <span id="remainingData">
                                   
                                       
                                       
                                           (.*?) MBs left
                                       
                                   
                               <\/span>/', $usage, $matches))
        {
            $this->data_usage = $matches[1] . " MBs";
        }
        
        
        if (preg_match('/<li><h4>Data Usage - <span id="data">(.*?)<\/span><\/h4>/', $usage, $matches))
        {
            $this->total_data = $matches[1];
        }
    }
}
