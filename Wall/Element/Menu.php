<?php
/*
 * Copyright (c) 2004-2005, Kaspars Foigts
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *
 *    * Redistributions in binary form must reproduce the above
 *      copyright notice, this list of conditions and the following
 *      disclaimer in the documentation and/or other materials provided
 *      with the distribution.
 *
 *    * Neither the name of the WALL4PHP nor the names of its
 *      contributors may be used to endorse or promote products derived
 *      from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 * Authors: Kaspars Foigts (wall4php@laacz.lv)
 *
*/

require_once('Wall/Element.php');

class WallElementMenu extends WallElement {
    
    var $tag = 'menu';
    
    var $autonumber = false;
    var $colorize = false;
    
    var $table_ok = false;
    var $css_ok = false;
    var $table_and_css_background = false;
    
    var $wml_menu_with_select = false;
    
    var $counter = 0;

    function WallElementMenu (&$wall, $attributes = Array()) {
        $this->WallElement($wall, $attributes);
    }
    
    function doStartTag() {
        parent::doStartTag();

        if ($this->getAncestorByClassName('wallelementblock')) {
            trigger_error("'menu' tag cannot be nested inside a 'block' tag (breaks XHTML validity and will produce an error on some browsers).\n Close or remove the containing 'block' tag.", E_USER_ERROR);
        }
        
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            
            $this->table_ok = $this->_wall->getCapa('xhtml_supports_table_for_layout');
            $this->css_ok = $this->_wall->getCapa('xhtml_supports_css_cell_table_coloring');
            $this->table_and_css_background = $this->table_ok && $this->css_ok;
            
            if ($this->_wall->menu_css_tag && $this->colorize && $this->table_and_css_background) {
                $this->write('<table>');
            } else if ($this->autonumber) {
                $this->write('<ol>');
            } else {
                $this->write('<ul>');
            }

        } else if (strpos($this->preferred_markup, 'chtml') !== false) {
            $this->write('<br clear="all">');
        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            $this->wml_menu_with_select = $this->_wall->getCapa('menu_with_select_element_recommended');
            if ($this->wml_menu_with_select) {
                $this->write('<p align="left" mode="nowrap">');
                $this->write('<select>');
            } else {
                $this->write('<p>');
            }
        }
        
    }
    
    function doEndTag() {
        parent::doEndTag();
        //$this->write($this->preferred_markup);
        if (strpos($this->preferred_markup, 'xhtmlmp') !== false) {
            if ($this->_wall->menu_css_tag && $this->table_and_css_background && $this->colorize) {
                $this->write('</table>');
            } else if ($this->autonumber) {
                $this->write('</ol>');
            } else {
                $this->write('</ul>');
            }
        } else if (strpos($this->preferred_markup, 'chtml') !== false) {

        } else if (strpos($this->preferred_markup, 'wml') !== false) {
            if ($this->wml_menu_with_select) {
                $this->write('</select>');
                $this->write('</p>');
            } else {
                $this->write('</p>');
            }
        }
    }
}

?>