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
            // $notes = R::find('note', 'course=? AND privacy=?', [$course, 1]);
            // $context->local()->addval('anotes', $notes);


            $sql = 'SELECT N.* FROM note N
                WHERE N.course = "'.$course.'" AND N.privacy = 1
                ORDER BY N.upload ASC';
            $rows = R::getAll($sql);
            $anotes = R::convertToBeans('note', $rows);
            $context->local()->addval('anotes', $anotes);

            // Get file icons (first file type in set is the icon)
            $sql = 'SELECT F.* FROM note N
                JOIN file F ON N.id = F.note_id
                WHERE N.course = "'.$course.'" AND N.privacy = 1
                GROUP BY N.id
                ORDER BY N.upload ASC, F.id ASC';
            $rows = R::getAll($sql);
            $afiles = R::convertToBeans('file', $rows);
            $context->local()->addval('afiles', $afiles);


            // Get 4 top rated notes
            // Get notes as beans
            $sql = 'SELECT N.* FROM note N
                JOIN review R ON N.id = R.note_id
                WHERE N.course = "'.$course.'" AND N.privacy = 1
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
                WHERE N.course = "'.$course.'" AND N.privacy = 1
                GROUP BY N.id
                ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC, F.id ASC LIMIT 4';
            $rows = R::getAll($sql);
            $tfiles = R::convertToBeans('file', $rows);
            $context->local()->addval('tfiles', $tfiles);

            return '@content/course.twig';
        }
    }
?>
