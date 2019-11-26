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
                GROUP BY note_id ORDER BY added DESC LIMIT 4', [$uid]);
            $context->local()->addval('ruploads', $ruploads);








            //
            //
            // // Get 6 top notes
            // $notes = array();
            // $files = R::findAll('upload', 'JOIN note N on N.id = upload.note_id
            //     JOIN review R ON R.note_id = N.id WHERE N.module = ?
            //     GROUP BY N.id ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
            //     N.upload DESC LIMIT 6', [$module]);
            // foreach ($files as $file)
            // {
            //     if (!$file->canaccess($context->user()))
            //     { # Current user cannot access the file, remove from array
            //         unset($files[$file->id]);
            //     }
            //     else
            //     { # User can access file, save note to array
            //         array_push($notes, $file->note);
            //     }
            // }
            // $context->local()->addval('tnotes', $notes);
            // $context->local()->addval('tfiles', $files);
            //
            // // Get users favourite notes
            // $sql = 'SELECT F.* FROM upload F
            //     JOIN note N on N.id = F.note_id
            //     JOIN user U ON R.note_id = N.id
            //     WHERE R.user_id = '.$uid.' AND R.favourite = 1
            //     GROUP BY N.id';
            // $rows = R::getAll($sql);
            // $files = R::convertToBeans('upload', $rows);
            // $notes = array();
            // foreach ($files as $file)
            // {
            //     if (!$file->canaccess($context->user()))
            //     { # Current user cannot access the file, remove from array
            //         unset($files[$file->id]);
            //     }
            //     else
            //     { # User can access file, save note to array
            //         array_push($notes, $file->note);
            //     }
            // }
            // $context->local()->addval('fnotes', $notes);
            // $context->local()->addval('ffiles', $files);
            //
            // // Get 6 top notes
            // $sql = 'SELECT F.* FROM upload F
            //     JOIN note N on N.id = F.note_id
            //     JOIN review R ON R.note_id = N.id
            //     GROUP BY N.id
            //     ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
            //     N.upload DESC LIMIT 6';
            // $rows = R::getAll($sql);
            // $files = R::convertToBeans('upload', $rows);
            // $notes = array();
            // foreach ($files as $file)
            // {
            //     if (!$file->canaccess($context->user()))
            //     { # Current user cannot access the file, remove from array
            //         unset($files[$file->id]);
            //     }
            //     else
            //     { # User can access file, save note to array
            //         array_push($notes, $file->note);
            //     }
            // }
            // $context->local()->addval('tnotes', $notes);
            // $context->local()->addval('tfiles', $files);
            //
            // // Get every file and note user can access
            // $notes = array();
            // $files = R::findAll('upload', 'GROUP BY note_id ORDER BY added DESC');
            // foreach ($files as $file)
            // {
            //     if (!$file->canaccess($context->user()))
            //     { # Current user cannot access the file, remove from array
            //         unset($files[$file->id]);
            //     }
            //     else
            //     { # User can access file, save note to array
            //         array_push($notes, $file->note);
            //     }
            // }
            // $context->local()->addval('anotes', $notes);
            // $context->local()->addval('afiles', $files);
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //
            // // Get file icons (first file type in set is the icon)
            // $sql = 'SELECT F.* FROM note N
            //     JOIN user U ON N.user_id = U.id
            //     JOIN file F ON N.id = F.note_id
            //     WHERE U.id = '.$user->id.'
            //     GROUP BY N.id
            //     ORDER BY N.upload DESC, F.id ASC
            //     LIMIT 4';
            // $rows = R::getAll($sql);
            // $rfiles = R::convertToBeans('file', $rows);
            // $context->local()->addval('rfiles', $rfiles);
            //
            // // Get user's favourite notes
            // // Get notes as beans
            // $sql = 'SELECT N.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     JOIN user U ON R.user_id = U.id
            //     WHERE U.id = '.$user->id.' AND R.favourite = 1
            //     AND N.privacy = 1 ORDER BY N.upload DESC';
            // $rows = R::getAll($sql);
            // $fnotes = R::convertToBeans('note', $rows);
            // $context->local()->addval('fnotes', $fnotes);
            //
            // // Get file icons (first file type in set is the icon)
            // $sql = 'SELECT F.* FROM note N
            //     JOIN review R ON N.id = R.note_id
            //     JOIN user U ON R.user_id = U.id
            //     JOIN file F ON N.id = F.note_id
            //     WHERE U.id = '.$user->id.' AND R.favourite = 1
            //     AND N.privacy = 1 GROUP BY N.id
            //     ORDER BY N.upload DESC, F.id ASC';
            // $rows = R::getAll($sql);
            // $ffiles = R::convertToBeans('file', $rows);
            // $context->local()->addval('ffiles', $ffiles);
            //
            // // Get user's notes for table
            // // Get notes as beans
            // $sql = 'SELECT N.* FROM note N
            //     WHERE N.user_id = '.$user->id.'
            //     ORDER BY N.upload DESC';
            // $rows = R::getAll($sql);
            // $mnotes = R::convertToBeans('note', $rows);
            // $context->local()->addval('mnotes', $mnotes);

            // Get number of favourite notes
            $favourites = R::count('review', 'user_id = ? AND favourite = ?', [$uid, 1]);
            $context->local()->addval('favourites', $favourites);

            return '@content/account.twig';
        }
    }
?>
