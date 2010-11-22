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

/**
* A class to represent a collection of child nodes
*
* Public methods for Tree_NodeCollection class:
*   &add(Tree_Node node)                               Adds a node to the collection
*   &firstNode()                                       Retreives a reference to the first node in the collection
*   &lastNode()                                        Retreives a reference to the last node in the collection
*   &removeNodeAt(int index)                           Removes the node at the specified index (nodes are re-ordered)
*   removeNode(Tree_Node node [, boolean search])      Removes the given node (nodes are re-ordered)
*   indexOf(Tree_Node node)                            Retreives the index of the given node
*   getNodeCount([boolean recurse])                    Retreives the number of nodes in the collection, optionally recursing
*   getFlatList()                                      Retrieves an indexed array of the nodes from top to bottom, left to right
*   traverse(callback function)                        Traverses the tree supply each node to the callback function
*   search(mixed searchData [, bool strict])           Basic search function for searching the Trees' "tag" data
*   moveTo()                                           Moves the nodes in this collection to the given node/tree
*   copyTo()                                           Copies the nodes in this collection to the given node/tree
*   usortNodes(callback comparer)                      Sorts the nodes in the collection based on a user defined comparison function.
*/
class Tree_NodeCollection implements ArrayAccess, IteratorAggregate
{
    /**
    * An array of child nodes
    * @var array
    */
    public $nodes;


    /**
    * The containing node/tree object
    * @var object
    */
    private $container;


    /**
    * Constructor
    */
    function __construct($container)
    {
        $this->nodes     = array();
        $this->container = $container;
    }
    
    /**
    * Sorts the nodes in the collection based on a user defined comparison function.
    * The function is deliberately not recursive so that you can sort 
    * any of the node collections within the tree in different ways. 
    * 
    * To sort the entire tree uniformly, call the Tree function of the same name.
    * eg:
    *      $myTree->usortNodes('cmp');
    * 
    *   where 'cmp' is the comparison function.
    * 
    * Not part of the original package, added by Bruce Anwyl on Jan 04, 2008.
    * 
    * @param  callback $comparer  Name of user defined comparison function
    * @return bool                Result of usort 
    */
    public function usortNodes($comparer)
    {
        return usort($this->nodes, $comparer);
    }
    
    /**
    * Implementation of IteratorAggregate::getIterator()
    * 
    * @return object Iterator object for looping over this collections
    *                immediate nodes.
    */
    public function getIterator()
    {
        return new ArrayIterator($this->nodes);
    }


    /**
    * Implementation of ArrayAccess:offsetSet()
    * 
    * @param mixed $key   Key to set value for
    * @param mixed $value Value to set
    */
    public function offsetSet($key, $value)
    {
        $this->nodes[$key] = $value;
    }
    
    
    /**
    * Implementation of ArrayAccess:offsetGet()
    * 
    * @param  mixed $key Key to retrieve value of
    * @return mixed      Value of given key
    */
    public function offsetGet($key)
    {
        return $this->nodes[$key];
    }
    
    
    /**
    * Implementation of ArrayAccess:offsetUnset()
    * 
    * @param mixed $key Key to unset
    */
    public function offsetUnset($key)
    {
        unset($this->nodes[$key]);
    }
    
    
    /**
    * Implementation of ArrayAccess:offsetExists()
    * 
    * @param  mixed $key Key to check for
    * @return bool       Whether it's set or not
    */
    public function offsetExists($key)
    {
        return isset($this->nodes[$key]);
    }


    /**
    * Adds a node to this node
    *
    * @param  object $node The Tree_Node object
    * @return object       A reference to the new node inside the tree
    */
    public function add(Tree_Node $node)
    {
        $node->setParent($this->container);

        // Set the Tree for the node
        if ($this->container->getTree() instanceof Tree) {
            $node->setTree($this->container->getTree());
        }

        $this->nodes[] = $node;

        return $node;
    }


    /**
    * Returns the first node in this particular collection
    *
    * @return object The first node. NULL if no nodes.
    */
    public function firstNode()
    {
        return !empty($this->nodes) ? $this->nodes[0] : null;
    }


    /**
    * Returns the last node in this particular collection
    *
    * @return object The last node. NULL if no nodes.
    */
    public function lastNode()
    {
        return !empty($this->nodes) ? $this->nodes[count($this->nodes) - 1] : null;
    }


    /**
    * Removes a node from the child nodes array at the
    * specified (zero based) index.
    *
    * @param  integer $index The index to remove
    * @return object         The node that was removed, or null
    *                        if this index did not exist
    */
    public function removeNodeAt($index)
    {
        $node = null;
        if (!empty($this->nodes[$index])) {
            $node = $this->nodes[$index]->remove();
            $this->nodes = array_values($this->nodes);
        }

        return $node;
    }


