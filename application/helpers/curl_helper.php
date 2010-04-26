<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_curl'))
{
  /**
   * Fungsi get_curl
   * Fungsi untuk meload halaman remote,
   * lalu mengembalikan info koneksi dan output halaman tersebut
   * Parameter:
   * @param string $url     : URL halaman remote
   * @param string $referer : string HTTP_REFERER yang dicek di halaman remote
   * @param array $post     : Array yang akan diterima halaman remote sebagai _POST
   * @return FALSE atau array yang terdiri dari output dari curl_exec dan info dari curl_getinfo
   */
  function get_curl ($url='', $referer='', $post=array(), $timeout=0)
  {
    //sanitize parameter
    $url = trim($url);
    $referer = trim($referer);
    $post = ! is_array($post) ? array() : $post;
    $timeout = intval($timeout);
    if ( $url != '' )
    {
      // create a new CURL resource
      $ch = curl_init();
      // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, $url);
      if ( $referer != '' )
      {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
      }
      if ( count($post) > 0 )
      {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      }
      if ( $timeout > 0 )
      {
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
      }
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // grab URL and pass it to the browser
      $output = curl_exec($ch);
      //get url info
      $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      // close CURL resource, and free up system resources
      curl_close($ch);
      //return CURL output dan CURL info
      return array('output' => $output,
                   'info'   => $info);
    }
    return FALSE;
  }
}
