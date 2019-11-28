<?php
/**
 * A class that handles any request to browse notes by course or module code
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /browse/
 */
    class Browse extends \Framework\Siteaction
    {
/**
 * Handle browse operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            // Get note ID from REST
            $rest = $context->rest();
            if ((sizeof($rest) < 2) || ($rest[0] == '') || ($rest[1] == ''))
            { # No page or parameter passed
                throw new \Framework\Exception\Forbidden('No access');
            }
            $page = filter_var($rest[0], FILTER_SANITIZE_STRING);
            $param = filter_var($rest[1], FILTER_SANITIZE_STRING);
            if ($page != 'course' && $page != 'module')
            { # Invalid page parameter
                throw new \Framework\Exception\Forbidden('No access');
            }

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            $context->local()->addval('page', $page);
            switch ($page) {
                case 'course':
                    $context->local()->addval('course', $param);

                    // Get 6 top notes
                    $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                        JOIN review R ON R.note_id = N.id WHERE N.course = ?
                        AND R.rating > 0 GROUP BY N.id
                        ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                        N.upload DESC LIMIT 6', [$param]);
                    $context->local()->addval('top', $context->canAccessFiles($top));

                    // Get every file and note user can access
                    $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                        WHERE N.course = ? GROUP BY N.id ORDER BY added DESC', [$param]);
                    $context->local()->addval('notes', $context->canAccessFiles($notes));
                    break;
                case 'module':
                    $note = R::findOne('note', 'module = ?', [$param]);
                    $context->local()->addval('course', $note->course);
                    $context->local()->addval('module', $param);

                    // Get 6 top notes
                    $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                    JOIN review R ON R.note_id = N.id WHERE N.module = ?
                    AND R.rating > 0 GROUP BY N.id
                    ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                    N.upload DESC LIMIT 6', [$param]);
                    $context->local()->addval('top', $context->canAccessFiles($top));

                    // Get every file and note user can access
                    $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                    WHERE N.module = ? GROUP BY N.id ORDER BY added DESC', [$param]);
                    $context->local()->addval('notes', $context->canAccessFiles($notes));
                    break;
            }
            return '@content/browse.twig';
        }
    }
?>
