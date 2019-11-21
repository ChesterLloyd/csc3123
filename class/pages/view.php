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
            $noteid = filter_var($_GET['note'], FILTER_SANITIZE_STRING);

            // Put current note bean in context
            $note = R::load('note', $noteid);
            $context->local()->addval('note', $note);

            // Put user bean in context
            $user = $context->user();
            $userid = $user->id;
            $context->local()->addval('user', $user);

            // Put author's bean in context
            // $author = R::load('user', $note->user_id);
            $author = R::findOne('user', 'id=?', [$note->user_id]);
            $context->local()->addval('author', $author);

            // Put file beans for current note in context
            $sql = 'SELECT F.* FROM file F
                JOIN note N ON F.note_id = N.id
                WHERE N.id = '.$noteid;
            $rows = R::getAll($sql);
            $files = R::convertToBeans('file', $rows);
            $context->local()->addval('files', $files);

            // Get rating of the notes
            $sql = 'SELECT (SUM(R.rating) / COUNT(R.rating)) AS rating
                FROM note N
                JOIN review R ON N.id = R.note_id
                WHERE N.id = '.$noteid.' GROUP BY N.id';
            $rating = R::getCell($sql);
            $rating = round($rating);
            $context->local()->addval('rating', $rating);

            // Get number of favourites for the note
            $sql = 'SELECT COUNT(R.favourite) AS favourite
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE N.id = '.$noteid.' AND R.favourite = 1';
            $favourites = R::getCell($sql);
            $context->local()->addval('favourites', $favourites);

            // Is this note in the user's favourites
            $sql = 'SELECT R.favourite
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE R.user_id = '.$userid.' AND N.id = '.$noteid;
            $favourite = R::getCell($sql);
            $context->local()->addval('favourite', $favourite);

            // Has this user reviewed this note
            $sql = 'SELECT COUNT(R.id)
                FROM review R
                JOIN note N ON R.note_id = N.id
                WHERE R.user_id = '.$userid.' AND N.id = '.$noteid;
            $reviewed = R::getCell($sql);
            $context->local()->addval('reviewed', $reviewed);

            return '@content/view.twig';
        }
    }
?>
