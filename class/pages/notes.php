<?php
/**
 * A class that contains code to handle any requests for browsing all notes
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
            $fd = $context->formdata();
            if (($param = $fd->post('search', '')) !== '')
            { # there is a search
                header('Location: '.$context->local()->base().'/search/'.$param);
            }

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get users favourite notes
            $favourites = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE R.user_id = ?
                AND R.favourite = ? GROUP BY N.id ORDER BY N.upload DESC', [$uid, 1]);
            $context->local()->addval('favourites', $context->canAccessFiles($favourites));

            // Get 6 top notes
            $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE R.rating > 0 GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6');
            $context->local()->addval('top', $context->canAccessFiles($top));

            // Get every file and note user can access
            $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                GROUP BY N.id ORDER BY added DESC');
            $context->local()->addval('notes', $context->canAccessFiles($notes));

            return '@content/notes.twig';
        }
    }
?>
