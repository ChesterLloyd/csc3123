<?php
/**
 * A class that contains code to handle any requests for  /modify/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /modify/
 */
    class Modify extends \Framework\Siteaction
    {
/**
 * Handle modify operations
 *
 * @param object	$context	The context object for the site
 *
 * @return string	A template name
 */
        public function handle(Context $context)
        {
            // $fd = $context->formdata();
            // if ($fd->hasfile('uploads'))
            // {
            //     // Make a note with the form data
            //     $note = \R::dispense('note');
            //     $note->name = $fd->post('name', 'No name');
            //     $note->course = $fd->post('course', 'Unknown Course');
            //     $note->module = $fd->post('module', 'Unknown Module');
            //     $note->privacy = $fd->post('privacy', '0');
            //     $note->downloads = 0;
            //     $note->upload = $context->utcnow();
            //     $note->description = $fd->post('description', 'No description');
            //     $note->comment = $fd->post('comment', 'No comments.');
            //
            //     if (Config::UPUBLIC && Config::UPRIVATE)
            //     { # need to check the flag could be either private or public
            //         foreach ($fd->posta('public') as $ix => $public)
            //         {
            //             $upl = \R::dispense('upload');
            //             $note->xownFile[] = $upl;
            //             $save = $upl->savefile($context, $fd->filedata('uploads', $ix), $public, $context->user(), $ix);
            //         }
            //     }
            //     else
            //     {
            //         foreach(new \Framework\FAIterator('uploads') as $ix => $fa)
            //         { # we only support private or public in this case so there is no flag
            //             $upl = \R::dispense('upload');
            //             $note->xownFile[] = $upl;
            //             $save = $upl->savefile($context, $fa, Config::UPUBLIC, $context->user(), $ix);
            //         }
            //     }
            //     if ($save)
            //     {
            //         // The file(s) uploaded OK, store the note
            //         $nid = \R::store($note);
            //
            //         // Then assign it to the author (logged in user)
            //         $uid = $context->user()->id;
            //         $user = \R::load('user', $uid);
            //         $user->xownNote[] = $note;
            //         \R::store($user);
            //
            //         // Display a success message with a link to see thier notes
            //         $context->local()->message(Local::MESSAGE, 'Your notes have been uploaded. View them <a href=view?note=' . $nid . '>here</a>.');
            //     }
            // }


            // Get existing note to page

            // Get note ID from query string
            $nid = filter_var($_GET['note'], FILTER_SANITIZE_STRING);

            $files = R::find('upload', 'note_id = ?', [$nid]);
            foreach ($files as $file)
            {
                if (!$file->canaccess($context->user()))
                { # current user cannot access the file, should not access notes
                    throw new \Framework\Exception\Forbidden('No access');
                }
            }
            $context->local()->addval('files', $files);

            // Put current note bean in context
            $note = $context->load('note', $nid);
            $context->local()->addval('note', $note);

            // Put current user bean in context
            $user = $context->user();
            $uid = $user->id;
            $context->local()->addval('user', $user);

            // Put author bean in context (could be an admin changing this note)
            $author = R::findOne('user', 'id = ?', [$note->user_id]);
            $context->local()->addval('author', $author);
            return '@content/modify.twig';
        }
    }
?>
