<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('display_flag'))
{
  /**
   * Fungsi display_flag
   * display flag for supplied iso3166 country code
   * @param string $country_code : two letter country code
   * @param string $flag_path
   * @return string flag image url
   */
  function display_flag ($country_code=''
                        , $country_name=''
                        , $width=16, $height=11
                        , $no_flag='&nbsp;'
                        , $flag_path='', $img_ext='.gif')
  {
    $country_name = trim($country_name);
    if ($flag_path == '')
    {
      $flag_path = base_url().'public/images/flags/';
    }
    $result = $no_flag;
    if ($country_code!='')
    {
      $country_code = strtolower(substr($country_code, 0, 2));
      if (strlen($country_code)==2)
      {
        $width  = intval($width);
        $height = intval($height);
        $title = $country_name != '' ? "title='{$country_name}'" : '';
        $result = sprintf('<img src="%s" alt="%s" width="%d" height="%d" style="border: 0px;" %s />',
                           $flag_path.$country_code.$img_ext, $country_code, $width, $height, $title);
      }
    }
    return $result;
  }

}
