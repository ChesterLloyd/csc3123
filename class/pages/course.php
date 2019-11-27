<?php
/**
 * A class that handles any requests to browse notes by course name
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \R as R;
/**
 * Support /course/
 */
    class Course extends \Framework\Siteaction
    {
/**
 * Handle course operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $course = filter_var($_GET['title'], FILTER_SANITIZE_STRING);
            $context->local()->addval('course', $course);

            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get 6 top notes
            $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE N.course = ?
                AND R.rating > 0 GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6', [$course]);
            $context->local()->addval('top', $context->canAccessFiles($top));
            
            // Get every file and note user can access
            $notes = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                WHERE N.course = ? GROUP BY N.id ORDER BY added DESC', [$course]);
            $context->local()->addval('notes', $context->canAccessFiles($notes));

            return '@content/course.twig';
        }
    }
?>
