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
            $context->local()->addval('user', $user);

            // Get last uploaded note date
            $recent = R::getCell('SELECT max(upload) FROM note WHERE user_id = ' . $user->id);
            $context->local()->addval('recent', $recent);

            // Get user's recently uploaded notes
            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                JOIN user U ON N.user_id = U.id
                WHERE U.id = '.$user->id.'
                ORDER BY N.upload DESC LIMIT 4';
            $rows = R::getAll($sql);
            $rnotes = R::convertToBeans('note', $rows);
            $context->local()->addval('rnotes', $rnotes);

            // Get file icons (first file type in set is the icon)
            $sql = 'SELECT F.* FROM note N
                JOIN user U ON N.user_id = U.id
                JOIN file F ON N.id = F.note_id
                WHERE U.id = '.$user->id.'
                GROUP BY N.id
                ORDER BY N.upload DESC, F.id ASC
                LIMIT 4';
            $rows = R::getAll($sql);
            $rfiles = R::convertToBeans('file', $rows);
            $context->local()->addval('rfiles', $rfiles);

            // Get user's favourite notes
            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                JOIN review R ON N.id = R.note_id
                JOIN user U ON R.user_id = U.id
                WHERE U.id = '.$user->id.' AND R.favourite = 1
                AND N.privacy = 1 ORDER BY N.upload DESC';
            $rows = R::getAll($sql);
            $fnotes = R::convertToBeans('note', $rows);
            $context->local()->addval('fnotes', $fnotes);

            // Get file icons (first file type in set is the icon)
            $sql = 'SELECT F.* FROM note N
                JOIN review R ON N.id = R.note_id
                JOIN user U ON R.user_id = U.id
                JOIN file F ON N.id = F.note_id
                WHERE U.id = '.$user->id.' AND R.favourite = 1
                AND N.privacy = 1 GROUP BY N.id
                ORDER BY N.upload DESC, F.id ASC';
            $rows = R::getAll($sql);
            $ffiles = R::convertToBeans('file', $rows);
            $context->local()->addval('ffiles', $ffiles);

            // Get user's notes for table
            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                WHERE N.user_id = '.$user->id.'
                ORDER BY N.upload DESC';
            $rows = R::getAll($sql);
            $mnotes = R::convertToBeans('note', $rows);
            $context->local()->addval('mnotes', $mnotes);

            // Get number of favourite notes
            $favourites = R::count('review', 'user_id=? AND favourite=?', [$user->id, 1]);
            $context->local()->addval('favourites', $favourites);

            return '@content/account.twig';
        }
    }
?>
