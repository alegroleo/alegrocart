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


require_once('Tree/Node.php');
require_once('Tree/NodeCollection.php');


/**
* An OO tree class based on various things, including the MS treeview control
* If you use this class and wish to show your appreciation then visit my
* wishlist here:   http://www.amazon.co.uk/exec/obidos/wishlist/S8H2UOGMPZK6
*
* Structure of one of these trees:
*
*  Tree Object
*    |
*    +- Tree_NodeCollection object (nodes property)
*          |
*          +- Array of Tree_Node objects (nodes property)
*
* Usage:
*   $tree = new Tree();
*   $node  = $tree->nodes->add(new Tree_Node('1'));
*   $node2 = $tree->nodes->add(new Tree_Node('2'));
*   $node3 = $tree->nodes->add(new Tree_Node('3'));
*   $node4 = $node3->nodes->add(new Tree_Node('3_1'));
*   $tree->nodes->removeNodeAt(0);
*   print_r($tree);
*
* The data for a node is supplied by giving it as the argument to the Tree_Node
* constructor. You can retreive the data by using a nodes getTag() method, and alter
* it using the setTag() method.
*
* Public methods for Tree class:
*   hasChildren()                                            Returns whether this tree has child nodes or not
*   &merge(object &$tree [, ...])                            Merges two or more Tree/Tree_Node objects. Can be used statically or not.
*/

class Tree
{
    /**
    * Child nodes
    * @var object
    */
    public $nodes;


    /**
    * Constructor
    */
    function __construct()
    {
        $this->nodes = new Tree_NodeCollection($this);
    }


    /**
    * Creates a Tree structure using a Reader driver.
    *
    * @param  RecursiveIterator $it   Iterator to create tree from
    * @param  mixed             $node Internal use only
    * @return Tree                    Tree object
    */
    public static function factory(Tree_Factory_Iterator $it, $node = null)
    {
        if (is_null($node)) {
            $node = new Tree();
        }

        foreach ($it as $v) {
            $newNode = $node->nodes->add(new Tree_Node($v->getTag()));

            if ($v->hasChildren()) {
                Tree::factory($v->getChildren(), $newNode);
            }
        }
        
        return $node;
    }


    /**
    * Merges two or more tree structures into one. Can take either
    * Tree objects or Tree_Node objects as arguments to merge. This merge
    * simply means the nodes from the second+ argument(s) are added to
    * this object.
    *
    * @param ...  Any number of Tree or Tree_Node objects to be merged
    *             with the first argument.
    * @return int Number of nodes merged
    */
    public function merge()
    {
        $args = func_get_args();
        $num  = 0;

        if (!empty($args)) {
            foreach ($args as $obj) { // Loop thru all given args
                if ($obj instanceof Tree_Node) {
                    $this->nodes->add($obj);
                } else {
                    foreach ($obj->nodes as $node) { // Loop thru all nodes (via Iterator, as nodes is a Tree_NodeCollection object)
                        $this->nodes->add($node);
                        $num++;
                    }
                }
            }
        }
        
        return $num;
    }


    /**
    * Returns true/false as to whether this node
    * has any child nodes or not.
    *
    * @return bool Any child nodes or not
    */
    public function hasChildren()
    {
        return $this->nodes->count() > 0;
    }


    /**
    * Returns itself, always
    *
    * @return Tree The Tree object ($this)
    */
    public function getTree()
    {
        return $this;
    }


    /**
    * __toString() method
    * 
    * Dumps a HTML friendly representation of the Trees'
    * tag data.
    */
    public function __toString()
    {
        $nodeList = $this->nodes->getFlatList();
        $str      = '';

        foreach ($nodeList as $node) {
            $str .= str_repeat('&nbsp;&nbsp;&nbsp;', $node->depth()) . $node->getTag() . "\n<br>";
        }

        return $str;
    }
    
    /**
    * Sorts the child nodes of every node in the tree in the sort order defined
    * in a user defined comparison function.
    * 
    * Not part of the original package, added by Bruce Anwyl on Jan 04, 2008.
    * 
    * @param  callback $comparer  Name of user defined comparison function
    */
    public function usortNodes($comparer)
    {
        //  Sort the top level first
        $this->nodes->usortNodes($comparer);
        //  then sort all the other levels.
        $allNodes = $this->nodes->getFlatList();
        foreach ($allNodes as $node)
        {
            if ( $node->hasChildren()) $node->nodes->usortNodes($comparer);
        }
    }  
}
?>