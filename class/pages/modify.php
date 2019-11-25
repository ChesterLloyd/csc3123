<?php
/**
 * A class that contains code to handle any requests for  /modify/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
     use \Framework\Local as Local;
     use \R as R;
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
        { # Get existing note to page

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
            $note = R::load('note', $nid);

            // Do this if form is submitted
            $fd = $context->formdata();
            if (isset($_POST['modify']))
            { # There has been a post

                // Update note bean with the form data
                $note->name = $fd->post('name', 'No name');
                $note->course = $fd->post('course', 'Unknown Course');
                $note->module = $fd->post('module', 'Unknown Module');
                $note->privacy = $fd->post('privacy', 1);
                $note->description = $fd->post('description', 'No description');
                $note->comment = $fd->post('comment', 'No comments.');
                R::store($note);

                // Upload new files
                $nfiles = 0;
                $tfiles = 0;
                if (Config::UPUBLIC && Config::UPRIVATE)
                { # need to check the flag could be either private or public
                    $tfiles = sizeof($fd->posta('public'));
                    foreach ($fd->posta('public') as $ix => $public)
                    {
                        $upl = R::dispense('upload');
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
                        $upl = R::dispense('upload');
                        $save = $upl->savefile($context, $fa, Config::UPUBLIC, $context->user(), $ix);
                        if ($save)
                        {
                            $note->xownFile[] = $upl;
                            $nfiles ++;
                        }
                    }
                }
                if ($nfiles > 0)
                { # New files have been saved
                    R::store($note);
                    if ($nfiles == $tfiles)
                    { # All files were uploaded, display a success message
                        $context->local()->message(Local::MESSAGE, 'Your files have been uploaded.');
                    }
                    else
                    { # Not all files were uploaded
                        $context->local()->message(Local::WARNING, 'Some files have not been uploaded.');
                    }
                }
                elseif ($fd->hasfile('uploads'))
                { # No files were uploaded
                    $context->local()->message(Local::ERROR, 'There was an issue uploading some of your files.');
                }

                // Start deleting files only if we have 1 or more remaining
                if ($fd->post('delete') != '')
                { # Something marked to delete
                    $deleteFiles = explode(',', $fd->post('delete'));
                    $removeCount = sizeof($deleteFiles);
                    $totalCount = R::count('upload', 'note_id = ?', [$nid]);
                    $context->local()->addval('deleteFiles', $deleteFiles);
                    $context->local()->addval('removeCount', $removeCount);
                    $context->local()->addval('totalCount', $totalCount);

                    if (($nfiles > 0) || ($removeCount < $totalCount))
                    { # Delete any note slected to remove
                        for ($i = 0; $i < $removeCount; $i ++)
                        {
                            // $file = R::findOne('upload', 'id = ?', [(int)$dfiles[$i]]);
                            // $file->delete();
                        }
                    }
                    elseif ($removeCount != 0)
                    { # Do not delete notes, will not be enough left
                        $context->local()->message(Local::ERROR, 'No files were deleted, there must be at least 1 remaining.');
                    }
                }





            }

            // Update file list (could be new ones from above)
            $files = R::find('upload', 'note_id = ?', [$nid]);
            $context->local()->addval('files', $files);
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
