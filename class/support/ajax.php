<?php
/**
 * A class that handles Ajax calls
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2017-2019 Newcastle University
 *
 */
    namespace Support;

    use Support\Context as Context;
    use \R as R;
/**
 * Handles Ajax Calls.
 */
    class Ajax extends \Framework\Ajax
    {
/**
 * Add functions that implement your AJAX operations here and register them
 * in the handle method below.
 */
/**
 * Adds a note to the user's favourites
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 */
        public function addFavourite(Context $context) : void
        {
            $rest = $context->rest();
            $nid = $rest[1];
            $note = R::findOne('note', 'id = ?', [$nid]);
            $uid = $context->user()->id;
            // Find review for this user and this note (favourite flag stored in reviews)
            $review = R::findOne('review', 'note_id = ? AND user_id = ?', [$nid, $uid]);

            if (!$review)
            { # No bean has been loaded (no review, so make one)
                $review = R::dispense('review');
                $review->favourite = 1;
                $review->rating = NULL;
                $rid = R::store($review);

                // Give review to note
                $note->xownReview[] = $review;
                R::store($note);

                // Give review to user
                $user = R::load('user', $uid);
                $user->xownReview[] = $review;
                R::store($user);

                // Check if the review bean has been added
                if ($rid > 0)
                {
                    echo "This note has been added to your favourites.";
                }
            }
            else
            {
                if ($review->favourite == 1)
                { # Already a favourite
                    echo "This note is already saved in your favourites.";
                }
                else
                { # Update review bean
                    $review->favourite = 1;
                    R::store($review);
                    echo "This note has been added to your favourites.";
                }
            }
        }
/**
 * Removes a note from the user's favourites
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 */
        public function removeFavourite(Context $context) : void
        {
            $rest = $context->rest();
            $nid = $rest[1];
            $note = R::findOne('note', 'id = ?', [$nid]);
            $uid = $context->user()->id;
            // Find review for this user and this note (favourite flag stored in reviews)
            $review = R::findOne('review', 'note_id = ? AND user_id = ?', [$nid, $uid]);

            if (!$review)
            { # No bean has been loaded (no review, nothing to do)
                echo "This note has already been removed from your favourites.";
            }
            else
            {
                if ($review->favourite == 1)
                { # Update review bean
                    $review->favourite = 0;
                    R::store($review);
                    echo "This note has been removed from your favourites.";
                }
                else
                { # Review exists, but not as a favourite
                    echo "This note has already been removed from your favourites.";
                }
            }
        }
/**
 * Adds a review for a note, by a user. Updates the rating if a review exists.
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 */
        public function addReview(Context $context) : void
        {
            $rest = $context->rest();
            $nid = $rest[1];
            $rating = $rest[2];
            $note = R::findOne('note', 'id = ?', [$nid]);
            $uid = $context->user()->id;
            // Find review for this user and this note (favourite flag stored in reviews)
            $review = R::findOne('review', 'note_id = ? AND user_id = ?', [$nid, $uid]);

            if (!$review)
            { # No bean has been loaded (no review, add a new one)
                $review = R::dispense('review');
                $review->favourite = 0;
                $review->rating = $rating;
                $rid = R::store($review);

                // Give review to note
                $note->xownReview[] = $review;
                R::store($note);

                // Give review to user
                $user = R::load('user', $uid);
                $user->xownReview[] = $review;
                R::store($user);

                echo "Your review has been added.";
            }
            else
            { # Update review bean
                if ($review->rating === NULL)
                { # New review
                    echo "Your review has been added.";
                }
                else
                { # Updated existing review
                    echo "Your review has been updated.";
                }
                $review->rating = $rating;
                R::store($review);
            }
        }
/**
 * Deletes a note and all corresponding upload beans
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 *
 * @throws \Framework\Exception\Forbidden
 */
        public function delete(Context $context) : void
        {
            $rest = $context->rest();
            $nid = $rest[1];
            $note = R::findOne('note', 'id = ?', [$nid]);
            if (!$note)
            { # Note bean has been loaded (no note to delete)
                throw new \Framework\Exception\Forbidden('No access');
            }
            else
            { # Note does exist, try to delete
                $uid = $context->user()->id;
                $access = false;
                $uploads = R::findAll('upload', 'note_id = ?', [$nid]);
                foreach ($uploads as $upload)
                { # Iterate through each uploaded file bean attached to the note
                    if ($upload->canaccess($context->user()))
                    { # User has access to delete it
                        R::trash($upload);
                        $access = true;
                    }
                    else
                    { # User cannot access the files, should not attempt anymore
                        throw new \Framework\Exception\Forbidden('No access');
                    }
                }
                if ($access)
                { # Could access the files, time to delete the note bean
                    R::trash($note);
                    echo "This note has been removed.";
                }
            }
        }
/**
 * Downlaods a note and update the update counter
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 */
        public function download(Context $context) : void
        { # Get data from rest
            $rest = $context->rest();
            if (count($rest) == 2 && $rest[0] == 'getfile')
            { # this is access by upload ID
                $file = R::load('upload', $rest[1]);
                if ($file->getID() != 0)
                {
                    $note = R::load('note', $file->note()->getID());
                    $note->downloads = ($note->downloads + 1);
                    R::store($note);
                    echo "File download started.";

                    // Make a new instance of Getfile and call it
                    $getfile = new \Framework\Pages\Getfile;
                    // URL to download files becomes: getfile/file/upload id
                    $getfile->handle($context);
                }
                else
                {
                    echo "Error accessing the note.";
                }
            }
            else
            {
                echo "Error downloading this file.";
            }
        }
/**
 * If you are using the pagination or search hinting features of the framework then you need to
 * add some appropriate vaues into these arrays.
 *
 * The key to both the array fields is the name of the bean type you are working with.
 */
/**
 * @var array   Values controlling whether or not pagination calls are allowed
 */
        private static $allowPaging = [
            // 'bean' => [TRUE, [['ContextName', 'RoleName']]] // TRUE if login needed, then an array of roles required in form [['context name', 'role name']...] (can be empty)
        ];
/**
 * @var array   Values controlling whether or not search hint calls are allowed
 */
        private static $allowHints = [
            // 'bean' => ['field', TRUE, [['ContextName', 'RoleName']]] // TRUE if login needed, then an array of roles required in form [['context name', 'role name']...] (can be empty)
        ];
/**
 * @var array   Values controlling whether or not calls on the bean operation are allowed
 */
        private static $allowBean = [
            // [[['ContextName', 'RoleName']], [ 'bean' => [...fields...], ...] // an array of roles required in form [['context name', 'role name']...] (can be empty)
        ];
/**
 * @var array   Values controlling whether or not calls on the toggle operation are allowed
 */
        private static $allowToggle = [
            // [[['ContextName', 'RoleName']], [ 'bean' => [...fields...], ...]] // an array of roles required in form [['context name', 'role name']...] (can be empty)
        ];
/**
 * @var array   Values controlling whether or not calls on the table operation are allowed
 */
        private static $allowTable = [
            // [[['ContextName', 'RoleName']], [ 'bean', ....] // an array of roles required in form [['context name', 'role name']...] (can be empty)
        ];
/**
 * @var array   Values controlling whether or not bean operations are logged for certain beans
 */
        private static $audit = [
            // 'bean'..... A list of bean names
        ];
/**
 * Handle AJAX operations
 *
 * @param \Support\Context	$context	The context object for the site
 *
 * @return void
 */
        public function handle(Context $context) : void
        {
            //$this->operation(['yourop', ...], [TRUE, [['ContextName', 'RoleName'],...]]);
            $this->operation(['addFavourite', 'removeFavourite', 'addReview', 'delete', 'download'], [TRUE, [['Site', 'Student'], ['Site', 'Teacher']]]);
            // TRUE if login needed, then an array of roles required in form [['context name', 'role name']...] (can be empty)
            $this->pageOrHint(self::$allowPaging, self::$allowHints);
            $this->beanAccess(self::$allowBean, self::$allowToggle, self::$allowTable, self::$audit);
            parent::handle($context);
        }
    }
?>
