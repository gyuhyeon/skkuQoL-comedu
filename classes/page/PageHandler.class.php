<?php
/* Copyright (C) NAVER <http://www.navercorp.com> */

/**
 * @class PageHandler
 * @author NAVER (developers@xpressengine.com)
 * handles page navigation
 * @version 0.1
 *
 * @remarks Getting total counts, number of pages, current page number, number of items per page, 
 *          this class implements methods and contains variables for page navigation
 */
class PageHandler extends Handler
{

	var $total_count = 0; ///< number of total items
	var $total_page = 0; ///< number of total pages
	var $cur_page = 0; ///< current page number
	var $page_count = 10; ///< number of page links displayed at one time
	var $first_page = 1; ///< first page number
	var $last_page = 1; ///< last page number
	var $point = 0; ///< increments per getNextPage() 

	/**
	 * constructor
	 * @param int $total_count number of total items
	 * @param int $total_page number of total pages
	 * @param int $cur_page current page number
	 * @param int $page_count number of page links displayed at one time 
	 * @return void
	 */

	function PageHandler($total_count, $total_page, $cur_page, $page_count = 10)
	{
		$this->total_count = $total_count;
		$this->total_page = $total_page;
		$this->cur_page = $cur_page;
		$this->page_count = $page_count;
		$this->point = 0;

		$first_page = $cur_page - (int) ($page_count / 2);
		if($first_page < 1)
		{
			$first_page = 1;
		}

		if($total_page > $page_count && $first_page + $page_count - 1 > $total_page)
		{
			$first_page -= $first_page + $page_count - 1 - $total_page;
		}

		$last_page = $total_page;
		if($last_page > $total_page)
		{
			$last_page = $total_page;
		}

		$this->first_page = $first_page;
		$this->last_page = $last_page;

		if($total_page < $this->page_count)
		{
			$this->page_count = $total_page;
		}
	}

	/**
	 * request next page
	 * @return int next page number
	 */
	function getNextPage()
	{
		$page = $this->first_page + $this->point++;
		if($this->point > $this->page_count || $page > $this->last_page)
		{
			$page = 0;
		}
		return $page;
	}

	/**
	 * return number of page that added offset.
	 * @param int $offset
	 * @return int
	 */
	function getPage($offset)
	{
		return max(min($this->cur_page + $offset, $this->total_page), '');
	}

}
/* End of file PageHandler.class.php */
/* Location: ./classes/page/PageHandler.class.php */
