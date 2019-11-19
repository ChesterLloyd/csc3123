<?php
/**
 * A class that contains code to handle any requests for  /manage/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /manage/
 */
    class Manage extends \Framework\Siteaction
    {
/**
 * Handle manage operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/manage.twig';
        }
    }
?>