<?php
/* $Id$ */
/**
 * @package IM-Engine
 * @subpackage lib
 */
/**
 * This class handles templates and offer some methods to create html elements like
 * <>select<>, <>input<> and simple <>table<>-constructs.
 *
 * @package IM-Engine
 * @subpackage lib
 * @version $Rev$
 * @copyright (c) 2007 Sven Müller
 * @license
 * @author Sven Müller <web.mueller@arcor.de>
 * @todo Exception-Handling via Exception-Wrapper-Klasse
 * @todo Dokumentation in Doku-Wiki
 */
//include '/lab/srv/dlt2.localnet/libraries/core/system/lib/TemplateBase.php';
class Template extends TemplateBase {
	/**#@+ Class attributes */
	/** */
	protected $_internalBinds = array();
	/** */
	protected $_internalCount	= 0;
	/**	 */
	protected $_internalIndex = array();
	/**#@- */

  /**
   * Enter description here...
   *
   * @param String $templatePath
   * @param Bool $caching
   * @param String $cachePath
   * @param String $charset
   */
	public function __construct($templatePath = './', $charset = 'UTF-8', $caching = false, $cachePath = './') {
    $cachedFilePath = null;
      // Caching parameters
      if(is_bool($caching))
        parent::setCaching($caching);

      if(is_string($cachePath))
        parent::setCachePath($cachePath);

      // Path to template folder
      if(is_string($templatePath))
        self::setTemplatePath($templatePath);
      // Charset of the site
      if(is_string($charset))
        parent::setCharset($charset);

      $this->setParseConfig(array('doc', 'base', 'conf'));
	}

    /**
     * Binds a key value pair for replacing them in the displayed template
     *
     * @param string $tag
     * @param null $value
     * @param bool $section
     * @param bool $autoFetch
     * @param bool $looseIt
     * @return bool
     */
  public final function bindTo($tag, $value = null, $section = false, $autoFetch = false, $looseIt = true) {
    if( $tag === '' || (!empty($value) && $section == true && $autoFetch == true)) return false;

    if(!is_bool($looseIt)) $looseIt = true;
    if($autoFetch == false) $autoFetch = 0;
    if($section == false) $section = 0;
    if($looseIt == false) $looseIt = 0;

    $tag = trim($tag);

    $_internalIndexCount = array_key_exists($tag,$this->_internalIndex)?$this->_internalIndex[$tag]:$this->_internalCount;

    if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
      $this->_internalBinds[$_internalIndexCount]['meta'] = array(
        'tag' => $tag,
        'innerCount' => 0,
        'section' => $section,
        'autoFetch' => $autoFetch
      );

    $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

    if(!empty($value)) {
      $this->_internalBinds[$_internalIndexCount][$innerCount] = new stdClass();
      $this->_internalBinds[$_internalIndexCount][$innerCount]->value	= $value;
      $this->_internalBinds[$_internalIndexCount][$innerCount]->looseIt = $looseIt;
      // Den inneren Zähler hochsetzen
      $this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;
    }

    if($innerCount == 0) {
      $this->_internalIndex[$tag] = $this->_internalCount;
      $this->_internalCount++;
    }

    return true;
  }

    /**
     * @param $tag
     * @param $path
     * @param bool $looseIt
     * @return bool
     */
  final public function bindFileTo($tag, $path, $looseIt = true) {
    if(!is_string($tag) || !is_string($path)) return false;

    if(file_exists($path)) {
      $_internalIndexCount = array_key_exists($tag, $this->_internalIndex) ? $this->_internalIndex[$tag] : $this->_internalCount;

      if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
        $this->_internalBinds[$_internalIndexCount]['meta'] = array(
          'tag' => $tag,
          'innerCount' => 0
        );

      $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

      $filecontent = file_get_contents($path,true);

      $this->_internalBinds[$_internalIndexCount][$innerCount] = new stdClass();
      $this->_internalBinds[$_internalIndexCount][$innerCount]->value	= $filecontent;
      $this->_internalBinds[$_internalIndexCount][$innerCount]->looseIt = $looseIt;
      $this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;

      if($innerCount == 0) {
        $this->_internalIndex[$tag] = $this->_internalCount;
        $this->_internalCount++;
      }
    }

    return true;
	}

