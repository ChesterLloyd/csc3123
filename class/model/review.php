<?php
/**
 * A model class for the RedBean object Review
 *
 * @author Chester Lloyd <c.lloyd7@ncl.ac.uk>
 *
 */
    namespace Model;
/**
 * A class implementing a RedBean model for Review beans
 */
    class Review extends \RedBeanPHP\SimpleModel
    {

/**
 * Return the rating (1 to 5) of a review
 *
 * @return int
 */
        public function rating() : int
        {
            return $this->bean->rating;
        }
    }
?>
