<?php
/**
 * A class that contains code to handle any requests for  /course/
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
            $sql = 'SELECT F.* FROM upload F
                JOIN note N on N.id = F.note_id
                JOIN review R ON R.note_id = N.id
                WHERE N.course = "'.$course.'"
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
            $files = R::findAll('upload', 'JOIN note N on N.id = upload.note_id
                WHERE N.course = ? GROUP BY note_id ORDER BY added DESC', [$course]);
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

            return '@content/course.twig';
        }
    }
?>