    /**
     * @param $tag
     * @param $name
     * @param $options
     * @param null $params
     * @param bool $looseIt
     * @return bool
     */
  public final function bindSelectFieldTo($tag, $name, $options = [], $params = null, $looseIt = true) {
		if(!is_string($tag) || $tag === '' || !is_string($name) || $name === '' || empty($options)) return false;

		if(!is_bool($looseIt)) $looseIt = true;

		if(is_array($options)) {
			$tag = trim($tag);

      $_internalIndexCount = array_key_exists($tag, $this->_internalIndex) ? $this->_internalIndex[$tag] : $this->_internalCount;

      if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
        $this->_internalBinds[$_internalIndexCount]['meta'] = array(
          'tag' => $tag,
          'innerCount' => 0
        );

      $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

      $html_string = $this->_constructSelectField($name, $options, $params);

      $this->_internalBinds[$_internalIndexCount  ][$innerCount] = new stdClass();
			$this->_internalBinds[$_internalIndexCount][$innerCount]->value	= $html_string;
			$this->_internalBinds[$_internalIndexCount][$innerCount]->looseIt = $looseIt;
      $this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;

      if($innerCount == 0) {
        $this->_internalIndex[$tag] = $this->_internalCount;
        $this->_internalCount++;
      }
		}

    return true;
	}

	/**
	 * Creates an input field from parameter and binds it.
	 *
	 * @param string $tag
	 * @param string $name
	 * @param string,int,float $value
	 * @param string $type
	 * @param string $img[optional]
	 * @param string $params[optional]
	 * @param bool $looseIt[optional]
	 * @return bool
	 */
	public final function bindInputTo($tag, $name, $value, $type, $img = '', $params = null, $looseIt = true) {
		if(!is_string($tag) || $tag === '' || !is_string($name) || $name === '' || !is_string($type) || $type === '') return false;

		if(!is_bool($looseIt)) $looseIt = true;

		$tag = trim($tag);

    $_internalIndexCount = array_key_exists($tag, $this->_internalIndex) ? $this->_internalIndex[$tag] : $this->_internalCount;

    if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
      $this->_internalBinds[$_internalIndexCount]['meta'] = array(
        'tag' => $tag,
        'innerCount' => 0
      );

    $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

    $html_string = '';

		switch ($type):
			case 'checkbox':
			case 'radio':
			case 'text':
			case 'password':
				$html_string = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" ' . $params . '/>';
				break;
			case 'button':
			case 'submit':
			case 'image':
				if(!empty($img)) $html_string = '<input type="' . $type . '" name="' . $name . '" src="' . $img . '" value="' . $value . '" ' . $params . '/>';
				else $html_string = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" ' . $params . '/>';
				break;
		endswitch;

    $this->_internalBinds[$this->_internalCount][$innerCount] = new stdClass();
		$this->_internalBinds[$this->_internalCount][$innerCount]->value = $html_string;
		$this->_internalBinds[$this->_internalCount][$innerCount]->looseIt = $looseIt;
    $this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;

    if($innerCount == 0) {
      $this->_internalIndex[$tag] = $this->_internalCount;
      $this->_internalCount++;
		}

    return true;
	}

	/**
	 * Fetches a section from given template and bind it to given tag
	 *
	 * @param string $path
	 * @param string $sectionTag
	 * @param int $count[optional]
	 * @param array $valueArray[optional]
	 * @param bool $looseIt[optional]
	 * @return bool
	 */
	public final function bindFetchedListTo($path, $sectionTag, $count = 1, $valueArray = null, $looseIt = true) {
		if(!file_exists($path) || !is_string($sectionTag) || $sectionTag === '' || !is_string($sectionTag)) return false;

		// get template as string
		$_internalStringBuffer = file_get_contents($path);
		// make end tag
		$tagEnd = str_replace('<', '</', $sectionTag);
		$sectionTagEnd = str_replace('</', '<\/', $tagEnd);
		// get section by tags
		preg_match('/' . $sectionTag . '.*' . $sectionTagEnd . '/Us', $_internalStringBuffer, $match);

		if(is_array($valueArray)){
      $valueRow = '';

			for($i=0; $i < $count; $i++) {
				$singleRow = $match[0];

				$values = $valueArray[$i];
				while(list($section, $value) = each($values)) {
          if(!is_array($value))
            $singleRow = str_replace($section, $value, $singleRow);
          else
            while(list($key, $val) = each($value)) $singleRow = str_replace($key, $val, $singleRow);
				}

				$singleRow = str_replace('<base:listcounter>', $i, $singleRow);
				$valueRow .= $singleRow;
			}

			// Parse for tags in valuerow
			$this->_fetchByParsedString($valueRow);

      $_internalIndexCount = array_key_exists($sectionTag, $this->_internalIndex)
        ? $this->_internalIndex[$sectionTag]
        : $this->_internalCount;

      if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
        $this->_internalBinds[$_internalIndexCount]['meta'] = array(
          'tag' => $sectionTag,
          'section' => 1,
          'innerCount' => 0
        );

      $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

      $this->_internalBinds[$_internalIndexCount][$innerCount] = new stdClass();
      $this->_internalBinds[$_internalIndexCount][$innerCount]->value	= $valueRow;
      $this->_internalBinds[$_internalIndexCount][$innerCount]->looseIt = $looseIt;
      $this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;

      if($innerCount == 0) {
        $this->_internalIndex[$sectionTag] = $this->_internalCount;
        $this->_internalCount++;
      }
		}

    return true;
	}

