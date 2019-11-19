<?php
/**
 * You can put arbitrary code in here and run it
 */

    // Make a test note
    $note = R::dispense('note');
    $note->name = "Lecture 1";
    $note->course = "Computer Science";
    $note->module = "CSC3123";
    $note->privacy = 1;
    $note->comment = "Comment";
    $note->file = "";
    $nid = R::store($note);

    // Make a test file
    $file = R::dispense('file');
    $file->name = "Lecture 1";
    $file->type = "PDF";
    $file->size = "2.1MB"
    $bid = R::store($file);

    // Attach this file to the note (cascade delete)
    $book = R::load('note', $bid));
    $note->xcontainsFile[] = $file;
    R::store($note);


    \Support\Context::getinstance()->local()->message(\Framework\Local::MESSAGE, 'Done');
?>
