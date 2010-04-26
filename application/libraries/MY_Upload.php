<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File Uploading Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Uploads
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/file_uploading.html
 */
class MY_Upload extends CI_Upload {

	var $is_flash		= FALSE;

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function MY_Upload($props = array())
	{
    // Call the Model constructor
    parent::CI_Upload();
		log_message('debug', "MY_Upload Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function initialize($config = array())
	{
		$defaults = array(
							'max_size'			=> 0,
							'max_width'			=> 0,
							'max_height'		=> 0,
							'max_filename'		=> 0,
							'allowed_types'		=> "",
							'file_temp'			=> "",
							'file_name'			=> "",
							'orig_name'			=> "",
							'file_type'			=> "",
							'file_size'			=> "",
							'file_ext'			=> "",
							'upload_path'		=> "",
							'overwrite'			=> FALSE,
							'encrypt_name'		=> FALSE,
							'is_image'			=> FALSE,
							'image_width'		=> '',
							'image_height'		=> '',
							'image_type'		=> '',
							'image_size_str'	=> '',
							'error_msg'			=> array(),
							'mimes'				=> array(),
							'remove_spaces'		=> TRUE,
							'xss_clean'			=> FALSE,
							'temp_prefix'		=> "temp_file_",
							'is_flash'			=> FALSE
						);	

		foreach ($defaults as $key => $val)
		{
			if (isset($config[$key]))
			{
				$method = 'set_'.$key;
				if (method_exists($this, $method))
				{
					$this->$method($config[$key]);
				}
				else
				{
					$this->$key = $config[$key];
				}			
			}
			else
			{
				$this->$key = $val;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Validate the image
	 *
	 * @access	public
	 * @return	bool
	 */	
	function is_image()
	{
		// IE will sometimes return odd mime-types during upload, so here we just standardize all
		// jpegs or pngs to the same file type.

		$png_mimes  = array('image/x-png');
		$jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');
		if ( $this->is_flash )
		{
  		$png_mimes  = array('image/x-png', 'application/octet-stream');
  		$jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg', 'application/octet-stream');
		}

		if (in_array($this->file_type, $png_mimes))
		{
			$this->file_type = 'image/png';
		}

		if (in_array($this->file_type, $jpeg_mimes))
		{
			$this->file_type = 'image/jpeg';
		}

		$img_mimes = array(
							'image/gif',
							'image/jpeg',
							'image/png',
						   );
    if ( $this->is_flash )
    {
      $img_mimes = array(
        'image/gif',
        'image/jpeg',
        'image/png',
        'application/octet-stream',
      );
    }

		return (in_array($this->file_type, $img_mimes, TRUE)) ? TRUE : FALSE;
	}

	// --------------------------------------------------------------------

}
// END MY_Upload Class

/* End of file MY_Upload.php */
/* Location: ./system/application/libraries/MY_Upload.php */