<?php
/**
 * A class that contains code to handle any requests for  /add/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /add/
 */
    class Add extends \Framework\Siteaction
    {
/**
 * Handle add operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/add.twig';
        }
    }
?>