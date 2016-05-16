<?php
/* $Id$ */
/**
 * @package IM-Engine
 * @subpackage lib
 */
/**
 * TemplateBase makes some methods available to create complete hmtl elements like
 * <select>,<table>, <ol> and <ul>.
 * @package IM-Engine
 * @subpackage lib
 * @version $Rev$
 * @copyright © 2007 Sven Müller
 * @license
 * @author Sven Müller <web.mueller@arcor.de>
 */
require_once('Html.php');

class TemplateBase extends Html {
    /**#@+ Class attributes */
    /** Regulare path to the templates */
    protected static $_templatePath = '/';

    /** Path to the cache folder */
    protected static $_cachePath = '/';

    /**
     * Flag for switch on/off caching
     * @var String
     * */
    protected static $_caching = true;

    /**
     * Time to live of a cached file
     * @var Integer
     * */
    protected static $_cacheTime = 60;

    /**
     * Format of a full date
     * @var String
     * */
    protected static $_fullDateFormat = 'Y-m-d H:i:s';

    /**
     * Format of a date without time
     * @var String
     * */
    protected static $_dateFormat = 'Y-m-d';

    /**
     * Format of time
     * @var String
     * */
    protected static $_timeFormat = 'H:i:s';

    /**
     * Exits runtime after display
     * @var Bool
     * */
    protected $_exit = false;

    /**
     * Enter description here...
     *
     * @var bool
     */
    protected $_parse = false;

    /**
     * The values for parsing
     *
     * @var array
     */
    protected $_parseValues = array();

    /**#@- */
    /**
     * Sets the path to the template folder.
     *
     * @param String $templatePath
     * @return Void
     */
    public final function setTemplatePath($templatePath) {
        self::$_templatePath = $templatePath;
    }

    /**
     * Sets the path to the cache folder.
     *
     * @param String $cachePath
     * @return Void
     */
    public final function setCachePath($cachePath) {
        self::$_cachePath = trim($cachePath);
    }

    /**
     * Sets the time which cached documents are valid.
     *
     * @param Integer $time
     * @return Void
     */
    public final function setCacheTime($time = 1800) {
        $time=intval($time);
        if(is_numeric($time))
            self::$_cacheTime = $time;
    }

    /**
     * Sets the caching attribute to switch the caching on/off.
     *
     * @param Bool $caching
     * @return Void
     */
    public final function setCaching($caching) {
        if(is_bool($caching))
           self::$_caching = $caching;
    }

    /**
     * Sets the exit attribute to switch on/off the script finish by calling the "display" method.
     *
     * @param Bool $exit
     * @return Void
     */
    public final function setExit($exit = false) {
        if(is_bool($exit))
            $this->_exit = $exit;
    }

	/**
	 * Sets the format the full date has to be.
	 *
	 * @param String $format
	 * @return void
	 */
	public final function setFullDateFormat($format = 'Y-m-d H:i:s') {
	    if(is_string($format))
	        self::$_fullDateFormat = $format;
	}

	/**
	 * Sets the format the date has to be.
	 *
	 * @param String $format
	 * @return String
	 */
	public final function setDateFormat($format = 'Y-m-d') {
	    if(is_string($format))
	       self::$_dateFormat = $format;
	}

	/**
	 * Sets the format the time has to be.
	 *
	 * @param String $format
	 * @return String
	 */
	public final function setTimeFormat($format = 'H:i:s') {
	    if(is_string($format))
	       self::$_timeFormat = $format;
	}
	/**
	 * Enter description here...
	 *
	 * @param bool $on
	 */
	final public function setParseFlag($on=false) {
	    if(is_bool($on))
	       $this->_parse = $on;
	}
	/**
	 * Returns the full date from request (REQUEST_TIME).
	 *
	 * @return String
	 */
	public final function getFullDate() {
	    return date(self::$_fullDateFormat,$_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time());
	}
	/**
	 * Returns the date from request (REQUEST_TIME).
	 *
	 * @return String
	 */
	public final function getDate() {
	    return date(self::$_dateFormat,$_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time());
	}
	/**
	 * Returns the time from request (REQUEST_TIME).
	 *
	 * @return String
	 */
	public final function getTime() {
	    return date(self::$_timeFormat,$_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time());
	}

