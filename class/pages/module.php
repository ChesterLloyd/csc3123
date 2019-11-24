<?php
/**
 * A class that handles any requests to browse notes by module code
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /module/
 */
    class Module extends \Framework\Siteaction
    {
/**
 * Handle module operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $module = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
            $context->local()->addval('module', $module);

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get 6 top notes
            $notes = array();
            $files = R::findAll('upload', 'JOIN note N on N.id = uploads.note_id
                JOIN review R ON R.note_id = N.id WHERE N.module = ?
                GROUP BY N.id ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6', [$module]);
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
            $files = R::findAll('upload', 'JOIN note N on N.id = upload.note_id
                WHERE N.module = ? GROUP BY note_id ORDER BY added DESC', [$module]);
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

            return '@content/module.twig';
        }
    }
?>
