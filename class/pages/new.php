<?php
/**
 * A class that contains code to handle any requests for  /new/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /new/
 */
    class New extends \Framework\Siteaction
    {
/**
 * Handle new operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/new.twig';
        }
    }
?>