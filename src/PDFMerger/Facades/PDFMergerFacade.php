<?php
/*
* File:     PDFMergerFacade.php
* Category: Facade
* Author:   M. Goldenbaum
* Created:  01.12.16 21:06
* Updated:  -
*
* Description:
*  -
*/

namespace Webklex\PDFMerger\Facades;

use \Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Translation\Translator
 */
class PDFMergerFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'PDFMerger';
    }
}
