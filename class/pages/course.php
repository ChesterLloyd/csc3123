<?php
/**
 * A class that contains code to handle any requests for  /course/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /course/
 */
    class Course extends \Framework\Siteaction
    {
/**
 * Handle course operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            return '@content/course.twig';
        }
    }
?>