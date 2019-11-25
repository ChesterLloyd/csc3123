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

                // Upload new files
                $nfiles = 0;
                $tfiles = 0;
                if (Config::UPUBLIC && Config::UPRIVATE)
                { # need to check the flag could be either private or public
                    $tfiles = sizeof($fd->posta('public'));
                    foreach ($fd->posta('public') as $ix => $public)
                    {
                        $upl = \R::dispense('upload');
                        $save = $upl->savefile($context, $fd->filedata('uploads', $ix), $public, $context->user(), $ix);
                        if ($save)
                        {
                            $note->xownFile[] = $upl;
                            $nfiles ++;
                        }
                    }
                }
                else
                {
                    $tfiles = sizeof(new \Framework\FAIterator('uploads'));
                    foreach(new \Framework\FAIterator('uploads') as $ix => $fa)
                    { # we only support private or public in this case so there is no flag
                        $upl = \R::dispense('upload');
                        $save = $upl->savefile($context, $fa, Config::UPUBLIC, $context->user(), $ix);
                        if ($save)
                        {
                            $note->xownFile[] = $upl;
                            $nfiles ++;
                        }
                    }
                }
                if ($nfiles > 0)
                { # Number of successfully saved files is > 0, save the note
                    $nid = \R::store($note);

                    // Then assign it to the author (logged in user)
                    $uid = $context->user()->id;
                    $user = \R::load('user', $uid);
                    $user->xownNote[] = $note;
                    \R::store($user);

                    if ($nfiles == $tfiles)
                    { # All files were saved, display a success message
                        $context->local()->message(Local::MESSAGE, 'Your notes have been uploaded.');
                    }
                    else
                    { # Not all files were saved
                        $context->local()->message(Local::WARNING, 'Your notes have been uploaded, but some files have not been saved.');
                    }
                }
                else
                { # Note not saved as no files were uploaded
                    $context->local()->message(Local::ERROR, 'Your notes have not been saved as there was an issue with the file(s) selected.');
                }
            }
            return '@content/add.twig';
        }
    }
?>
