<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Items;
use Hocmai\Exceptions\HocmaiSDKException;
use Hocmai\HocmaiResponse;

/**
 *
 * @author Thai Bui - Created At: 10/16/2018 - 1:28 PM
 * @version 1.0.0
 *
 */
class ItemFactory
{
    /**
     * @const string The base graph object class.
     */
    const BASE_ITEM_CLASS = '\Hocmai\Items\ItemBase';

    /**
     * @const string The base graph edge class.
     */
//    const BASE_GRAPH_EDGE_CLASS = '\Facebook\GraphNodes\GraphEdge';

    /**
     * @const string The graph object prefix.
     */
    const BASE_ITEM_PREFIX = '\Hocmai\Items\\';

    /**
     * @var HocmaiResponse The response entity from Graph.
     */
    protected $response;

    /**
     * @var array The decoded body of the FacebookResponse entity from Graph.
     */
    protected $decodedBody;

    /**
     * Init this Graph object.
     *
     * @param HocmaiResponse $response The response entity from Graph.
     */
    public function __construct(HocmaiResponse $response)
    {
        $this->response = $response;
        $this->decodedBody = $response->getDecodedBody();
    }

    /**
     * Tries to convert a FacebookResponse entity into a GraphNode.
     *
     * @param string|null $subclassName The GraphNode sub class to cast to.
     *
     * @return ItemBase
     *
     * @throws HocmaiSDKException
     */
    /*public function makeGraphNode($subclassName = null)
    {
        $this->validateResponseAsArray();
        $this->validateResponseCastableAsGraphNode();

        return $this->castAsGraphNodeOrGraphEdge($this->decodedBody, $subclassName);
    }*/

    public function makeItem($subclassName = null)
    {
        $this->validateResponseAsArray();
//        $this->validateResponseCastableAsItem();

        return $this->castAsItem($this->decodedBody, $subclassName);
    }

    public function makeProductLine()
    {
        return $this->makeItem(static::BASE_ITEM_PREFIX . 'Common\ProductLine');
    }

    public function makeReport()
    {
        return $this->makeItem(static::BASE_ITEM_PREFIX . 'Reports\Table');
    }

    /**
     * Convenience method for creating a GraphAchievement collection.
     *
     * @return GraphAchievement
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphAchievement()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphAchievement');
    }*/

    /**
     * Convenience method for creating a GraphAlbum collection.
     *
     * @return GraphAlbum
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphAlbum()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphAlbum');
    }*/

    /**
     * Convenience method for creating a GraphPage collection.
     *
     * @return GraphPage
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphPage()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphPage');
    }*/

    /**
     * Convenience method for creating a GraphSessionInfo collection.
     *
     * @return GraphSessionInfo
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphSessionInfo()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphSessionInfo');
    }*/

    /**
     * Convenience method for creating a GraphUser collection.
     *
     * @return GraphUser
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphUser()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphUser');
    }*/

    /**
     * Convenience method for creating a GraphEvent collection.
     *
     * @return GraphEvent
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphEvent()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphEvent');
    }*/

    /**
     * Convenience method for creating a GraphGroup collection.
     *
     * @return GraphGroup
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphGroup()
    {
        return $this->makeGraphNode(static::BASE_GRAPH_OBJECT_PREFIX . 'GraphGroup');
    }*/

    /**
     * Tries to convert a FacebookResponse entity into a GraphEdge.
     *
     * @param string|null $subclassName The GraphNode sub class to cast the list items to.
     * @param boolean     $auto_prefix  Toggle to auto-prefix the subclass name.
     *
     * @return GraphEdge
     *
     * @throws FacebookSDKException
     */
    /*public function makeGraphEdge($subclassName = null, $auto_prefix = true)
    {
        $this->validateResponseAsArray();
        $this->validateResponseCastableAsGraphEdge();

        if ($subclassName && $auto_prefix) {
            $subclassName = static::BASE_GRAPH_OBJECT_PREFIX . $subclassName;
        }

        return $this->castAsGraphNodeOrGraphEdge($this->decodedBody, $subclassName);
    }*/

    /**
     * Validates the decoded body.
     *
     * @throws HocmaiSDKException
     */
    public function validateResponseAsArray()
    {
        if (!is_array($this->decodedBody)) {
            throw new HocmaiSDKException('Unable to get response from Graph as array.', 620);
        }
    }

    /**
     * Validates that the return data can be cast as a GraphNode.
     *
     * @throws HocmaiSDKException
     */
    /*public function validateResponseCastableAsGraphNode()
    {
        if (isset($this->decodedBody['data']) && static::isCastableAsGraphEdge($this->decodedBody['data'])) {
            throw new HocmaiSDKException(
                'Unable to convert response from Graph to a GraphNode because
                the response looks like a GraphEdge. Try using GraphNodeFactory::makeGraphEdge() instead.',
                620
            );
        }
    }*/

    /*public function validateResponseCastableAsItem()
    {
//        if (isset($this->decodedBody['data']) && static::is)
    }*/

    /**
     * Validates that the return data can be cast as a GraphEdge.
     *
     * @throws HocmaiSDKException
     */
    /*public function validateResponseCastableAsGraphEdge()
    {
        if (!(isset($this->decodedBody['data']) && static::isCastableAsGraphEdge($this->decodedBody['data']))) {
            throw new HocmaiSDKException(
                'Unable to convert response from Graph to a GraphEdge because
                the response does not look like a GraphEdge. Try using GraphNodeFactory::makeGraphNode() instead.',
                620
            );
        }
    }*/

