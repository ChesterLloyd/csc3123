<?php
/**
 * A class that contains code to handle any requests for  /account/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /account/
 */
    class Account extends \Framework\Siteaction
    {
/**
 * Handle account operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/account.twig';
        }
    }
?>