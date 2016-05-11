<?php
/* $Id$ */
/**
 * @package IM-Engine
 * @subpackage lib
 */
/**
 * OrderedList provides HTML information to create a basic list in IM-Engine.
 * 
 * @package IM-Engine
 * @subpackage lib
 * @version $Rev$
 * @copyright © 2007 Sven Müller
 * @license 
 * @author Sven Müller <web.mueller@arcor.de>
 */
class HTMLlist
{   
    /**#@+ Types of lists */
    /** Unordered list type */
    const HTML_UNORDERED_LIST = -1;

    /** Ordered list type */
    const HTML_ORDERED_LIST = -2;
    /**#@-*/
    
    /**#@+ Class attributes */
    /** HTML list type */
    private $_type = self::HTML_ORDERED_LIST;

    /** Values for the list points*/
    private $_listPoints = array();

    /** HTML attributes for list points */
    private $_pointParams = null;

    /** HTML attributes for list */
    private $_listParams = '';

    /** HTML code for list */
    private $_htmllist = '';
    /**#@-*/

    public final function __construct($type, $listPoints, $pointParams = '', $listParams = '') {
	    if(is_array($listPoints) && count($listPoints) > 0) {
            // set values for list
            $this->_pointParams = $pointParams;
            $this->_listParams = $listParams;

            $this->setListPoints($listPoints);

            if ($type === 'ul' || $type === 'ol') {
                $this->setListType($type);
            }
        }
    }
    
    public final function setListType($type) {
        if(!is_string($type))
            return $this;
            
        switch($type):
            case 'ul':
                $this->_type = self::HTML_UNORDERED_LIST;
                break;
            case 'ol':
                $this->_type = self::HTML_ORDERED_LIST;
                break;
        endswitch;

        return $this;
    }

    /**
     * Enter description here...
     *
     * @param Array $listPoints
     * @return HTMLlist
     */
    public final function setListPoints($listPoints) {
	    if(is_array($listPoints) && count($listPoints) > 0)
	        $this->_listPoints = $listPoints;
        return $this;
    }
    
    public final function getListHTML() {
        // create HTML for list
        $this->_createListHTML();
        return $this->_htmllist;
    }

    /**
     * @return string
     */
    public final function getType() {
        return ($this->_type === self::HTML_UNORDERED_LIST) ? 'ul' : ($this->_type === self::HTML_ORDERED_LIST) ? 'ol' : '';
    }

    /**
     *
     */
    private final function _createListHTML() {
        $list = '<' . $this->getType() . ' ' . $this->_listParams . '>';
        
        if(count($this->_listPoints) > 0) {
            for($i = 0, $countListPoints = count($this->_listPoints); $i < $countListPoints; $i++) {
                if(is_array($this->_pointParams) && isset($this->_pointParams[$i])) {
                    $pointParam = $this->_pointParams[$i];
                } elseif(is_string($this->_listPoints)) {
                    $pointParam = $this->_pointParams;
                } else  {
                    $pointParam = '';
                }

                $list .= '<li ' . $pointParam . '>' . $this->_listPoints[$i] . '</li>';
            }
        }
   
        $list .= '</' . $this->getType() . '>';
        
        $this->_htmllist = $list;
    }
}
?>