<?php
/**
 * A class that contains code to handle any requests for  /browse/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /browse/
 */
    class Browse extends \Framework\Siteaction
    {
/**
 * Handle browse operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/browse.twig';
        }
    }
?>