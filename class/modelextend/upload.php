<?php
/**
 * A trait that allows extending the model class for the RedBean object Upload
 *
 * Add any new methods you want the Uploadbean to have here.
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2018-2019 Newcastle University
 *
 */
    namespace ModelExtend;
/**
 * Upload table stores info about files that have been uploaded...
 */
    trait Upload
    {
/**
 * Determine if a user can access the file
 *
 * It is either the user or any admin that is allowd. If the note is set
 * to public, then active user can have access.
 *
 * @param object	$user	A user object
 * @param string    $op     r for read, u for update, d for delete
 *
 * @return bool
 */
        public function canaccess($user, $op = 'r') : bool
        {
            // Get note and see if author set it to public
            $note = \R::findOne('note', 'id=?', [$this->bean->note_id]);
            if ($note->privacy == 1)
            { # Ayone can see this if they are logged in and active
                return $this->bean->user->isactive();
            }
            // Else, only the author or an admin
            return $this->bean->user->equals($user) || $user->isadmin();
        }
/**
 * Hook for adding extra data to a file save.
 *
 * @param \Support\Context	$context	The context object for the site
 * @param int	$index	If you are reading data from an array fo files, this is the index
 *                      in the file. You may have paralleld data arrays and need this index.
 *
 * @return void
 */
        public function addData(\Support\Context $context, int $index) : void
        {
            // Get current file data
            $da = $context->formdata()->filedata('uploads', $index);
            // Assign file an icon based on its type
            $this->bean->icon = $context->getFileIcon($da['type']);
            // Calculate an appropriate unit size
            $this->bean->size = $context->getFileSize($da['size']);
        }
/**
 * Called when you try to trash to an upload. Do any cleanup in here
 *
 * @throws \Framework\Exception\Forbidden
 *
 * @return void
 */
        public function delete() : void
        {
/**** Do not change this code *****/
            $context = \Support\Context::getinstance();
            if (!$this->bean->canaccess($context->user(), 'd'))
            { // not allowed
                throw new \Framework\Exception\Forbidden;
            }
// Now delete the file
            unlink($context->local()->basedir().$this->fname);
/**** Put any cleanup code of yours after this line ****/
            /*
             * Your code goes here
             */
        }
    }
?>
