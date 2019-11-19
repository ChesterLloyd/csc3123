<?php
/**
 * You can put arbitrary code in here and run it
 */
    $prs = R::dispense('note');
    $prs->name = "Lecture 1";
    $prs->course = "Computer Science";
    $prs->module = "CSC3123";
    $prs->privacy = 1;
    $prs->comment = "Comment";
    $prs->file = "";
    $id = R::store($prs);


    \Support\Context::getinstance()->local()->message(\Framework\Local::MESSAGE, 'Done');
?>