    /**
    * Removes a node from the child nodes array by using
    * reference comparison.
    *
    * @param  Tree_Node $node   The node to remove
    * @param  bool      $search Whether to search child nodes
    * @return bool              True/False
    */
    public function remove(Tree_Node $node, $search = false)
    {
        foreach ($this->nodes as $index => $_node) {
            if ($_node === $node) {

                // Unset parent, tree
                $node->setParent(null);
                $node->setTree(null);

                unset($this->nodes[$index]);

                $this->nodes = array_values($this->nodes);
                return true;

            } else if ($search AND $_node->hasChildren() ) {
                $searchNodes[] = $_node;
            }
        }

        // Go thru searching those nodes that have children
        if (!empty($searchNodes)) {
            foreach ($searchNodes as $_node) {
                if ($_node->nodes->remove($node, true)) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
    * Returns the index in the nodes array at which
    * the given node resides. Used in the prev/next Sibling
    * methods.
    *
    * @param  object $node The node to return the index of
    * @return integer      The index of the node or null if
    *                      not found.
    */
    public function indexOf($node)
    {
        foreach ($this->nodes as $index => $_node) {
            if ($node === $_node) {
                return $index;
            }
        }
        
        return null;
    }


    /**
    * Returns node at given index. Note that this class also implements the
    * ArrayAccess interface so you could do this:
    *  $tree->nodes[3] instead of $tree->nodes->indexOf(3)
    *
    * @param  integer $index    Index of node to retrieve
    * @return Tree_Node         Tree_Node object or null if it doesn't exist
    */
    function nodeAt($index)
    {
        return isset($this->nodes[$index]) ? $this->nodes[$index] : null;
    }


    /**
    * Returns the number of child nodes in this node/tree.
    * Optionally searches the tree and returns the cumulative count.
    * Works also as an implementation of the Countable::count() interface (PHP5.1+)
    * 
    *
    * @param  bool    $search Search tree for nodecount too
    * @return integer         The number of nodes found
    */
    public function count($search = false)
    {
        if ($search) {
            $count = count($this->nodes);
            
            foreach ($this->nodes as $node) {
                $count += $node->nodes->count(true);
            }

            return $count;

        } else {
            return count($this->nodes);
        }
    }


    /**
    * Returns a flat list of the node collection. This array contains references
    * to the nodes.
    *
    * @return array Flat list of the nodes from top to bottom, left to right.
    */
    public function getFlatList()
    {
        $return = array();

        foreach ($this->nodes as $node) {
            $return[] = $node;
            
            // Recurse
            if ($node->hasChildren()) {
                $return = array_merge($return, $node->nodes->getFlatList());
            }
        }

        return $return;
    }


    /**
    * Traverses the node collection applying a function to each and every node.
    * The function name given (though this can be anything you can supply to
    * call_user_func(), not just a name) should take two arguments which are the
    * node object (Tree_Node class) and any extra data you pass via the $data argument
    * to traverse(). You can then access the nodes data by using
    * the getTag() method. The traversal goes from top to bottom, left to right
    * (ie same order as what you get from getFlatList()).
    *
    * ** The node is passed by reference to the function! **
    *
    * @param callback $function The callback function to use
    * @param array    $data     Any data to pass on to the callback function.
    *                           Probably most useful as an array of "stuff".
    */
    public function traverse($function, $data = null)
    {
        foreach ($this->nodes as $node) {

            call_user_func($function, $node, $data);

            // Recurse
            if ($node->hasChildren()) {
                $node->nodes->traverse($function, $data);
            }
        }
    }


    /**
    * Searches the node collection for a node with a tag matching
    * what you supply. This is a simple "tag == your data" comparison, (=== if strict option is applied)
    * and more advanced comparisons can be made using the traverse() method.
    * This function returns an array of matching Tree_Node objects.
    *
    * @param  mixed $data    Data to try to find and match
    * @param  bool  $strict  Whether to use === or simply == to compare
    * @return array          Array of resulting nodes matched.
    */
    public function search($data, $strict = false)
    {
        $results  = array();
        $nodeList = $this->getFlatList();

        foreach ($nodeList as $node) {
            $comparison = $strict ? ($node->getTag() === $data) : ($node->getTag() == $data);
            
            if ($comparison) {
                $results[] = $node;
            }
        }

        return $results;
    }


    /**
    * Moves the nodes in this collection (not the collection itself)
    * to the given new parent.
    * 
    * @param object $newParent The new parent Tree_Node or Tree object
    */
    public function moveTo($newParent)
    {
        foreach ($this->nodes as $node) {
            $node->moveTo($newParent);
        }
    }


    /**
    * Copies the nodes in this collection (not the collection itself)
    * to the given new parent.
    *
    * @param object $newParent The new parent Tree_Node or Tree object
    */
    public function copyTo($newParent)
    {
        foreach ($this->nodes as $node) {
            $node->copyTo($newParent);
        }
    }


    /**
    * Sets the master Tree object for Nodes in this
    * collection.
    *
    * @param Tree $tree The Tree object to set
    */
    public function setTree($tree)
    {
        foreach ($this->nodes as $node) {
            $node->setTree($tree);
        }
    }


    /**
    * Returns the Tree object
    *
    * @return Tree The Tree object containing this NodeCollection
    */
    function getTree()
    {
        return $this->container->getTree();
    }
}
?>