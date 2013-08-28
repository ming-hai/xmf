<?php
/*
 * This file has its roots as part of the Mojavi package which was
 * Copyright (c) 2003 Sean Kerr. It has been incorporated into this
 * derivative work under the terms of the LGPL V2.1.
 * (license terms)
 */

namespace Xmf\Mvc;

use Xmf\Mvc;

/**
 * Renderer implements a renderer object using template files
 * consisting of PHP code.
 *
 * @category  Xmf\Mvc\Renderer
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    Sean Kerr <skerr@mojavi.org>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @copyright 2003 Sean Kerr
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Renderer extends ContextAware
{

    /**
     * An associative array of template attributes.
     *
     * @since  1.0
     * @type   array
     */
    protected $attributes;

    /**
     * An absolute file-system path where a template can be found.
     *
     * @since  1.0
     * @type   string
     */
    protected $dir;

    /**
     * The template engine instance.
     *
     * @since  1.0
     * @type   object
     */
    protected $engine;

    /**
     * The mode to be used for rendering, which is one of the following:
     *
     * - Xmf\Mvc::RENDER_CLIENT - render to client
     * - Xmf\Mvc::RENDER_VAR - render to variable
     *
     * @type   int
     */
    protected $mode;

    /**
     * The result of a render when render mode is Xmf\Mvc::RENDER_VAR.
     *
     * @type   string
     */
    protected $result;

    /**
     * A relative or absolute file-system path to a template.
     *
     * @since  1.0
     * @type   string
     */
    protected $template;

    /**
     * Create a new Renderer instance.
     *
     * @since  1.0
     */
    public function __construct ()
    {

        $this->attributes = array();
        $this->dir        = null;
        $this->engine     = null;
        $this->mode       = Mvc::RENDER_CLIENT;
        $this->result     = null;
        $this->template   = null;

    }

    /**
     * Clear the rendered result.
     *
     * _This is only useful when render mode is_ Xmf\Mvc::RENDER_VAR
     *
     * @return void
     * @since  1.0
     */
    public function clearResult ()
    {
        $this->result = null;
    }

    /**
     * Render the view.
     *
     *  _This method should never be called manually._
     *
     * @return void
     * @since  1.0
     */
    public function execute ()
    {
        $dir = null;

        if ($this->template == null) {
            $error = 'A template has not been specified';
            trigger_error($error, E_USER_ERROR);
            exit;
        }

        if ($this->isPathAbsolute($this->template)) {
            $dir            = dirname($this->template) . '/';
            $this->template = basename($this->template);
        } else {
            $dir = ($this->dir == null)
                   ? $this->Controller()->getUnitDir() . 'templates/'
                   : $this->dir;
            if (!is_readable($dir . $this->template)
                && is_readable(TEMPLATE_DIR . $this->template)
            ) {
                $dir = TEMPLATE_DIR;
            }
        }

        if (is_readable($dir . $this->template)) {
            // make it easier to access data directly in the template
            $mojavi   =& $this->Controller()->getMojavi();
            $template =& $this->attributes;

            if ($this->mode == Mvc::RENDER_VAR
                || $this->controller()->getRenderMode() == Mvc::RENDER_VAR
            ) {
                ob_start();
                require $dir . $this->template;
                $this->result = ob_get_contents();
                ob_end_clean();
            } else {
                require $dir . $this->template;
            }
        } else {
            $error = 'Template file ' . $dir . $this->template . ' does ' .
                     'not exist or is not readable';
            trigger_error($error, E_USER_ERROR);
            exit;
        }
    }

    /**
     * Retrieve the rendered result when render mode is Xmf\Mvc::RENDER_VAR.
     *
     * @return string A rendered view.
     *
     * @since  1.0
     */
    public function & fetchResult ()
    {
        if ($this->mode == Mvc::RENDER_VAR
            || $this->Controller()->getRenderMode() == Mvc::RENDER_VAR
        ) {
            if ($this->result == null) {
                $this->execute();
            }

            return $this->result;
        }
        $null = null;

        return $null;

    }

    /**
     * Retrieve an attribute.
     *
     * @param string $name An attribute name.
     *
     * @return mixed An attribute value, if attribute exists, otherwise NULL.
     *
     * @since  1.0
     */
    public function & getAttribute ($name)
    {

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];

        }
        $null=null;

        return $null;

    }

    /**
     * Retrieve the template engine instance.
     *
     * @return bool NULL because no template engine exists for PHP templates.
     *
     * @since  1.0
     */
    public function & getEngine ()
    {
        return $this->engine;
    }

    /**
     * Retrieve the render mode, which is one of the following:
     *
     * - Xmf\Mvc::RENDER_CLIENT - render to client
     * - Xmf\Mvc::RENDER_VAR    - render to variable
     *
     * @return int A render mode.
     */
    public function getMode ()
    {
        return $this->mode;
    }

    /**
     * Retrieve an absolute file-system path to the template directory.
     *
     * This will return NULL unless a directory has been specified setTemplateDir().
     *
     * @return string A template directory.
     *
     * @since  1.0
     */
    public function getTemplateDir ()
    {
        return $this->dir;
    }

    /**
     * Determine if a file-system path is absolute.
     *
     * @param string $path A file-system path.
     *
     * @return bool True if path is absolute, false otherwise
     * @since  1.0
     */
    public function isPathAbsolute ($path)
    {
        if (strlen($path) >= 2) {
            if ($path{0} == '/' || $path{0} == "\\" || $path{1} == ':') {
                return true;
            }
        }

        return false;
    }

    /**
     * Remove an attribute.
     *
     * @param string $name An attribute name.
     *
     * @return void
     * @since  1.0
     */
    public function & removeAttribute ($name)
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
    }

    /**
     * Set multiple attributes by using an associative array.
     *
     * @param array $array An associative array of attributes.
     *
     * @return void
     * @since  1.0
     */
    public function setArray ($array)
    {
        if (is_array($array)) {
            $this->attributes = array_merge($this->attributes, $array);
        }
    }

    /**
     * Set multiple attributes by using a reference to an associative array.
     *
     * @param array &$array An associative array of attributes.
     *
     * @return void
     * @since  1.0
     */
    public function setArrayByRef (&$array)
    {
        $keys  = array_keys($array);
        $count = sizeof($keys);

        for ($i = 0; $i < $count; $i++) {
            $this->attributes[$keys[$i]] =& $array[$keys[$i]];
        }
    }

    /**
     * Set an attribute.
     *
     * @param string $name  An attribute name.
     * @param mixed  $value An attribute value.
     *
     * @return void
     * @since  1.0
     */
    public function setAttribute ($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Set an element attribute array
     *
     * This allows an attribute which is an array to be built one
     * element at a time.
     *
     * @param string $stem  An attribute array name.
     * @param string $name  An attribute array item name. If empty, the
     *                      value will be appended to the end of the
     *                      array rather than added with the key $name.
     * @param mixed  $value An attribute array item value.
     *
     * @return void
     * @since  1.0
     */
    public function setAttributeArrayItem ($stem, $name, $value)
    {
        if (!isset($this->attributes[$stem])
            || !is_array($this->attributes[$stem])
        ) {
            $this->attributes[$stem]=array();
        }
        if (empty($name)) {
            $this->attributes[$stem][] = $value;
        } else {
            $this->attributes[$stem][$name] = $value;
        }
    }

    /**
     * Set an attribute by reference.
     *
     * @param string $name   An attribute name.
     * @param mixed  &$value An attribute value.
     *
     * @return void
     * @since  1.0
     */
    public function setAttributeByRef ($name, &$value)
    {
        $this->attributes[$name] =& $value;
    }

    /**
     * Set the render mode, which is one of the following:
     * - Xmf\Mvc::RENDER_CLIENT - render to client
     * - Xmf\Mvc::RENDER_VAR    - render to variable
     *
     * @param int $mode render mode.
     *
     * @return void
     * @since  1.0
     */
    public function setMode ($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Set the template.
     *
     * @param string $template A relative or absolute file-system path to a template.
     *
     * @return void
     * @since  1.0
     */
    public function setTemplate ($template)
    {
        $this->template = $template;
    }

    /**
     * Set the template directory.
     *
     * @param string $dir An absolute file-system path to the template directory.
     *
     * @return void
     * @since  1.0
     */
    public function setTemplateDir ($dir)
    {
        $this->dir = $dir;

        if (substr($dir, -1) != '/') {
            $this->dir .= '/';
        }
    }

    /**
     * Determine if a template exists.
     *
     * @param string $template A relative or absolute path to atemplate.
     * @param atring $dir      Absolute file-system path of template directory.
     *
     * @return bool TRUE if the template exists and is readable, otherwise FALSE.
     *
     * @since  1.0
     */
    public function templateExists ($template, $dir = null)
    {
        if ($this->isPathAbsolute($template)) {
            $dir      = dirname($template) . '/';
            $template = basename($template);
        } elseif ($dir == null) {
            $dir = $this->dir;

            if (substr($dir, -1) != '/') {
                $dir .= '/';
            }
        }

        return (is_readable($dir . $template));
    }
}
