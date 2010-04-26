<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Image Manipulation class
 * remove 2>&1 from command line
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Image_lib
 * @author		Donny Kurnia
 * @link		http://codeigniter.com/user_guide/libraries/image_lib.html
 */
class MY_Image_lib extends CI_Image_lib {

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function MY_Image_lib($props = array())
	{
	  parent::CI_Image_lib($props);
	  log_message('debug', 'MY_Image_lib initialized');
	}

	// --------------------------------------------------------------------

  /**
   * Image Process Using ImageMagick
   *
   * This function will resize, crop or rotate
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function image_process_imagemagick($action = 'resize')
  {
    //  Do we have a vaild library path?
    if ($this->library_path == '')
    {
      $this->set_error('imglib_libpath_invalid');
      return FALSE;
    }

    if ( ! eregi("convert$", $this->library_path))
    {
      if ( ! eregi("/$", $this->library_path)) $this->library_path .= "/";

      $this->library_path .= 'convert';
    }

    // Execute the command
    $cmd = $this->library_path." -quality ".$this->quality;

    if ($action == 'crop')
    {
      $cmd .= " -crop ".$this->width."x".$this->height."+".$this->x_axis."+".$this->y_axis." \"$this->full_src_path\" \"$this->full_dst_path\"";
    }
    elseif ($action == 'rotate')
    {
      switch ($this->rotation_angle)
      {
        case 'hor'  : $angle = '-flop';
          break;
        case 'vrt'  : $angle = '-flip';
          break;
        default   : $angle = '-rotate '.$this->rotation_angle;
          break;
      }

      $cmd .= " ".$angle." \"$this->full_src_path\" \"$this->full_dst_path\"";
    }
    else  // Resize
    {
      $cmd .= " -resize ".$this->width."x".$this->height." \"$this->full_src_path\" \"$this->full_dst_path\"";
    }

    $retval = 1;

    @exec($cmd, $output, $retval);

    //  Did it work?
    if ($retval > 0)
    {
      $this->set_error('imglib_image_process_failed');
      return FALSE;
    }

    // Set the file to 777
    @chmod($this->full_dst_path, DIR_WRITE_MODE);

    return TRUE;
  }

}
// END Image_lib Class

/* End of file Image_lib.php */
/* Location: ./system/application/libraries/Image_lib.php */