<?php
// +-----------------------------------------------------------------------+
// | Copyright (c) 2002-2003, Richard Heyes                                |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Richard Heyes <http://www.phpguru.org>                        |
// +-----------------------------------------------------------------------+

require_once('library/Tree/Factory/Iterator.php');

/**
* Creates a tree structure from a list of items.
* Items must be separated using the supplied separator.
* Eg:    array('foo',
*              'foo/bar',
*              'foo/bar/jello',
*              'foo/bar/jello2',
*              'foo/bar2/jello')
*
* Would create a structure thus:
*   foo
*    +-bar
*    |  +-jello
*    |  +-jello2
*    +-bar2
*       +-jello
*/

class Tree_Factory_List implements Tree_Factory_Iterator
{
    /**
    * Structure of list
    * @var array
    */
    private $structure;


    /**
    * Used for valid() method
    * @var bool
    */
    private $valid;


    /**
    * Constructor
    * 
    * Creates a tree structure from a list of items.
    * Items must be separated using the supplied separator.
    * Eg:    array('foo',
    *              'foo/bar',
    *              'foo/bar/jello',
    *              'foo/bar/jello2',
    *              'foo/bar2/jello')
    * 
    * @param array $list Array of values
    */
    public function __construct($list, $sep = '/')
    {
        foreach ($list as $v) {
            $s = &$this->structure; // $s is a moving reference

            $v = explode($sep, $v);
            
            // Loop thru each path segment
            foreach ($v as $j) {
                if (!isset($s[$j])) {
                    $s[$j] = array();
                }
                
                $s = &$s[$j];
            }
        }
    }


    /**
    * Returns tag data for current node
    */
    public function getTag()
    {
        return key($this->structure);
    }


    /**
    * Iterator::current()
    */
    public function current()
    {
        return $this;
    }


    /**
    * Iterator::key()
    */
    public function key()
    {
        // Not much to do here
    }


    /**
    * Iterator::next()
    */
    public function next()
    {
        $this->valid = (next($this->structure) !== false);
    }


    /**
    * Iterator::rewind()
    */
    public function rewind()
    {
        $this->valid = (reset($this->structure) !== false);
    }


    /**
    * Iterator::valid()
    */
    public function valid()
    {
        return $this->valid;
    }


    /**
    * Iterator::hasChildren()
    */
    public function hasChildren()
    {
        return !empty($this->structure[key($this->structure)]);
    }


    /**
    * Iterator::getChildren()
    */
    public function getChildren()
    {
        $newStructure = $this->structure[key($this->structure)];

        $clone = clone $this;
        $clone->structure = $newStructure;

        return $clone;
    }
}
?>