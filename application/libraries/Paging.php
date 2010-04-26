<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Paging Class
 *
 * @package    CodeIgniter
 * @subpackage  Libraries
 * @category  Pagination
 * @author    Donny Kurnia
 * @link    http://hantulab.blogspot.com/
 */
class Paging {

  var $base_url        = ''; // The page we are linking to
  var $suffix_url      = '';
  var $total_rows      = '0'; // Total number of items (database results)
  var $per_page        = 20; // Max number of items you want shown per page
  var $start           = '0'; // The start offset of current page
  var $num_page        = '0'; // Total number of page
  var $page_array      = array(); // Array to store the page number
  var $num_links       = 2; // Number of links show before and after the currently viewed page.
                            // Example with num_links = 1, current page = 6, and num_page = 10
                            //  1 &#8230; 5 6 7 &#8230; 10
  var $cur_page        = 1; // The current page being viewed
  var $uri_segment     = 0;
  var $first_link      = '&laquo; First';
  var $prev_link       = '&lsaquo; Previous';
  var $next_link       = 'Next &rsaquo;';
  var $last_link       = 'Last &raquo;';
  var $full_tag_open   = '';
  var $full_tag_close  = '';
  var $first_tag_open  = '';
  var $first_tag_close = '';
  var $prev_tag_open   = '&nbsp;';
  var $prev_tag_close  = '';
  var $next_tag_open   = '&nbsp;';
  var $next_tag_close  = '';
  var $last_tag_open   = '&nbsp;';
  var $last_tag_close  = '';
  var $cur_tag_open    = '&nbsp;<b>';
  var $cur_tag_close   = '</b>';
  var $num_tag_open    = '&nbsp;';
  var $num_tag_close   = '';
  var $inactive_tag_open  = '&nbsp;';
  var $inactive_tag_close = '';
  var $title              = '';
  var $show_first         = false;
  var $show_prev          = true;
  var $show_next          = true;
  var $show_last          = false;
  var $href_class         = 'pagenav';
  var $first_href_class   = 'pagenav';
  var $prev_href_class    = 'pagenav';
  var $current_href_class = 'pagenav';
  var $next_href_class    = 'pagenav';
  var $last_href_class    = 'pagenav';
  var $show_elipses       = TRUE;

  /**
   * Constructor
   *
   * @access  public
   * @param  array  initialization parameters
   */
  function Paging($params = array())
  {
    if (count($params) > 0)
    {
      $this->initialize($params);
    }

    log_message('debug', "Paging Class Initialized");
  }

  // --------------------------------------------------------------------

  /**
   * Initialize Preferences
   *
   * @access  public
   * @param  array  initialization parameters
   * @return  void
   */
  function initialize($params = array())
  {
    if (count($params) > 0)
    {
      foreach ($params as $key => $val)
      {
        if (isset($this->$key))
        {
          $this->$key = $val;
        }
      }
    }
    if ($this->per_page > 0 AND $this->total_rows > 0)
    {
      // Calculate the total number of pages
      $this->num_page = ceil($this->total_rows / $this->per_page);

      //fix the num_page
      if ($this->num_page < 1)
      {
        $this->num_page = 1;
      }

      // Determine the current page number.
      $CI =& get_instance();
      if ($this->uri_segment > 0 AND $CI->uri->rsegment($this->uri_segment) > 0)
      {
        $this->cur_page = $CI->uri->rsegment($this->uri_segment);
      }

      // Prep the current page - no funny business!
      $this->cur_page = preg_replace("/[a-z\-]/", "", $this->cur_page);

      if ( ! is_numeric($this->cur_page) OR $this->cur_page < 1)
      {
        $this->cur_page = 1;
      }

      // fix terhadap cur_page
      // Is the page number beyond the result range?
      // If so we show the last page
      if ($this->cur_page > $this->num_page)
      {
        $this->cur_page = $this->num_page;
      }

      //menghitung awal item (untuk limit MySQL)
      $this->start = ($this->cur_page - 1) * $this->per_page;
      $this->page_array = $this->_createNavigasi ($this->cur_page,
                                                  $this->num_page,
                                                  $this->num_links);
    }
  }

  // --------------------------------------------------------------------

  /**
   * Fungsi untuk menghasilkan array halaman yang akan tampil
   *
   * @access  private
   * @param  int  current page number
   * @param  int  total page number
   * @param  int  num links to shown in pagination
   * @return  array
   */
  function _createNavigasi($cur_page, $num_page, $num_links) {
    $hasil = array();
    if ($num_page > $num_links)
    {
      // penentuan $start dan $end
      if (($cur_page - $num_links) < 1)
      {
        $start = 1;
        $end = $start + $num_links;
      }
      else if (($cur_page + $num_links) > $num_page)
      {
        $end = $num_page;
        $start = $end - $num_links;
      }
      else
      {
        $start = $cur_page - $num_links;
        $end = $cur_page + $num_links;
      }

      //beginning
      if ($start == 2)
      {
        $hasil[] = 1;
      }
      else if ($start > 2)
      {
        $hasil[] = 1;
        $hasil[] = '&#8230;';
      }

      // main loop
      for ($i=$start; $i<=$end; $i++)
      {
        $hasil[] = $i;
      }

      // end
      if ($end == ($num_page - 1))
      {
        $hasil[] = $num_page;
      }
      else if ($end < ($num_page - 1))
      {
        $hasil[] = '&#8230;';
        $hasil[] = $num_page;
      }
    }
    else
    {
      // normal loop
      for ($i=1; $i<=$num_page; $i++)
      {
        $hasil[] = $i;
      }
    }
    return $hasil;
  }

