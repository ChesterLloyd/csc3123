<?php
/**
 * A class that contains code to handle any requests for  /modify/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /modify/
 */
    class Modify extends \Framework\Siteaction
    {
/**
 * Handle modify operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/modify.twig';
        }
    }
?>