<?php
 /**
  * Class for handling home pages
  *
  * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
  * @copyright 2012-2019 Newcastle University
  */
    namespace Pages;

    use \Support\Context as Context;
    use \R as R;
/**
 * A class that contains code to implement a home page
 */
    class Home extends \Framework\SiteAction
    {
/**
 * Handle various contact operations /
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Get 6 top notes
            $top = R::findAll('upload', 'JOIN note N ON N.id = upload.note_id
                JOIN review R ON R.note_id = N.id WHERE R.rating > 0
                GROUP BY N.id ORDER BY (SUM(R.rating) / COUNT(R.rating)) DESC,
                N.upload DESC LIMIT 6');
            $context->local()->addval('top', $context->canAccessFiles($top));

            return '@content/index.twig';
        }
    }
?>
