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
            // Get search parameter from REST
            $rest = $context->rest();
            if (sizeof($rest) != 1)
            { # No page or parameter passed
                throw new \Framework\Exception\Forbidden('No access');
            }
            $param = filter_var('%'.$rest[0].'%', FILTER_SANITIZE_STRING);

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get 6 top notes
            $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                WHERE N.name LIKE ? OR N.course LIKE ? OR N.module LIKE ?
                GROUP BY N.id ORDER BY added DESC', [$param, $param, $param]);
            $context->local()->addval('notes', $context->canAccessFiles($notes));

            return '@content/search.twig';
        }
    }
?>
