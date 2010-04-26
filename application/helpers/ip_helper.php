<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('encode_ip'))
{
  /**
   * Fungsi encode_ip didapat dari phpBB 2.0.11
   *
   * @access	public
   * @param	string IP address yang akan di-encode
   * @return string
   */
  function encode_ip($dotquad_ip)
  {
    $ip_sep = explode('.', $dotquad_ip);
    return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
  }
}

if ( ! function_exists('decode_ip'))
{
  /**
   * Fungsi decode_ip didapat dari phpBB 2.0.11
   *
   * @access	public
   * @param	string IP address yang akan di-decode
   * @return string
   */
  function decode_ip($int_ip)
  {
    $hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
    return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
  }
}

if ( ! function_exists('inet_aton'))
{
  /**
   * Given the dotted-quad representation of a network address as a string,
   * returns an integer that represents the numeric value of the address.
   * Addresses may be 4- or 8-byte addresses. 
   *
   * @param string $ip_address - A dotted-quad network address.
   * @return string proper address
   */
  function inet_aton($ip_address)
  {
    return sprintf("%u", ip2long($ip_address));
  }
}

if ( ! function_exists('inet_ntoa'))
{
  /**
   * Given a numeric network address in network byte order (4 or 8 byte),
   * returns the dotted-quad representation of the address as a string. 
   *
   * @param int $proper_address - A numeric network address. 
   * @return string the Internet IP address as a string. 
   */
  function inet_ntoa($proper_address)
  {
    return long2ip($proper_address);
  }
}
