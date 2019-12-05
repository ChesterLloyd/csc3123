<?php
/**
 * A class that contains code to handle any requests for  /search/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /search/
 */
    class Search extends \Framework\Siteaction
    {
/**
 * Handle search operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/search.twig';
        }
    }
?>