<?php
/**
 * Class for handling downloading files
 *
 * @author Chester Lloyd <c.lloyd7@ncl.ac.uk>
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
* A class that contains code to implement a file downloader
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
        { # Downlaod this file, update download counter for note first
            $fpt = $context->rest();
            if (count($fpt) == 2 && $fpt[0] == 'file')
            { # this is access by upload ID
                $file = R::load('upload', $fpt[1]);
                if ($file->getID() != 0)
                {
                    $note = R::load('note', $file->note()->getID());
                    $note->downloads = ($note->downloads + 1);
                    R::store($note);
                }
            }

            // Make a new instance of Getfile and call it
            $getfile = new \Framework\Pages\Getfile;
            // URL to download files becomes: download/file/upload id
            $getfile->handle($context);
            return '@content/download.twig';
        }
    }
?>
