<?php
/**
 * A wrapper so that users dont need to edit the FWContext class in order to add features.
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2016-2018 Newcastle University
 *
 */
    namespace Support;
/**
 * A wrapper for the real Context class that allows people to extend its functionality
 * in ways that are apporpriate for their particular website.
 */
    class Context extends \Framework\Context
    {
/**
 * Any functions that you need to be available through context.
 */

/**
* Returns a font awesome icon name for the given file type
*
* @param string     The file type to lookup
*
* @return string    The icon name
*/
        public function getFileIcon(string $ftype) : string
        {
            switch ($da['type'])
            {
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.oasis.opendocument.text':
            case 'text/plain':
                return 'file-word';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'file-excel';
                break;
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                return 'file-powerpoint';
                break;
            case 'application/pdf':
                return 'file-pdf';
                break;
            case 'application/zip':
                return 'file-archive';
                break;
            case 'application/json':
            case 'application/xml':
            case 'text/html':
            case 'text/css':
            case 'text/xml':
            case 'text/csv':
            case 'application/javascript':
                return 'file-code';
                break;
            case 'image/png':
            case 'image/jpeg':
            case 'image/gif':
                return 'file-image';
                break;
            default:
                return 'file';
                break;
            }
        }

/**
* Returns file size with an apporpriate unit, when given the size in bytes
*
* @param int     The file size in bytes
*
* @return string    The file size with the unit
*/
        public function getFileIcon(int $fsize) : string
        {
            $unit = 0;
            $units = array('B', 'KB', 'MB', 'GB');
            while ($fsize > 1024 && $unit < 3)
            {
                $unit ++;
                $fsize /= 1024;
            }
            return round($fsize, 2) . ' ' . $units[$unit];
        }
    }
?>
