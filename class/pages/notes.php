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


            // Get user's favourite notes
            $userid = $user->id;

            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                JOIN review R ON N.id = R.note_id
                JOIN user U ON R.user_id = U.id
                WHERE U.id = '.$userid.' AND R.favourite = 1';
            $rows = R::getAll($sql);
            $fnotes = R::convertToBeans('note', $rows);
            $context->local()->addval('fnotes', $fnotes);

            // Get file icons (first file type in set is the icon)
            $sql = 'SELECT F.* FROM note N
                JOIN review R ON N.id = R.note_id
                JOIN user U ON R.user_id = U.id
                JOIN file F ON N.id = F.note_id
                WHERE U.id = '.$userid.' AND R.favourite = 1
                GROUP BY N.id ORDER BY N.upload ASC, F.id ASC';
            $rows = R::getAll($sql);
            $ffiles = R::convertToBeans('file', $rows);
            $context->local()->addval('ffiles', $ffiles);


            // Get 4 top rated notes
            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                JOIN review R ON N.id = R.note_id
                GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 4';
            $rows = R::getAll($sql);
            $tnotes = R::convertToBeans('note', $rows);
            $context->local()->addval('tnotes', $tnotes);

            // Get file icons (first file type in set is the icon)
            $sql = 'SELECT F.* FROM note N
                JOIN review R ON N.id = R.note_id
                JOIN file F ON N.id = F.note_id
                GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC, F.id ASC LIMIT 4';
            $rows = R::getAll($sql);
            $tfiles = R::convertToBeans('file', $rows);
            $context->local()->addval('tfiles', $tfiles);




            return '@content/notes.twig';
        }
    }
?>
