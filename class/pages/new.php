<?php
/**
 * A class that contains code to handle any requests for  /new/
 */
     namespace Pages;

     use \Support\Context as Context;
     use \Config\Config as Config;
/**
 * Support /new/
 */
    class New extends \Framework\Siteaction
    {
/**
 * Handle new operations
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
                $note = R::dispense('note');
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
                        $upl->savefile($context, $fd->filedata('uploads', $ix), $public, $context->user(), $ix);
                    }
                }
                else
                {
                    foreach(new \Framework\FAIterator('uploads') as $ix => $fa)
                    { # we only support private or public in this case so there is no flag
                        $upl = \R::dispense('upload');
                        $note->xownFile[] = $upl;
                        $upl->savefile($context, $fa, Config::UPUBLIC, $context->user(), $ix);
                    }
                }
                // Finally, store the note
                R::store($note);
            }
            return '@content/new.twig';
        }
    }
?>
