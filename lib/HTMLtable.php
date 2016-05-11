<?php
/* $Id$ */
/**
 * @package IM-Engine
 * @subpackage lib
 */
/**
 * HTMLtable provides HTML table information to create a basic table in IM-Engine.
 * 
 * @package IM-Engine
 * @subpackage lib
 * @version $Rev$
 * @copyright © 2007 Sven Müller
 * @license 
 * @author Sven Müller <web.mueller@arcor.de>
 */
class HTMLtable
{
    /**#@+*/
    /** Constants of failure*/
    const ERR_NO_CELLS = 11005;
    /**#@-*/

    /**#@+ Class attributes */
    /** An array with the table cells */
    private $cells        = array();

    /** The number of rows the table has */
    private $rows         = 0;

    /** The number of columns per row */
    private $columns      = 0;

    /** The html attributes of the row tags */
    private $rowParams    = '';

    /** The html attributes of the table tag */
    private $tableParams  = '';

    /***/
    private $_tableHTML = '';
    /**#@- */

    /**
     * Instantiate the Table object
     *
     * @param Array $cells
     * @param string $rParams
     * @param string $tParams
     */
    public final function __construct($cells = null, $rParams = '', $tParams = '') {
        if(is_array($cells) && count($cells) > 0)
            $this->cells = $cells;
        
        $rParams = trim($rParams);
        if(is_string($rParams) && $rParams !== '' )
            $this->rowParams = $rParams;
            
        $tParams = trim($tParams);
        if(is_string($tParams) && $tParams !== '')
            $this->tableParams = $tParams;
    }

    /**
     * Sets the attribute "cells" with cells array
     *
     * @param Array $arrCells
     * @return HTMLtable
     */
    public final function setCells($arrCells) {
        if(is_array($arrCells) && count($arrCells) > 0)
            $this->cells = $arrCells;
        return $this;
    }

    /**
     * Returns the array with all cells 
     *
     * @return Array
     */
    public final function getCells() {
        return $this->cells;
    }
    /**
     * Sets the number of the rows
     *
     * @param Integer $num
     * @return Void
     */
    public final function setRowsNum($num) {
        if(is_long($num) && $num > 0)
            $this->rows = intval($num);
    }

    /**
     * Returns the number of rows in the table
     *
     * @return Integer
     */
    public final function getRowsNum() {
        return $this->rows;
    }
    /**
     * Sets the number of columns per row
     *
     * @param Integer $num
     * @return Void
     */
    public final function setColumnsNum($num) {
        if(is_long($num) && $num > 0)
            $this->columns = intval($num);
    }
    /**
     * Returns the number of columns per row
     *
     * @return Integer
     */
    public final function getColumnsNum() {
        return $this->columns;
    }
    /**
     * Sets the row params
     *
     * @param String $params
     * @return Void
     */
    public final function setRowParams($params)
    {
        $params = trim($params);
        if(is_string($params) && $params !== '')
            $this->rowParams = $params;
    }
    /**
     * Returns the params of the rows
     *
     * @return String
     */
    public final function getRowParams() {
        return $this->rowParams;
    }
    /**
     * Sets the params of the table
     *
     * @param String $params
     * @return Void
     */
    public final function setTableParams($params)
    {
        $params = trim($params);
        if(is_string($params) && $params !== '')
            $this->tableParams = $params;
    }

    /**
     * Returns the params of the table
     *
     * @return String
     */
    public final function getTableParams() {
        return $this->tableParams;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public final function getTableHTML() {
        $this->_createTableHTML();
        
        return $this->_tableHTML;
    }

    /**
     * Enter description here...
     *
     * @return int|void
     */
    private final function _createTableHTML() {
	    $cells = $this->getCells();
	    
        if(count($cells) === 0)
            return self::ERR_NO_CELLS;
            
	    $_internalBuffer = '<table ' . $this->getTableParams() . '>' . "\n";
	    
	    $max = 0;
        $cellSeek = 0;
        
        for($i=0, $n=count($cells); $i < $n; $i++) {
            $_internalBuffer .= '<tr ' . $this->getRowParams() . '>' . "\n";
            // Die Zellen durchgehen
            for($j=0, $m=count($cells[$i]); $j < $m; $j++, $cellSeek++) {
                if(!isset($cells[$i][$cellSeek]))
                    $_internalBuffer .= '<td ></td>' . "\n";
                else
                    $_internalBuffer .= '<td ' . $cells[$i][$cellSeek]['attributes'].
                                        '>' . $cells[$i][$cellSeek]['value'] . '</td>'.
                                        "\n";
            }

            $_internalBuffer .= '</tr>' . "\n";
        }

        $_internalBuffer .= '</table>' . "\n";
	    
	    $this->_tableHTML = $_internalBuffer;
    }
}
?>