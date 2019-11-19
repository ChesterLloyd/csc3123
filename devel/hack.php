<?php
/**
 * You can put arbitrary code in here and run it
 */

    // // Make a test file
    // $file = R::dispense('file');
    // $file->name = "Lecture 1";
    // $file->type = "PDF";
    // $file->size = "2.1MB";
    // $fid = R::store($file);
    //
    // // Make a test note
    // $note = R::dispense('note');
    // $note->name = "Lecture 1";
    // $note->course = "Computer Science";
    // $note->module = "CSC3123";
    // $note->privacy = 1;
    // $note->comment = "Comment";
    // $note->xownFile[] = $file;     // Attach this file to the note (cascade delete)
    // $nid = R::store($note);

    // Make a sample review for logged in user to "Lecture 1" note 5
    $review = R::dispense('note');
    $review->favourite = 1;
    $review->rating = 4;
    $review->comment = "Very good notes";
    $rid = R::store($review);

    // Give review to note
    $note = R::load('note', 5);
    $note->xownReview[] = $review;      // Attach this review to the note
    R::store($note);

    // Give rating to user
    $userid = $context->user()->id;
    $user = R::load('user', $userid);
    $user->xownReview[] = $review;      // Attach this review to student also
    R::store($user);




    \Support\Context::getinstance()->local()->message(\Framework\Local::MESSAGE, 'Done');
?>
