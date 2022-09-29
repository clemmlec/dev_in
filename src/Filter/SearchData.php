<?php

namespace App\Filter;

class SearchData
{
    private ?int $page = 1;
    private ?string $query = '';
    private ?array $forum = [];

    /**
     * Get the value of page.
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page.
     *
     * @return self
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the value of query.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the value of query.
     *
     * @return self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of categories.
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Set the value of categories.
     *
     * @return self
     */
    public function setForum($forum)
    {
        $this->forum = $forum;

        return $this;
    }
}
