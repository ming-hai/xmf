<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Xmf
 * @since           0.1
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: bootstrap.php 8065 2011-11-06 02:02:32Z beckmi $
 */


if (!defined('XOOPS_ROOT_PATH')) {
    require dirname(dirname(dirname(dirname(__FILE__)))) . '/mainfile.php';
}

// DO NOT depend on these in your - they will change or be removed
if (!defined('XMF_EXEC')) {
    define('XMF_EXEC', true);

    define('XMF_URL', XOOPS_URL . '/modules/xmf');
    define('XMF_CSS_URL', XMF_URL . '/css');
    // define('XMF_IMAGES_URL', XMF_URL . '/images');
    define('XMF_INCLUDE_URL', XMF_URL . '/include');
    define('XMF_LANGUAGE_URL', XMF_URL . '/language');
    define('XMF_LIBRARIES_URL', XMF_URL . '/libraries');
    define('XMF_TEMPLATES_URL', XMF_URL . '/templates');
    define('XMF_KRUMO_URL', XMF_URL . '/css/krumo/');

    define('XMF_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/xmf');
    define('XMF_CSS_PATH', XMF_ROOT_PATH . '/css');
    define('XMF_IMAGES_PATH', XMF_ROOT_PATH . '/images');
    define('XMF_INCLUDE_PATH', XMF_ROOT_PATH . '/include');
    define('XMF_LANGUAGE_PATH', XMF_ROOT_PATH . '/language');
    define('XMF_LIBRARIES_PATH', XMF_ROOT_PATH . '/libraries');
    define('XMF_TEMPLATES_PATH', XMF_ROOT_PATH . '/templates');

    define('XMF_NEWLINE', "\n");

    define('_GLOBAL_LEFT', 'left');
    define('_GLOBAL_RIGHT', 'right');
}

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

\Xmf\Language::load('global', 'xmf');