    /**
     * Returns an instance of datatype "Table"
     *
     * @param null $cells
     * @param string $rParams
     * @param string $tParams
     * @return HTMLtable
     */
    public final function getTableObject($cells = null, $rParams = '', $tParams = '') {
	    if(!class_exists('HTMLtable'))
	       require_once('HTMLtable.php');

	    return $table = new HTMLtable($cells, $rParams, $tParams);
	}

	public final function getListObject($type, $listPoints, $pointParams = '', $listParams = '') {
	    if(!class_exists('HTMLlist'))
	       require_once('HTMLlist.php');

	    return $HTMLlist = new HTMLlist($type, $listPoints, $pointParams, $listParams);
	}

	/**
	 * Produces a hash string
	 *
	 * @return String
	 */
	protected final function getHash() {
	    $params = '';
	    $arr = $_REQUEST;
        while(list($k, $v) = each($arr)) {
            if(is_string($v)) {
                $params .= $v;
            } elseif(is_array($v)) {
                while(list($k2, $v2) = each($v)) {
                    $params .= $v2;
                }
            }
        }

        $hash_string = $_SERVER['SERVER_ADDR'] . $params;
        $hash = sha1($hash_string, false);

        return $hash;
	}

	/**
	 * The given document string will be stored as cache file in the cache path
	 *
	 * @param String $document
	 * @return Bool
	 */
	public final function cacheDocument($document) {
	    $document = trim($document);

	    $fwrite = 0;

	    if(self::$_caching == true && (!is_string($document) || $document === '') && (!is_string(self::$_cachePath) || self::$_cachePath === '')) {
            $hash = $this->getHash();

	        $hash_handle = fopen(self::$_cachePath.$hash . '.html', 'w');

	        if($hash_handle) {
	           $fwrite = fwrite($hash_handle, $document);
	        }

            chmod(self::$_cachePath.$hash . '.html', 0777);
	        fclose($hash_handle);
	    }

	    if($fwrite > 0) {
            return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * Check if a cached document is available
	 *
	 * @return String
	 */
	protected final function checkCache() {
	    $found = '';

	    if(self::$_caching === true) {
	        $hash = self::getHash();

	        $dir_handle = dir(self::$_cachePath);

	        while (false !== ($entry = $dir_handle->read())) {
                if($entry == $hash . '.html') {

                    $found = $entry;
                    break;
                }
            }

            $dir_handle->close();
        }

        if($found !== '') {
            $file = self::$_cachePath . $found;
            $time = filemtime($file);

    	    if(($time + self::$_cacheTime) > $_SERVER['REQUEST_TIME']) {
                return $file;
    	    } else {
                unset($file);
            }
        }

        return '';
	}

    /**
     * Fetches a given string by replacing the binded tags..
     *
     * @param $string
     * @param bool $parse
     * @param $iterator
     * @return mixed
     */
    protected final function _fetch($string, $parse = false, $iterator = -1){

		$_internalStringBuffer = $string;

		if($this->_internalCount > 0) {
        	// interne Kopie
        	$_internalBinds = $this->_internalBinds;

            // alle Binds durchgehen
        	while(list($key,$arr) = each($_internalBinds)) {
        	    if(strpos($_internalStringBuffer, $arr['meta']['tag'])) {
        	        // Bei mehreren Werten pro Tag
            	    if($iterator != $key) {
                        $innerCount = $arr['meta']['innerCount'];
                        $tag = $arr['meta']['tag'];
                        $autoFetch = isset($arr['meta']['autoFetch']) ? $arr['meta']['autoFetch'] : 0;
                        $section = isset($arr['meta']['section']) ? $arr['meta']['section'] : 0;

                        $_internalBuffer = null;

                        if($innerCount !=0) {
                            for($i=0; $i < $innerCount; $i++) {
                                $obj = $arr[$i];

                                $_internalBuffer .= $obj->value;
                                // Soll der Tag aus den Binds entfernt werden?
                                if($obj->looseIt) {
                                    unset($this->_internalBinds[$key][$i]);
                                    $this->_internalBinds[$key]['meta']['innerCount']--;
                                }
                            }
                        }

                        //
                        if($section) {
                            $endTag = str_replace('<', '</', $tag);
                            $pregTagEnd = str_replace('</', '<\/', $endTag);

                            preg_match("/".$tag . ".*" . $pregTagEnd . "/Us", $_internalStringBuffer, $match);

                        	//Auto fetchen der Sektion
                            if(!empty($match[0]) && $autoFetch) {
                                $matchBuffer = $this->_fetch($match[0], $parse, $key);

                                $_internalStringBuffer = preg_replace("/" . $tag . ".*" . $pregTagEnd . "/Us", $matchBuffer, $_internalStringBuffer);
                                $_internalStringBuffer = str_replace($tag, '', $_internalStringBuffer);
                                $_internalStringBuffer = str_replace($endTag, '', $_internalStringBuffer);

                            } elseif(!empty($match[0]) && !empty($_internalBuffer)) {
                                $_internalStringBuffer = preg_replace("/" . $tag . ".*" . $pregTagEnd . "/Us", $_internalBuffer, $_internalStringBuffer);

                                $_internalStringBuffer = str_replace($tag, '', $_internalStringBuffer);
                                $_internalStringBuffer = str_replace($endTag, '', $_internalStringBuffer);
                        	}
                        } else {
                            $_internalStringBuffer = str_replace($tag, $_internalBuffer, $_internalStringBuffer);
                        }
                    }
        	    }
        	}

        	if($this->_parse || $parse) {
        	   $this->_fetchByParsedString($_internalStringBuffer);
        	}
		}

		// Removes comments
		$_internalStringBuffer = preg_replace('/\/\*\*.*\*\//Us', '', $_internalStringBuffer);
        // Removes non replaced tags
        // mechanismus, der bei versch. aktionen (binden, config,
        // parsetags angeben) gleich liste führt, um am Ende per regex
        // Überbleibsel aus dem Ausgabe-Strring zu entfenen
        $_internalStringBuffer = preg_replace('/<doc:.*>/Us', '', $_internalStringBuffer);

		return $_internalStringBuffer;
	}

    /**
     * @param $_internalStringBuffer
     */
    final public function _fetchByParsedString(&$_internalStringBuffer) {
        $parseValues = array();
        reset($this->_parseValues);

        while(list($key, $value) = each($this->_parseValues)) {
            preg_match_all('/' . $value . '/Us', $_internalStringBuffer, $parse_match);

            if(!empty($parse_match)) {
                while(list(, $pvalue) = each($parse_match[0])) {
                    if(!in_array($pvalue, $parseValues)) {
                        $parseValues[] = $pvalue;

                        if(isset($this->_internalIndex[$pvalue])) {
                            $bindvalue = $this->_internalBinds[$this->_internalIndex[$pvalue]];

                            // Meta-Angaben
                            $tag = $bindvalue["meta"]["tag"];
                            $innerCount = $bindvalue["meta"]["innerCount"];
                            $section = $bindvalue["meta"]["section"];
                            $autoFetch = $bindvalue["meta"]["autoFetch"];
                            $_internalBuffer = null;

                            for($i=0;$i < $innerCount;$i++) {
                                $_internalBuffer .= $bindvalue[$i]->value;

                                // Soll der Tag aus den Binds entfernt werden?
                                if($bindvalue[$i]->looseIt) {
                                    unset($this->_internalBinds[$this->_internalIndex[$pvalue]][$i]);
                                    $this->_internalBinds[$this->_internalIndex[$pvalue]]['meta']['innerCount']--;
                                }
                            }

                            //
                            if($section) {
                                $endTag = str_replace('<', '</', $tag);
                                $pregTagEnd = str_replace('</', '<\/', $endTag);

                                preg_match("/" . $tag . ".*" . $pregTagEnd . "/Us", $_internalStringBuffer, $match);

                            	//Auto fetchen der Sektion
                                if(!empty($match[0]) && $autoFetch) {
                                    $matchBuffer = $this->_fetch($match[0], true, $key);

                                    $_internalStringBuffer = preg_replace("/" . $tag . ".*" . $pregTagEnd . "/Us", $matchBuffer, $_internalStringBuffer);
                                    $_internalStringBuffer = str_replace($tag, '', $_internalStringBuffer);
                                    $_internalStringBuffer = str_replace($endTag, '', $_internalStringBuffer);

                                } elseif(!empty($match[0]) && !empty($_internalBuffer)) {
                                    $_internalStringBuffer = preg_replace("/" . $tag . ".*" . $pregTagEnd . "/Us", $_internalBuffer,$_internalStringBuffer);

                                    $_internalStringBuffer = str_replace($tag, '', $_internalStringBuffer);
                                    $_internalStringBuffer = str_replace($endTag, '', $_internalStringBuffer);
                            	}
                            } else {
                                $_internalStringBuffer = str_replace($tag,$_internalBuffer, $_internalStringBuffer);
                            }
                        }
                    }
                }
            }
    	}
	}
}
?>
