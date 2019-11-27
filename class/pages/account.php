<?php
/**
 * A class that contains code to handle any requests for  /account/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /account/
 */
    class Account extends \Framework\Siteaction
    {
/**
 * Handle account operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            // Put user bean in context
            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get last uploaded note date
            $recent = R::getCell('SELECT max(upload) FROM note WHERE user_id = ?', [$uid]);
            $context->local()->addval('recent', $recent);

            // Get user's recently uploaded files
            $ruploads = R::findAll('upload', 'WHERE user_id = ?
                GROUP BY note_id ORDER BY added DESC LIMIT 3', [$uid]);
            $context->local()->addval('ruploads', $ruploads);

            // Get user's favourite notes
            $favourites = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE R.user_id = ? AND
                R.favourite = ? GROUP BY N.id ORDER BY N.upload DESC', [$uid, 1]);
            $context->local()->addval('favourites', $favourites);

            // Get user's own notes
            $notes = R::findAll('upload', 'WHERE user_id = ?
                GROUP BY note_id ORDER BY added DESC', [$uid]);
            $context->local()->addval('notes', $notes);

            if ($context->hasteacher())
            { # If user is a teacher, show everyone's notes
                $all = R::findAll('upload', 'GROUP BY note_id ORDER BY added DESC');
                $context->local()->addval('all', $all);
                $context->local()->addval('teacher', true);
            }

            // Get number of favourite notes
            $nfavourites = R::count('review', 'user_id = ? AND favourite = ?', [$uid, 1]);
            $context->local()->addval('nfavourites', $nfavourites);

            return '@content/account.twig';
        }
    }
?>
