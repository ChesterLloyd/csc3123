<?php
/**
 * A class that contains code to handle any requests for  /notes/
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
            $notes = R::find('note');
            $context->local()->addval('notes', $notes);


            $user = $context->user();
            $context->local()->addval('user', $user);


            // Get user's favourite notes
            // $luser = R::load('user', $user->id);
            # get review where favourite=1 and user_id = user,
            # get notes from user_id in there

            $fnotes = R::exec('SELECT N.* FROM notes N
                JOIN review R ON N.id = R.note_id
                JOIN user U ON R.user_id = U.id
                WHERE U.id = ' . $user->id . ' AND R.favourite = 1');

            $context->local()->addval('fnotes', $fnotes);




            // foreach (R::findAll('note', 'order by name') as $pr)
            // {
            //     // process each person
            // }


            return '@content/notes.twig';
        }
    }
?>
