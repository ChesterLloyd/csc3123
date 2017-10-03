<?php
/**
 * A class that handles Ajax calls
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2017 Newcastle University
 *
 */
/**
 * Handles Ajax Calls.
 */
    class Ajax extends \Framework\Ajax
    {
/**
 * Any Ajax for your system goes in here.
 *
 *********  Make sure that you call the parent handle method for anything you are not handling yourself!! ***********
 */
/**
 * Handle AJAX operations
 *
 * @param object	$context	The context object for the site
 *
 * @return void
 */
        public function handle($context)
        {
            parent::handle($context);
        }
    }
?>