    /**
     * Safely instantiates a GraphNode of $subclassName.
     *
     * @param array       $data         The array of data to iterate over.
     * @param string|null $subclassName The subclass to cast this collection to.
     *
     * @return GraphNode
     *
     * @throws FacebookSDKException
     */
    /*public function safelyMakeGraphNode(array $data, $subclassName = null)
    {
        $subclassName = $subclassName ?: static::BASE_GRAPH_NODE_CLASS;
        static::validateSubclass($subclassName);

        // Remember the parent node ID
        $parentNodeId = isset($data['id']) ? $data['id'] : null;

        $items = [];

        foreach ($data as $k => $v) {
            // Array means could be recurable
            if (is_array($v)) {
                // Detect any smart-casting from the $graphObjectMap array.
                // This is always empty on the GraphNode collection, but subclasses can define
                // their own array of smart-casting types.
                $graphObjectMap = $subclassName::getObjectMap();
                $objectSubClass = isset($graphObjectMap[$k])
                    ? $graphObjectMap[$k]
                    : null;

                // Could be a GraphEdge or GraphNode
                $items[$k] = $this->castAsGraphNodeOrGraphEdge($v, $objectSubClass, $k, $parentNodeId);
            } else {
                $items[$k] = $v;
            }
        }

        return new $subclassName($items);
    }*/

    public function safelyMakeItem(array $data, $subclassName = null)
    {
        $subclassName = $subclassName ?: static::BASE_ITEM_CLASS;
        static::validateSubclass($subclassName);

        return new $subclassName($data);
    }

    /**
     * Takes an array of values and determines how to cast each node.
     *
     * @param array       $data         The array of data to iterate over.
     * @param string|null $subclassName The subclass to cast this collection to.
     * @param string|null $parentKey    The key of this data (Graph edge).
     * @param string|null $parentNodeId The parent Graph node ID.
     *
     * @return GraphNode|GraphEdge
     *
     * @throws FacebookSDKException
     */
    /*public function castAsGraphNodeOrGraphEdge(array $data, $subclassName = null, $parentKey = null, $parentNodeId = null)
    {
        if (isset($data['data'])) {
            // Create GraphEdge
            if (static::isCastableAsGraphEdge($data['data'])) {
                return $this->safelyMakeGraphEdge($data, $subclassName, $parentKey, $parentNodeId);
            }
            // Sometimes Graph is a weirdo and returns a GraphNode under the "data" key
            $data = $data['data'];
        }

        // Create GraphNode
        return $this->safelyMakeGraphNode($data, $subclassName);
    }*/

    public function castAsItem(array $data, $subclassName = null)
    {
        if (isset($data['data'])) {
            $data = $data['data'];
        }
        return $this->safelyMakeItem($data, $subclassName);

    }

    /**
     * Return an array of GraphNode's.
     *
     * @param array       $data         The array of data to iterate over.
     * @param string|null $subclassName The GraphNode subclass to cast each item in the list to.
     * @param string|null $parentKey    The key of this data (Graph edge).
     * @param string|null $parentNodeId The parent Graph node ID.
     *
     * @return GraphEdge
     *
     * @throws FacebookSDKException
     */
    /*public function safelyMakeGraphEdge(array $data, $subclassName = null, $parentKey = null, $parentNodeId = null)
    {
        if (!isset($data['data'])) {
            throw new FacebookSDKException('Cannot cast data to GraphEdge. Expected a "data" key.', 620);
        }

        $dataList = [];
        foreach ($data['data'] as $graphNode) {
            $dataList[] = $this->safelyMakeGraphNode($graphNode, $subclassName);
        }

        $metaData = $this->getMetaData($data);

        // We'll need to make an edge endpoint for this in case it's a GraphEdge (for cursor pagination)
        $parentGraphEdgeEndpoint = $parentNodeId && $parentKey ? '/' . $parentNodeId . '/' . $parentKey : null;
        $className = static::BASE_GRAPH_EDGE_CLASS;

        return new $className($this->response->getRequest(), $dataList, $metaData, $parentGraphEdgeEndpoint, $subclassName);
    }*/

    /**
     * Get the meta data from a list in a Graph response.
     *
     * @param array $data The Graph response.
     *
     * @return array
     */
    public function getMetaData(array $data)
    {
        unset($data['data']);

        return $data;
    }

    /**
     * Determines whether or not the data should be cast as a GraphEdge.
     *
     * @param array $data
     *
     * @return boolean
     */
    /*public static function isCastableAsGraphEdge(array $data)
    {
        if ($data === []) {
            return true;
        }

        // Checks for a sequential numeric array which would be a GraphEdge
        return array_keys($data) === range(0, count($data) - 1);
    }*/

    /**
     * Ensures that the subclass in question is valid.
     *
     * @param string $subclassName The GraphNode subclass to validate.
     *
     * @throws HocmaiSDKException
     */
    public static function validateSubclass($subclassName)
    {
        if ($subclassName == static::BASE_ITEM_CLASS ||
            is_subclass_of($subclassName, static::BASE_ITEM_CLASS)) {
            return;
        }

        throw new HocmaiSDKException('The given subclass "' . $subclassName .
            '" is not valid. Cannot cast to an object that is not a Item subclass.', 620);
    }
}