    /**
     * Enter description here...
     *
     * @param string $tag
     * @param string $type
     * @param array $values
     * @param string $innerParams
     * @param string $outerParams
     * @param bool $looseIt[optional]
     * @return bool
     */
	public final function bindHTMLElementTo($tag, $type, $values, $innerParams = '', $outerParams = '', $looseIt = true) {
    if (!is_string($tag) || $tag === '') return false;

		if(!is_bool($looseIt)) $looseIt = true;

    $_internalIndexCount = array_key_exists($tag,$this->_internalIndex) ? $this->_internalIndex[$tag] : $this->_internalCount;

    if(!isset($this->_internalBinds[$_internalIndexCount]['meta']))
      $this->_internalBinds[$_internalIndexCount]['meta'] = array(
        'tag' => $tag,
        'innerCount' => 0
      );

    $innerCount = $this->_internalBinds[$_internalIndexCount]['meta']['innerCount'];

    $this->_internalBinds[$this->_internalCount][$innerCount] = new stdClass();

		switch($type) {
			case'ul':
        $list = $this->getListObject('ul', $values, $innerParams, $outerParams);
				$this->_internalBinds[$this->_internalCount][$innerCount]->value = $list->getListHTML();
				break;
			case'table'://operates now
        $table = $this->getTableObject($values, $innerParams, $outerParams);
				$this->_internalBinds[$this->_internalCount][$innerCount]->value = $table->getTableHTML();
				break;
			case'ol':
			default:
        $list = $this->getListObject('ol',$values, $innerParams, $outerParams);
				$this->_internalBinds[$this->_internalCount][$innerCount]->value = $list->getListHTML();
				break;
		}

		$this->_internalBinds[$this->_internalCount][$innerCount]->looseIt = $looseIt;
		$this->_internalBinds[$_internalIndexCount]['meta']['innerCount']++;

		if($innerCount == 0) {
      $this->_internalIndex[$tag] = $this->_internalCount;
      $this->_internalCount++;
		}

    return true;
	}

	/**
	 * Parse an ini file and sets the template configuration
	 *
	 * @param string $path
	 * @param string $section
	 * @return bool
	 */
	public final function setConfig($path, $section = null) {
    if(!is_string($path) || (isset($section) && !is_string($section))) return false;

    if(file_exists($path)) {
      $config = parse_ini_file($path,$section);

      if(isset($section) && !empty($section)) $conf_array = $config[$section];
      else $conf_array = $config;

      while(list($key,$value)=each($conf_array))
        switch($key):
        case 'cache':
          if($value == true) $this->setCaching(true);
          else $this->setCaching(false);
          break;
        case 'cachepath':
          if(is_string($value)) $this->setCachePath($value);
          break;
        case 'cachetime':
          $this->setCacheTime(intval($value));
          break;
        case 'templatepath':
          if(is_string($value)) $this->setTemplatePath($value);
          break;
        case 'exit':
          if($value == true) $this->setExit(true);
          else $this->setExit(false);
          break;
        case 'parse':
          if($value === true) $this->setParseFlag(true);
          else $this->setParseFlag(false);
          break;
        endswitch;

      return true;
    }

    return false;
	}

