<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('padding'))
{
  /**
   * Fungsi untuk menambahkan string $pad
   * ke string $s bila panjangnya kurang dari $len pada posisi $pos
   *
   * @access	public
   * @param	string string yang akan di-padding
   * @param	int panjang string minimum
   * @param	string string yang akan mengisi ruang kosong padding
   * @param	string posisi padding, di depan atau belakang
   * @return string
   */
  function padding($s, $len, $pad, $pos = 'front')
  {
    if (strlen($s) < $len)
    {
      if ($pos == 'front')
      {
        return $pad . $s;
      }
      else
      {
        return $s . $pad;
      }
    }
    else
    {
      return $s;
    }
  }
}

if ( ! function_exists('return_bytes'))
{
  /**
   * Fungsi untuk mengembalikan nilai $val dalam bytes
   *
   * @access	public
   * @param	string nilai dengan satuan yang hendak dijadikan bytes
   * @return string
   */
  function return_bytes($val)
  {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last)
    {
      // The 'G' modifier is available since PHP 5.1.0
      case 'g':
      $val *= 1024;
      case 'm':
      $val *= 1024;
      case 'k':
      $val *= 1024;
    }
    return $val;
  }
}

if ( ! function_exists('return_kilobytes'))
{
  /**
   * Fungsi untuk mengembalikan nilai $val dalam kilobytes
   *
   * @access	public
   * @param	string nilai dengan satuan yang hendak dijadikan kilobytes
   * @return string
   */
  function return_kilobytes($val)
  {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last)
    {
      // The 'G' modifier is available since PHP 5.1.0
      case 'g':
      $val *= 1024;
      case 'm':
      $val *= 1024;
      case 'k':
      $val *= 1024;
    }
    return $val/1024;
  }
}

if ( ! function_exists('nilai'))
{
  /** Fungsi untuk menampilkan nilai atau nilai null jika nilai yang hendak ditampilkan tidak ada / NULL
   *
   * @access	public
   * @param	string nilai yang akan ditampilkan
   * @return string
   */
  function nilai($value, $NULL_value)
  {
    if (!$value)
    {
      return $NULL_value;
    }
    else
    {
      return $value;
    }
  }
}
