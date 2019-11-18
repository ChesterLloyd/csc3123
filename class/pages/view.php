<?php
/**
 * A class that contains code to handle any requests for  /view/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /view/
 */
    class View extends \Framework\Siteaction
    {
/**
 * Handle view operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/view.twig';
        }
    }
?>