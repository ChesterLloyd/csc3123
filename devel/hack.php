<?php
/**
 * You can put arbitrary code in here and run it
 */

    // Remove all current beans
    // R::wipe('review');
    // R::wipe('note');
    // R::wipe('file');

    // Make a test file
    $file = R::dispense('file');
    $file->name = "Lecture 1";
    $file->type = "PDF";
    $file->icon = "pdf";
    $file->size = "890 KB";
    $fid = R::store($file);

    // Make a test note
    $note = R::dispense('note');
    $note->name = "Lecture 1";
    $note->course = "Computer Science";
    $note->module = "CSC721";
    $note->privacy = 1;
    $note->downloads = 0;
    $note->upload = $context->utcnow();
    $note->description = "Introduction to HCI";
    $note->comment = "Lecture slides for L1";
    $note->xownFile[] = $file;     // Attach this file to the note (cascade delete)
    $nid = R::store($note);

    // Make a sample review for logged in user to "Lecture 1" note
    $review = R::dispense('review');
    $review->favourite = 1;
    $review->rating = 4;
    $review->comment = "Very good notes";
    $rid = R::store($review);

    // Give review to note
    // $note = R::load('note', 5);
    $note->xownReview[] = $review;      // Attach this review to the note
    R::store($note);

    // Give rating to user
    $userid = $context->user()->id;
    $user = R::load('user', $userid);
    $user->xownReview[] = $review;      // Attach this review to student also
    $user->xownNote[] = $note;          // This student is the author of this note
    R::store($user);




    \Support\Context::getinstance()->local()->message(\Framework\Local::MESSAGE, 'Done');
?>
