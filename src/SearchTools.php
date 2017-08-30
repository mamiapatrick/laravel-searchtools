<?php

namespace bnjns\SearchTools;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class SearchTools
{
    /**
     * Variable to store the request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Variable to store the router object.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Variable to store which areas of the tool to render.
     *
     * @var array
     */
    protected $show = [
        'filter' => true,
        'search' => true,
    ];

    /**
     * Variable to store the request values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Variable to store the filter options.
     *
     * @var array
     */
    protected $filterOptions = [];

    /**
     * Variable to store the text for no filter.
     *
     * @var string
     */
    protected $noFilterText = '- no filter -';

    /**
     * The constructor. Initialise the object.
     *
     * @param \Illuminate\Http\Request   $request
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router  = $router;

        $this->values = [
            'filter' => $this->getQueryValue('filter'),
            'search' => $this->getQueryValue('search'),
        ];
    }

    /**
     * Get the value of the current search query.
     *
     * @return mixed|null
     */
    public function search()
    {
        return $this->values['search'];
    }

    /**
     * Get the value of the current filter query.
     *
     * @return mixed|null
     */
    public function filter()
    {
        return $this->values['filter'];
    }

    /**
     * Show a specific tool.
     *
     * @param $name
     *
     * @return $this
     */
    public function show($name)
    {
        $this->show[$name] = true;

        return $this;
    }

    /**
     * Hide a specific tool.
     *
     * @param $name
     *
     * @return $this
     */
    public function hide($name)
    {
        $this->show[$name] = false;

        return $this;
    }

    /**
     * Set the filter options array.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setFilterOptions(array $options)
    {
        $this->filterOptions = $options;

        return $this;
    }

    /**
     * Add a single filter option to the array.
     *
     * @param $filter
     * @param $text
     *
     * @return $this
     */
    public function addFilterOption($filter, $text)
    {
        $this->filterOptions[$filter] = $text;

        return $this;
    }

    /**
     * Set the text to show for no filter applied.
     *
     * @param $text
     *
     * @return $this
     */
    public function setNoFilterText($text)
    {
        $this->noFilterText = $text;

        return $this;
    }

    /**
     * Render the view.
     *
     * @return $this
     */
    public function render()
    {
        // Get the URL
        $url   = $this->request->url();
        $query = $this->request->query();

        // Set up the query
        if (!is_null($this->values['filter']) && isset($query['filter'])) {
            unset($query['filter']);
        }
        if (!is_null($this->values['search']) && isset($query['search'])) {
            unset($query['search']);
        }
        if ($this->request->has('page') && isset($query['page'])) {
            unset($query['page']);
        }

        // Set the filter values
        if (count($this->filterOptions)) {
            $filter_list = [
                (object)[
                    'text'  => $this->noFilterText,
                    'url'   => $this->createUrl($url, $query),
                    'value' => '',
                ],
            ];
            foreach ($this->filterOptions as $filter => $text) {
                $filter_list[] = (object)[
                    'text'  => $text,
                    'url'   => $this->createUrl($url, $query + ['filter' => $filter]),
                    'value' => $filter,
                ];
            }
        } else {
            $filter_list = [];
        }

        // Render the view
        return view('search-tools::bootstrap')->with('FilterValue', $this->values['filter'])
                                              ->with('SearchValue', $this->values['search'])
                                              ->with('FilterOptions', $filter_list)
                                              ->with('ShowTools', $this->show)
                                              ->with('ClearSearchLink', $this->createUrl($url, $query))
                                              ->with('BaseURL', $url)
                                              ->with('BaseQuery', $query);
    }

    /**
     * Include the stylesheet tag.
     *
     * @return string
     */
    public function assets()
    {
        return '<link rel="stylesheet" href="/css/vendors/search_tools/search_tools.css">';
    }

    /**
     * Get the value of an entry in the request object.
     *
     * @param $name
     *
     * @return mixed|null
     */
    private function getQueryValue($name)
    {
        return $this->request->has($name) && !empty($this->request->get($name)) ? $this->request->get($name) : null;
    }

    /**
     * Create a valid URL string.
     *
     * @param       $url
     * @param array $query
     *
     * @return string
     */
    private function createUrl($url, array $query = [])
    {
        return empty($query) ? $url : ($url . '?' . http_build_query($query));
    }
}
