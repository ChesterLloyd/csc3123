<?php
/**
 * A class that contains code to handle any requests for  /download/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /download/
 */
    class Download extends \Framework\Siteaction
    {
/**
 * Handle download operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            // $file = \Framework\Pages\Getfile::handle($context);

            $file = \Framework\Pages\Getfile::getinstance();


            $web = $context->web();

            // $web->sendfile($this->file, $file->filename);


            return '@content/download.twig';
        }
    }
?>
