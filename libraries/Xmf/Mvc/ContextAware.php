<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Mvc;

/**
 * ContextAware makes shared context available
 *
 * Shared context makes the following available:
 * Controller() -
 *
 * @category  Xmf\Mvc\ContextAware
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
abstract class ContextAware
{

    /**
     * Instance of the full context. At present this is the controller
     *
     * @return object shared context
     *
     * @since      1.0
     */
    protected function & Context()
    {
        return Context::get();
    }

    /**
     * Get the controller context
     *
     * @return object Xmf\Mvc\Controller instance
     * @since      1.0
     */
    public function & Controller()
    {
        return $this->Context();
    }

    /**
     * Get the request context
     *
     * @return object Xmf\Mvc\Request instance
     * @since      1.0
     */
    public function & Request()
    {
        return $this->Context()->getRequest();
    }

    /**
     * Get the user context
     *
     * @return object Xmf\Mvc\User instance
     * @since      1.0
     */
    public function & User()
    {
        return $this->Context()-> getUser();
    }

    /**
     * Get the ModelManager instance
     *
     * @return object Xmf\Mvc\ModelManager instance
     * @since      1.0
     */
    public function & Models()
    {
        return $this->Context()->getModels();
    }
}
