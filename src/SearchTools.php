<?php

namespace bnjns\SearchTools;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class SearchTools
{
    /**
     * Variable to store the request object.
     * @var \Illuminate\Http\Request
     */
    private static $request;
    
    /**
     * Variable to store the router object.
     * @var \Illuminate\Routing\Router
     */
    private static $router;
    
    /**
     * Variable to store which areas of the tool to render.
     * @var array
     */
    private static $show = [
        'filter' => true,
        'search' => true,
    ];
    
    /**
     * Variable to store the request values.
     * @var array
     */
    private static $values = [];
    
    /**
     * Variable to store the filter options.
     * @var array
     */
    private static $filterOptions = [];
    
    /**
     * The constructor. Initialise the object.
     * @param \Illuminate\Http\Request   $request
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        static::$request = $request;
        static::$router  = $router;
        
        static::$values = [
            'filter' => $this->getQueryValue('filter'),
            'search' => $this->getQueryValue('search'),
        ];
    }
    
    /**
     * Get the value of the current search query.
     * @return mixed|null
     */
    public function search()
    {
        return static::$values['search'];
    }
    
    /**
     * Get the value of the current filter query
     * @return mixed|null
     */
    public function filter()
    {
        return static::$values['filter'];
    }
    
    /**
     * Show a specific tool.
     * @param $name
     * @return $this
     */
    public function show($name)
    {
        static::$show[$name] = true;
        return $this;
    }
    
    /**
     * Hide a specific tool.
     * @param $name
     * @return $this
     */
    public function hide($name)
    {
        static::$show[$name] = false;
        return $this;
    }
    
    /**
     * Set the filter options array.
     * @param array $options
     * @return $this
     */
    public function setFilterOptions(array $options)
    {
        static::$filterOptions = $options;
        return $this;
    }
    
    /**
     * Add a single filter option to the array.
     * @param $filter
     * @param $text
     * @return $this
     */
    public function addFilterOption($filter, $text)
    {
        static::$filterOptions[$filter] = $text;
        return $this;
    }
    
    /**
     * Render the view.
     * @return $this
     */
    public function render()
    {
        // Get the URL
        $url   = static::$request->url();
        $query = static::$request->query();
        
        // Set up the query
        if(!is_null(static::$values['filter']) && isset($query['filter'])) {
            unset($query['filter']);
        }
        if(!is_null(static::$values['search']) && isset($query['search'])) {
            unset($query['search']);
        }
        if(static::$request->has('page') && isset($query['page'])) {
            unset($query['page']);
        }
        
        // Set the filter values
        if(count(static::$filterOptions)) {
            $filter_list = [
                (object) [
                    'text'  => '- no filter -',
                    'url'   => $this->createUrl($url, $query),
                    'value' => '',
                ],
            ];
            foreach(static::$filterOptions as $filter => $text) {
                $filter_list[] = (object) [
                    'text'  => $text,
                    'url'   => $this->createUrl($url, $query + ['filter' => $filter]),
                    'value' => $filter,
                ];
            }
        } else {
            $filter_list = [];
        }
        
        // Render the view
        return view('search-tools::bootstrap')->with('FilterValue', static::$values['filter'])
                                              ->with('SearchValue', static::$values['search'])
                                              ->with('FilterOptions', $filter_list)
                                              ->with('ShowTools', static::$show)
                                              ->with('ClearSearchLink', $this->createUrl($url, $query))
                                              ->with('BaseURL', $url)
                                              ->with('BaseQuery', $query);
    }
    
    /**
     * Include the stylesheet tag.
     * @return string
     */
    public function assets()
    {
        return '<link rel="stylesheet" href="/css/vendors/search_tools/search_tools.css">';
    }
    
    /**
     * Get the value of an entry in the request object.
     * @param $name
     * @return mixed|null
     */
    private function getQueryValue($name)
    {
        return static::$request->has($name) && !empty(static::$request->get($name)) ? static::$request->get($name) : null;
    }
    
    /**
     * Create a valid URL string.
     * @param       $url
     * @param array $query
     * @return string
     */
    private function createUrl($url, array $query = [])
    {
        return empty($query) ? $url : ($url . '?' . http_build_query($query));
    }
}