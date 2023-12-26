<?php
/* -----------------------------------------------------------------------------------------
   $Id: modified_api.php 12408 2019-11-12 12:36:06Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
  
  
  class modified_api {
  
    private static $_endpoint = 'https://api.modified-shop.org/';
    private static $_endpoint_backup = 'https://api.modified-shop.org/';
    private static $_method = NULL;

    /**
     * instance
     *
     * @var Singleton
     */
    protected static $_instance = null;


    /**
     * get instance
     *
     * @return   Singleton
     */
    public static function getInstance() {

      if (null === self::$_instance) {
        self::$_instance = new self;
      }

      return self::$_instance;
    }
 
    
    /**
     * clone
     */
    protected function __clone() {}


    /**
     * constructor
     */
    protected function __construct() {}

    
    /**
     * setEndpoint
     */
    public static function setEndpoint($endpoint) {
      self::$_endpoint = $endpoint;
    }

    /**
     * setMethod
     */
    public static function setMethod($method) {
      self::$_method = strtoupper($method);
    }

    /**
     * reset
     */
    public static function reset() {
      self::setEndpoint(self::$_endpoint_backup);
    }

    /**
     * clean
     */
    private static function clean($response) {
      if (is_array($response)) {
        foreach ($response as $key => $value) {
          $response[$key] = self::clean($value);
        }
      } else {
        $response = preg_replace('/<script(.*?)>(.*?)<\/script>/is', '', $response);
        $response = preg_replace('/<iframe(.*?)>(.*?)<\/iframe>/is', '', $response);
      }
      
      return $response;
    }


    /**
     * isJSON
     */
    private static function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }


    /**
     * request
     */
    public static function request($path, $data = '', $timeout = 5) {
      
      self::$_endpoint = rtrim(self::$_endpoint, '/').'/';
      $path = ltrim($path, '/');
      
      $ch = curl_init(self::$_endpoint.$path);
      
      curl_setopt($ch, CURLOPT_URL, self::$_endpoint.$path);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

      switch (self::$_method) {
        case 'POST':
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          break;
        case 'PUT':
        case 'PATCH':
        case 'DELETE':
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          break;
      }

      if (self::$_method != null) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::$_method);
      }

      $result = curl_exec($ch);
      $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      if ($httpStatus < 200 || $httpStatus >= 300) {
        trigger_error('Could not reach external host: '.$path.'. Exit with Status: '.$httpStatus, E_USER_WARNING);
      }
      
      $response = $result;
      if (self::isJSON($result) === true) {
        $response = json_decode($result, true);
      }
      
      return self::clean($response);
    }
    
  }
