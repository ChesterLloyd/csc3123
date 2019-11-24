<?php
/**
 * A class that handles uploading new notes
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \Framework\Local as Local;
/**
 * A class that contains code to implement an upload page
 */
    class Add extends \Framework\Siteaction
    {
/**
 * Handle add operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            $fd = $context->formdata();
            if ($fd->hasfile('uploads'))
            {
                // Make a note with the form data
                $note = \R::dispense('note');
                $note->name = $fd->post('name', 'No name');
                $note->course = $fd->post('course', 'Unknown Course');
                $note->module = $fd->post('module', 'Unknown Module');
                $note->privacy = $fd->post('privacy', '0');
                $note->downloads = 0;
                $note->upload = $context->utcnow();
                $note->description = $fd->post('description', 'No description');
                $note->comment = $fd->post('comment', 'No comments.');

                if (Config::UPUBLIC && Config::UPRIVATE)
                { # need to check the flag could be either private or public
                    foreach ($fd->posta('public') as $ix => $public)
                    {
                        $upl = \R::dispense('upload');
                        $note->xownFile[] = $upl;
                        $save = $upl->savefile($context, $fd->filedata('uploads', $ix), $public, $context->user(), $ix);
                    }
                }
                else
                {
                    foreach(new \Framework\FAIterator('uploads') as $ix => $fa)
                    { # we only support private or public in this case so there is no flag
                        $upl = \R::dispense('upload');
                        $note->xownFile[] = $upl;
                        $save = $upl->savefile($context, $fa, Config::UPUBLIC, $context->user(), $ix);
                    }
                }
                if ($save)
                {
                    // The file(s) uploaded OK, store the note
                    $nid = \R::store($note);

                    // Then assign it to the author (logged in user)
                    $uid = $context->user()->id;
                    $user = \R::load('user', $uid);
                    $user->xownNote[] = $note;
                    \R::store($user);

                    // Display a success message with a link to see thier notes
                    $context->local()->message(Local::MESSAGE, 'Your notes have been uploaded. View them <a href=view?note=' . $nid . '>here</a>.');
                }
            }
            return '@content/add.twig';
        }
    }
?>
