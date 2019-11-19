<?php
/**
 * A class that contains code to handle any requests for  /notes/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /notes/
 */
    class Notes extends \Framework\Siteaction
    {
/**
 * Handle notes operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $notes = R::find('note');
            $context->local()->addval('notes', $notes);


            $user = $context->user();
            $context->local()->addval('user', $user);




            // foreach (R::findAll('note', 'order by name') as $pr)
            // {
            //     // process each person
            // }


            return '@content/notes.twig';
        }
    }
?>
