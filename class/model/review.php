<?php
/**
 * A model class for the RedBean object Review
 *
 * @author Chester Lloyd <c.lloyd7@ncl.ac.uk>
 *
 */
    namespace Model;
/**
 * A class implementing a RedBean model for Page beans
 */
    class Review extends \RedBeanPHP\SimpleModel
    {

/**
 * Return the note's name
 *
 * @return int
 */
        public function rating() : int
        {
            return $this->bean->rating;
        }
    }
?>
