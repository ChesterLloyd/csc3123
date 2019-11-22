<?php
/**
 * A class that contains code to handle any requests for  /module/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /module/
 */
    class Module extends \Framework\Siteaction
    {
/**
 * Handle module operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/module.twig';
        }
    }
?>