  /**
   * Enter description here...
   *
   * @param array $tags
   * @return bool
   */
  public final function setConfigTags($tags) {
    if(0 == count($tags) || !is_array($tags)) return false;

    foreach($tags as $tag => $value)
      $this->bindTo('<conf:' . $tag . '>', $value, false, false, false);

    return true;
  }

	/**
	 * Set values for parsing the template
	 *
	 * @param array $parseValues
	 * @return bool
	 */
	final public function setParseConfig($parseValues) {
    if(!is_array($parseValues)) return false;

    while(list(, $v) = each($parseValues))
      if(!in_array($v, $this->_parseValues))
        $this->_parseValues[] = '<' . $v . ':.*>';

    return true;
	}

    /**
     * Enter description here...
     *
     * @param array $tags
     * @param string $tagname
     * @return bool
     */
	final public function setCustomConfig($tags, $tagname) {
    if(count($tags) == 0 || !is_array($tags)) return false;

    foreach($tags as $tag => $value)
      $this->bindTo('<' . $tagname . ':' . $tag . '>', $value, false, false, false);

    return true;
	}

    /**
     * Fetches a section from a template and returns it.
     *
     * @param $path
     * @param $tag
     * @param bool $parse
     * @param bool $autoFetch
     * @return string
     */
  public final function getSection($path, $tag, $parse = false, $autoFetch = false) {
    // init variables
    $_internalBuffer = '';

		if(!file_exists($path) || !is_string($tag) || $tag === '') return $_internalBuffer;

		// get template as string
		$_internalBuffer = file_get_contents($path);
		// make end tag
		$tagEnd = str_replace('<', '<\/', $tag);
		// get section by tags
		preg_match('/' . $tag . '.*' . $tagEnd . '/Us', $_internalBuffer, $match);
		$_internalBuffer = $match[0];
		// fetch section
		if($autoFetch) $_internalBuffer = $this->_fetch($_internalBuffer, $parse);
		// return
		return $_internalBuffer;
	}

    /**
     * Fetches complete template without displaying on the screen
     *
     * @param $path
     * @param bool $templatePath
     * @param bool $parse
     * @param bool $autoFetch
     * @return string
     */
  public final function getTemplate($path, $templatePath = true, $parse = false, $autoFetch = true) {
    $_internalBuffer = '';

		if(!is_string($path) || $path === '') return $_internalBuffer;

		if($templatePath) $path = self::$_templatePath . $path;

    $_internalBuffer = file_get_contents($path);

		//TODO: have a look if path begins with http -> not allowed.
		if($autoFetch) $_internalBuffer = $this->_fetch($_internalBuffer, $parse);

		return $_internalBuffer;
	}

	/**
	 * Enter description here...
	 *
	 * @return string
	 */
  public function getTemplatePath() {
    return self::$_templatePath;
  }

	/**
	 * Enter description here...
	 *
	 * @return void
	 */
	public final function checkSiteCache() {
    if( ($found = $this->checkCache()) && is_string($found)) self::display(parent::$_cachePath . $found, null, null, true);
	}

	/**
	 * Fetches the given template and print it out.
	 *
	 * @param string $path
	 * @param int $docType[optional]
	 * @param bool $parse[optional]
	 * @param bool $cached[optional]
	 * @return bool|void
	 */
	public final function display($path, $docType = null, $parse = false, $cached = false) {
    if( self::$_caching === true && ($fetched = $this->checkCache()) ) {
      print file_get_contents($fetched);
      if($this->_exit) exit;
    }

    if(!$cached) {
      $path = self::$_templatePath.$path;
      $this->_setBasicTags();

      if(!empty($docType)) self::setDocType(intval($docType));

      // Print document type at stream beginning
      $fetched = $this->getDocType();
    }

    if (empty($path) || !file_exists($path)) return false;

		$fetched .= TemplateBase::_fetch(file_get_contents($path), $parse);
		$this->cacheDocument($fetched);

		print $fetched;

		if($this->_exit) exit;
	}

   /**
     * Enter description here...
     *
     * @return Void
     */
  private final function _setBasicTags() {
    $this->bindTo('<base:Charset>', $this->getCharset());
    $this->bindTo('<base:Fulldate>', $this->getFullDate());
    $this->bindTo('<base:Date>', $this->getDate());
    $this->bindTo('<base:Time>', $this->getTime());
  }
}
