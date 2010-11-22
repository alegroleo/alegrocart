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
* A node class to complement the Tree class
*
* Public variables for Tree_Node class:
*   $nodes
*
* Public methods for Tree_Node class:
*   setTag(mixed tag)                                 Sets the tag data
*   getTag()                                          Retreives the tag data
*   &prevSibling()                                    Retreives a reference to the previous sibling node
*   &nextSibling()                                    Retreives a reference to the next sibling node
*   remove()                                          Removes this node from the collection
*   &getTree()                                        Returns the encompassing Tree object
*   &getParent()                                      Returns the parent Tree_Node object if any
*   hasChildren()                                     Returns whether this node has child nodes or not
*   depth()                                           Returns the depth of this node in the tree (zero based)
*   isChildOf()                                       Returns whether this node is a direct child of the given node/tree
*   moveTo()                                          Moves this node to be a child of the given node/tree
*   copyTo()                                          Copies this node to a new child of the given node/tree
*/
class Tree_Node
{
    /**
    * The data that this node holds
    * @var mixed
    */
    private $tag;


    /**
    * Parent node
    * @var mixed
    */
    private $parent;


    /**
    * The master Tree object
    * @var Tree
    */
    private $tree;


    /**
    * The nodes collection
    * @var NodeCollection
    */
    public $nodes;


    /**
    * Constructor
    *
    * @param mixed $tag The data that this node represents
    */
    function __construct($tag = null)
    {
        $this->tag    = $tag;
        $this->parent = null;
        $this->nodes  = new Tree_NodeCollection($this);
    }


    /**
    * Returns the previous child node in the parents node array,
    * or null if this node is the first.
    *
    * @return object A reference to the previous node in the parent
    *                node collection or null if this is the first.
    */
    public function prevSibling()
    {
        if ($this->parent) {
            $myIndex = $this->parent->nodes->indexOf($this);
    
            if ($myIndex > 0) {
                return $this->parent->nodes->nodeAt($myIndex - 1);
            }
        }
        
        return null;
    }


    /**
    * Returns the next child node in the parents node array,
    * or null if this node is the last.
    *
    * @return object A reference to the next node in the parent
    *                node collection or null if this is the last.
    */
    public function nextSibling()
    {
        if ($this->parent) {
            $myIndex = $this->parent->nodes->indexOf($this);

            if ($myIndex < ($this->parent->nodes->getNodeCount() - 1)) {
                return $this->parent->nodes->nodeAt($myIndex + 1);
            }
        }

        return null;
    }


    /**
    * Removes this node from its' parent. If this
    * node has no parent (ie its not been added to
    * a Tree or Tree_Node object) then this method
    * will do nothing.
    *
    * @return Tree_Node This Tree_Node object (always)
    */
    public function remove()
    {
        if ($this->parent) {
            $this->parent->nodes->remove($this);
        }

        return $this;
    }


    /**
    * Sets the tag data
    *
    * @param mixed $tag The data to set the tag to
    */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }


    /**
    * Returns the tag data
    *
    * @return mixed The tag data
    */
    public function getTag()
    {
        return $this->tag;
    }


    /**
    * Sets the master Tree object for this
    * node.
    *
    * @param object $tree The Tree object reference
    */
    public function setTree($tree)
    {
        $this->tree = $tree;

        // Set tree for child nodes in this nodes NodeCollection
        $this->nodes->setTree($tree);
    }


    /**
    * Returns the tree object which this node is attached
    * to (if any).
    *
    * @return object The encompassing Tree object
    */
    public function getTree()
    {
        return $this->tree;
    }


    /**
    * Sets the parent node of the node.
    *
    * @param object $node The parent Tree or Tree_Node
    */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }


    /**
    * Returns the parent node if any
    *
    * @return object The parent Tree or Tree_Node object
    */
    public function getParent()
    {
        return $this->parent;
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
    * Returns the depth in the tree of this node
    * This is a zero based indicator, so top level nodes
    * will have a depth of 0 (zero).
    *
    * @return integer The depth of the node
    */
    public function depth()
    {
        $depth = 0;
        $currLevel = $this;

        while ($currLevel->parent instanceof Tree_Node) {
            ++$depth;
            $currLevel = $currLevel->parent;
        }
        
        return $depth;
    }


    /**
    * Returns true/false as to whether this node is a child
    * of the given node.
    *
    * @param  object $parent The suspected parent Tree or Tree_Node object
    * @return bool           Whether this node is a child of the suspected parent
    */
    public function isChildOf($parent)
    {
        return $this->parent === $parent;
    }


    /**
    * Moves this node to a new parent. All child nodes will
    * be retained.
    *
    * @param object $newParent The new parent Tree_Node or Tree object
    */
    public function moveTo($newParent)
    {
        $this->remove();
        $newParent->nodes->add($this);
    }


    /**
    * Copies this node to a new parent. This copies the node
    * to the new parent node/tree and all its child nodes (ie
    * a deep copy). Technically, new nodes are created with copies
    * of the tag data, since this is for all intents and purposes
    * the only thing that needs copying.
    *
    * @param  object $newParent The new parent Tree_Node or Tree object
    * @return object            The new node
    */
    public function copyTo($newParent)
    {
        $newNode = $newParent->nodes->add(new Tree_Node($this->getTag()));

        // Copy child nodes
        $this->nodes->copyTo($newNode);

        return $newNode;
    }
}
?>