  // --------------------------------------------------------------------

  /**
   * Generate the pagination links
   *
   * @access  public
   * @return  string
   */
  function create_links()
  {
    // If our item count or per-page total is zero there is no need to continue.
    if ($this->total_rows == 0 OR $this->per_page == 0)
    {
       return '';
    }

    // Is there only one page? Hm... nothing more to do here then.
    if ($this->num_page == 1)
    {
      return '';
    }

    // Add a trailing slash to the base URL if needed
    $this->base_url = preg_replace("/(.+?)\/*$/", "\\1/",  $this->base_url);

    // And here we go...
    $output = '';

    // Render the "First" link
    if ($this->cur_page > 1) // if current page > 1
    {
      if ($this->show_first) // show nav to first page
      {
        $output .= $this->first_tag_open.
                   '<a'.(($this->first_href_class!='') ? ' class="'.$this->first_href_class.'"' : '').
                   ' href="'.site_url(sprintf($this->base_url, 1, $this->suffix_url)).'" id="page_first" title="'.$this->first_link.'" alt="1">'.$this->first_link.'</a>'.
                   $this->first_tag_close;
      }
    }
    else // current page == 1
    {
      if ($this->show_first) // show nav to first page
      {
        $output .= $this->inactive_tag_open.$this->first_link.$this->inactive_tag_close;
      }
    }

    // Render the "previous" link
    if ($this->cur_page > 1) // if current page > 1
    {
      if ($this->show_prev) //show nav to prev page
      {
        $output .= $this->prev_tag_open.
                   '<a'.(($this->prev_href_class!='') ? ' class="'.$this->prev_href_class.'"' : '').
                   ' href="'.site_url(sprintf($this->base_url, ($this->cur_page - 1), $this->suffix_url)).'" id="page_prev" title="'.$this->prev_link.'" alt="'.($this->cur_page - 1).'">'.$this->prev_link.'</a>'.
                   $this->prev_tag_close;
      }
    }
    else // current page == 1
    {
      if ($this->show_prev) //show nav to prev page
      {
        $output .= $this->inactive_tag_open.$this->prev_link.$this->inactive_tag_close;
      }
    }

    // Write the digit links
    foreach($this->page_array as $hal) // loop page_array
    {
      if ($hal == '&#8230;')                  // '&#8230;' untuk link yang jumlahnya melebihi $num_links
      {
        if ( $this->show_elipses )
        {
          $output .= $this->inactive_tag_open.$hal.$this->inactive_tag_close;
        }
      }
      else if ($hal == $this->cur_page)   // current page
      {
        $output .= $this->cur_tag_open.$this->title.$hal.$this->cur_tag_close;
      }
      else                                // navigation link
      {
        $output .= $this->num_tag_open.
                   '<a'.(($this->href_class!='') ? ' class="'.$this->href_class.'"' : '').
                   ' href="'.site_url(sprintf($this->base_url, $hal, $this->suffix_url)).'" id="page_'.($hal).'" title="'.$this->title.$hal.'" alt="'.($hal).'">'.$this->title.$hal.'</a>'.
                   $this->num_tag_close;
      }
    } // end of loop page_array

    // Render the "next" link
    if ($this->cur_page < $this->num_page) // current page < jumlah page
    {
      if ($this->show_next) //show nav to next page
      {
        $output .= $this->next_tag_open.
                   '<a'.(($this->next_href_class!='') ? ' class="'.$this->next_href_class.'"' : '').
                   ' href="'.site_url(sprintf($this->base_url, ($this->cur_page + 1), $this->suffix_url)).'" id="page_next" title="'.$this->next_link.'" alt="'.($this->cur_page + 1).'">'.$this->next_link.'</a>'.
                   $this->next_tag_close;
      }
    }
    else // current page == jumlah page
    {
      if ($this->show_next) //show nav to next page
      {
        $output .= $this->inactive_tag_open.$this->next_link.$this->inactive_tag_close;
      }
    }

    // Render the "Last" link
    if ($this->cur_page < $this->num_page) // current page < jumlah page
    {
      if ($this->show_last) // show nav to last page
      {
        $output .= $this->last_tag_open.
                   '<a'.(($this->last_href_class!='') ? ' class="'.$this->last_href_class.'"' : '').
                   ' href="'.site_url(sprintf($this->base_url, $this->num_page, $this->suffix_url)).'" id="page_last" title="'.$this->last_link.'" alt="'.($this->num_page).'">'.$this->last_link.'</a>'.
                   $this->last_tag_close;
      }
    }
    else // current page == jumlah page
    {
      if ($this->show_last) // show nav to last page
      {
        $output .= $this->inactive_tag_open.$this->last_link.$this->inactive_tag_close;
      }
    }

    // Kill double slashes.  Note: Sometimes we can end up with a double slash
    // in the penultimate link so we'll kill all double slashes.
    $output = preg_replace("#([^:])//+#", "\\1/", $output);

    // Add the wrapper HTML if exists
    $output = $this->full_tag_open.$output.$this->full_tag_close;

    return $output;
  }
}
// END Pagination Class

