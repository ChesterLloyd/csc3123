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

            $note = R::findOne('note', 'module = ?', [$module]);
            $context->local()->addval('course', $note->course);

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get 6 top notes
            $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE N.module = ?
                AND R.rating > 0 GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6', [$module]);
            $context->local()->addval('top', $context->canAccessFiles($top));

            // Get every file and note user can access
            $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                WHERE N.module = ? GROUP BY N.id ORDER BY added DESC', [$module]);
            $context->local()->addval('notes', $context->canAccessFiles($notes));

            return '@content/module.twig';
        }
    }
?>
