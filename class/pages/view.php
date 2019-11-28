<?php
/**
 * A class that collects all the data from the database when viewing a note
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
 *
 * @throws \Framework\Exception\Forbidden
 */
        public function handle(Context $context)
        {
            // Get note ID from REST
            $rest = $context->rest();
            $nid = filter_var($rest[0], FILTER_SANITIZE_STRING);
            if ($nid == '')
            { # No note given
                throw new \Framework\Exception\Forbidden('No access');
            }

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
            $author = R::findOne('user', 'id = ?', [$note->user_id]);
            $context->local()->addval('author', $author);

            // Get rating of the notes
            $rating = R::getCell('SELECT (SUM(R.rating) / COUNT(R.rating)) AS rating
                FROM note N JOIN review R ON N.id = R.note_id
                WHERE N.id = ? GROUP BY N.id', [$nid]);
            $rating = round($rating);
            $context->local()->addval('rating', $rating);

            // Get number of favourites for the note
            $favourites = R::getCell('SELECT COUNT(R.favourite) AS favourite
                FROM review R JOIN note N ON R.note_id = N.id
                WHERE N.id = ? AND R.favourite = 1', [$nid]);
            $context->local()->addval('favourites', $favourites);

            // Is this note in the user's favourites
            $favourite = R::getCell('SELECT R.favourite
                FROM review R JOIN note N ON R.note_id = N.id
                WHERE R.user_id = ? AND N.id = ?', [$uid, $nid]);
            $context->local()->addval('favourite', $favourite);

            // Has this user reviewed this note
            $review = R::getCell('SELECT R.rating
                FROM review R JOIN note N ON R.note_id = N.id
                WHERE R.user_id = ? AND N.id = ?
                AND R.rating IS NOT NULL', [$uid, $nid]);
            if ($review === NULL)
            {
                $review = 0;
            }
            $context->local()->addval('review', (int)$review);

            return '@content/view.twig';
        }
    }
?>
