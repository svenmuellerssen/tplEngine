<?php
/* $Id$ */
/**
 * @package IM-Engine
 * @subpackage lib
 */
/**
 * Base html class with element functions.
 *
 * @package IM-Engine
 * @subpackage lib
 * @version $Rev$
 * @copyright © 2007 Sven Müller
 * @license
 * @author Sven Müller <web.mueller@arcor.de>
 */

abstract class Html
{
    /**#@+ HTML document type constants */
    /** HTML document type 4.01 Transitional */
    const HTML401_TRANS = 100;

    /** HTML document type 4.01 Strict */
    const HTML401_STRICT = 101;

    /** XHTML document type 1.0 Basic */
    const XHTML10_BASIC = 102;

    /** XHTML document type 1.01 Transitional */
    const XHTML10_TRANS = 103;

    /** XHTML document type 1.0 Strict */
    const XHTML10_STRICT = 104;

    /** XHTML document type 1.1 Strict */
    const XHTML11_STRICT = 105;

    /** XML document type */
    const XML10 = 106;

    /**#@-*/
    /**#@+ Error consts */
    const ERROR_NO_INPUT_DATA  = 110;
    /**#@-*/

    /**#@+ Class attributes */
    /** Type of html document */
    private $_doctype = self::XHTML11_STRICT;

    /** Charset for html document */
    private $_charset = 'UTF-8';

    /**#@- */

	/**
	 * Creates a table from array values
	 *
	 * @param Array $list
	 * @param Bool $looseIt
	 * @return String
	 */
//	public final function _constructTable($values)
//	{
//	    if(!($values instanceof Table))
//	    {
//		  return self::ERROR_NO_INPUT_DATA;
//	    }
//
//	    $_internalBuffer = '<table '.$values->getTableParams().'>'."\n";
//
//	    $max = 0;
//	    $cells = $values->getCells();
//        $cellSeek = 0;
//
//        for($i=0;$i < $values->getRowsNum();$i++)
//        {
//            $_internalBuffer .= '<tr '.$values->getRowParams().'>'."\n";
//            // Die Zellen durchgehen
//            for($j=0;$j<$values->getColumnsNum();$j++,$cellSeek++)
//            {
//                if(!isset($cells[$cellSeek]))
//                    $_internalBuffer .= '<td ></td>'."\n";
//                else
//                    $_internalBuffer .= '<td '.$cells[$cellSeek]['parameter'].
//                                        '>'.$cells[$cellSeek]['value'].'</td>'.
//                                        "\n";
//            }
//            $_internalBuffer .= '</tr>'."\n";
//        }
//        $_internalBuffer .= '</table>'."\n";
//
//	    return $_internalBuffer;
//	}

	/**
	 * Create a HTML drop down field from given parameters.
	 *
	 * @param String $name
	 * @param Array $options
	 * @param String $params
	 * @return string
	 */
	public final function _constructSelectField($name, $options, $params = null) {
		if(empty($name) || !is_string($name)) {
		    return false;
		}

		if(empty($options) || !is_array($options)) {
		    return false;
		}

		$_string = '<select name="' . $name . '" ' . $params . '>';
		while(list($name, $v) = each($options)) {
			if(isset($v->value)) {
				$_string .= '<option value="' . $v->value . '" ' . $v->selected . '>' . $v->name . '</option>';
			} else {
				$_string .= '<option value="' . $v[0] . '" ' . $v[1] . '>' . $name . '</option>';
			}
		}

		$_string .= '</select>';

		return $_string;
	}

    /**
     * @param $docType
     * @return bool
     */
    public final function setDocType($docType) {
	    $docType = intval($docType);

	    if($docType > 100 && $docType < 106)
            $this->_doctype = $docType;

        return $this;
	}

	/**
	 * Sets the inner charset
	 *
	 * @param String $charset
	 * @return Html
	 */
	public final function setCharset($charset) {
	    $charset = trim($charset);

	    if(is_string($charset) && $charset !== '')
	        $this->_charset = $charset;

        return $this;
	}

    /**
     * Returns the document type of a html document.
     *
     * @return string
     */
    public final function getDocType() {
        $doctypeString = '';

        switch($this->_doctype):
            case self::HTML401_TRANS:
                $doctypeString = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/html4/loose.dtd">';
                break;
            case self::HTML401_STRICT:
                $doctypeString = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict //EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/html4/strict.dtd">';
                break;
            case self::XHTML10_BASIC:
                $doctypeString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">';
                break;
            case self::XHTML10_TRANS:
                $doctypeString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
                break;
            case self::XHTML10_STRICT:
                $doctypeString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
                break;
            case self::XML10:
                 $doctypeString = '<?xml version="1.0" encoding="'.self::_charset.'" ?>' . "\n";
                 break;
            case self::XHTML11_STRICT:
            default:
                $doctypeString = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"' . "\n";
                $doctypeString .= '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
                break;
        endswitch;

        return $doctypeString . "\n";
    }

	/**
	 * Returns the inner charset
	 *
	 * @return String
	 */
	public final function getCharset() {
	    return $this->_charset;
	}
}
?>