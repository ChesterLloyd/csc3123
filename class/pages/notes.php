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

            // Get users favourite notes
            $sql = 'SELECT F.* FROM upload F
                JOIN note N on N.id = F.note_id
                JOIN review R ON R.note_id = N.id
                WHERE R.user_id = '.$uid.' AND R.favourite = 1';
            $rows = R::getAll($sql);
            $files = R::convertToBeans('upload', $rows);
            $notes = array();
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # Current user cannot access the file, remove from array
                    unset($files[$file->id]);
                }
                else
                { # User can access file, save note to array
                    array_push($notes, $file->note);
                }
            }
            $context->local()->addval('fnotes', $notes);
            $context->local()->addval('ffiles', $files);

            // Get 6 top notes
            $sql = 'SELECT F.* FROM upload F
                JOIN note N on N.id = F.note_id
                JOIN review R ON R.note_id = N.id
                GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6';
            $rows = R::getAll($sql);
            $files = R::convertToBeans('upload', $rows);
            $notes = array();
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # Current user cannot access the file, remove from array
                    unset($files[$file->id]);
                }
                else
                { # User can access file, save note to array
                    array_push($notes, $file->note);
                }
            }
            $context->local()->addval('tnotes', $notes);
            $context->local()->addval('tfiles', $files);

            // Get every file and note user can access
            $notes = array();
            $files = R::findAll('upload', 'GROUP BY note_id');
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # Current user cannot access the file, remove from array
                    unset($files[$file->id]);
                }
                else
                { # User can access file, save note to array
                    array_push($notes, $file->note);
                }
            }
            $context->local()->addval('anotes', $notes);
            $context->local()->addval('afiles', $files);

            return '@content/notes.twig';
        }
    }
?>
