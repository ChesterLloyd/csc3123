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
            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get every file and note user can access
            $notes = array();
            $files = R::findAll('upload');
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # Current user cannot access the file, remove from array
                    unset($files[$file]);
                }
                else
                { # User can access file, save note to array
                    array_push($notes, $file->note);
                }
            }

            $context->local()->addval('tnotes', $notes);
            $context->local()->addval('tfiles', $files);




            // // Get user's favourite notes
            //
            //
            // // Get notes as beans
            // $sql = 'SELECT N.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     JOIN user U ON R.user_id = U.id
            //     WHERE U.id = '.$userid.' AND R.favourite = 1';
            // $rows = R::getAll($sql);
            // $fnotes = R::convertToBeans('note', $rows);
            // $context->local()->addval('fnotes', $fnotes);
            //
            // // Get file icons (first file type in set is the icon)
            // $sql = 'SELECT F.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     JOIN user U ON R.user_id = U.id
            //     JOIN file F ON N.id = F.note_id
            //     WHERE U.id = '.$userid.' AND R.favourite = 1
            //     GROUP BY N.id ORDER BY N.upload ASC, F.id ASC';
            // $rows = R::getAll($sql);
            // $ffiles = R::convertToBeans('file', $rows);
            // $context->local()->addval('ffiles', $ffiles);
            //
            //
            // // Get 4 top rated notes
            // // Get notes as beans
            // $sql = 'SELECT N.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     GROUP BY N.id
            //     ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
            //     N.upload DESC LIMIT 4';
            // $rows = R::getAll($sql);
            // $tnotes = R::convertToBeans('note', $rows);
            // $context->local()->addval('tnotes', $tnotes);
            //
            // // Get file icons (first file type in set is the icon)
            // $sql = 'SELECT F.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     JOIN file F ON N.id = F.note_id
            //     GROUP BY N.id
            //     ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
            //     N.upload DESC, F.id ASC LIMIT 4';
            // $rows = R::getAll($sql);
            // $tfiles = R::convertToBeans('file', $rows);
            // $context->local()->addval('tfiles', $tfiles);




            return '@content/notes.twig';
        }
    }
?>
