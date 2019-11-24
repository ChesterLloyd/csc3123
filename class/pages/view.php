<?php
/**
 * A class that contains code to handle any requests for  /view/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /view/
 */
    class View extends \Framework\Siteaction
    {
/**
 * Handle view operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            // Get note ID from query string
            $nid = filter_var($_GET['note'], FILTER_SANITIZE_STRING);

            $files = R::find('upload', 'note_id = ?', [$nid]);
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # current user cannot access the file, should not access notes
                    throw new \Framework\Exception\Forbidden('No access');
                }
            }
            $context->local()->addval('files', $files);

            // Put current note bean in context
            $note = $context->load('note', $nid);
            $context->local()->addval('note', $note);

            // Put current user bean in context
            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Put author bean in context
            $author = R::findOne('user', 'id=?', [$note->user_id]);
            $context->local()->addval('author', $author);

            // Get rating of the notes
            $sql = 'SELECT (SUM(R.rating) / COUNT(R.rating)) AS rating
                FROM note N
                JOIN review R ON N.id = R.note_id
                WHERE N.id = '.$nid.' GROUP BY N.id';
            $rating = R::getCell($sql);
            $rating = round($rating);
            $context->local()->addval('rating', $rating);

            // Get number of favourites for the note
            $sql = 'SELECT COUNT(R.favourite) AS favourite
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE N.id = '.$nid.' AND R.favourite = 1';
            $favourites = R::getCell($sql);
            $context->local()->addval('favourites', $favourites);

            // Is this note in the user's favourites
            $sql = 'SELECT R.favourite
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE R.user_id = '.$uid.' AND N.id = '.$nid;
            $favourite = R::getCell($sql);
            $context->local()->addval('favourite', $favourite);

            // Has this user reviewed this note
            $sql = 'SELECT COUNT(R.id)
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE R.user_id = '.$uid.' AND N.id = '.$nid;
            $reviewed = R::getCell($sql);
            $context->local()->addval('reviewed', $reviewed);

            return '@content/view.twig';
        }
    }
?>
