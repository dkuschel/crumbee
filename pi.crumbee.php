<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name' => 'CrumbEE',
  'pi_version' => '1.0',
  'pi_author' => 'Iain Urquhart',
  'pi_author_url' => 'http://jamdigital.co.nz/',
  'pi_description' => 'Returns a breadcrumb based on the current uri',
  'pi_usage' => Crumbee::usage()
  );

/**
 * Crumbee Class
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Iain Urquhart
 * @copyright		Copyright (c) 2010, Iain Urquhart
 * @link			  http://jamdigital.co.nz
 * @Updates			2017-05-01: updated to work with PHP 7 by DK
 */

class Crumbee
{

var $return_data = "";

  //function Crumbee()
  //Comment out above name to remove PHP 7 error when calling function with same name of class
  //Fix is to call a self-construct via the old fuction name, below:
 
    public function Crumbee()
    {
        self::__construct();
    }
  public function __construct()
  {
    $this->EE =& get_instance();
    
    // Let see what's seperating the links
    $delimiter = $this->EE->TMPL->fetch_param('delimiter');
	
  	$crumblabel ="";
  	
  	// Grab the uri and chop it up
    $parts = explode("/", $this->EE->security->xss_clean(dirname($_SERVER['REQUEST_URI']))); 
	foreach ($parts as $key => $dir) 
	{
    	switch ($dir) {
    	
    	/* Override labels from URLs if needed, to be nice */
        	case "about": $label = "About Us"; break;
			case "docs" : $label = "My Documents"; break;

        	
        	/* if not, lets move along*/ 
        	default: $label = $dir; break;   

    }

    /* start fresh, then add each directory back to the URL */
    	$url = "";
    	for ($i = 1; $i <= $key; $i++) 
       	{ 
       		$url .= $parts[$i] . "/"; 
       	} 
       	// Build an exclude list
    	if ($dir != "entry" && $dir != "category" && $dir != "") 
    	{
    	$label = str_replace("-"," ",$label);
    	$crumblabel = str_replace("_"," ",$label); 
       	//$this->return_data .= " <a href='/$url'>". ucwords($crumblabel) ."</a> ". $delimiter;
		
		$crumblabel = ucwords($crumblabel);
		//Only capitalize words that are not in the below array
		$noCap = array(" And ", " Of ", " The ");
		foreach ($noCap as $value) {
            $crumblabel = str_replace($value, strtolower($value), $crumblabel);
        }
       	$this->return_data .= " <a href='/$url'>". $crumblabel ."</a> ". $delimiter;

		}
	}
    
  }

//Update to work with PHP 7 and avoid error notices:
  //function usage()
  public static function usage()  
  {
  ob_start(); 
  ?>
The CrumbEE plugin creates a breadcrumb trail from the uri, primarily for use with the Pages module and/you/have/long/uris/

Example usage - placed within the {exp:channel:entries} tag:

	{exp:crumbee delimiter='>'} {title}

The delimiter='' parameter allows you to separate breadcrumb links with whatever html element you want.

You can override the output by adding to the switch statement in the plugin, an example is included (line 45) where the uri /about/ is converted to "About Us".

You can also ignore segments by adding to the exclude list (line 59) of the plugin.


  <?php
  $buffer = ob_get_contents();
	
  ob_end_clean(); 

  return $buffer;
  }
  // END

}
/* End of file pi.crumbee.php */ 
/* Location: ./system/expressionengine/third_party/crumbee/pi.crumbee.php */
