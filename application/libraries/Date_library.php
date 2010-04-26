<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Date_library {

  /* Constructor */
  function Date_library()
  {
    $this->CI =& get_instance();
    $this->CI->load->library('calendar');
  }

  /**
   * Fungsi timediff untuk mengembalikan selisih hari dengan hari ini
   * Jika selisih hari < 1, maka akan diperlihatkan selisih jam
   * Jika selisih jam < 1, maka akan diperlihatkan selisih menit
   *
   * @access	public
   * @param	date tanggal yang hendak dicari selisihnya, dalam format YYYY-MM-DD
   * @return string
   */
  function timediff($date, $config = array())
  {
    //get timestamp based on day
    $tsday     = strtotime(date('Y-m-d', strtotime($date)));
    $tsday_now = strtotime(date('Y-m-d', mktime()));
    //get real timestamp
    $ts        = strtotime($date);
    $ts_now    = mktime();

    if ($ts_now > $ts) // past time
    {
      $day_diff = floor(($tsday_now - $tsday) / (3600 * 24));
      $result = sprintf($config['pastday'], $day_diff);
      if ($day_diff==0)
      {
        $hour_diff = floor(($ts_now - $ts) / (3600));
        $result = sprintf($config['pasthour'], $hour_diff);
        if ($hour_diff==0)
        {
          $minutes_diff = floor(($ts_now - $ts) / (60));
          if ($minutes_diff == 0)
          {
            $result = $config['now'];
          }
          else
          {
            $result = sprintf($config['pastminutes'], $minutes_diff);
          }
        }
      }
    }
    else if ($ts_now < $ts) // future time
    {
      $day_diff = floor(($tsday - $tsday_now) / (3600 * 24));
      $result = sprintf($config['futureday'], $day_diff);
      if ($day_diff==0)
      {
        $hour_diff = floor(($ts - $ts_now) / (3600));
        $result = sprintf($config['futurehour'], $hour_diff);
        if ($hour_diff==0)
        {
          $minutes_diff = floor(($ts - $ts_now) / (60));
          if ($minutes_diff == 0)
          {
            $result = $config['now'];
          }
          else
          {
            $result = sprintf($config['futureminutes'], $minutes_diff);
          }
        }
      }
    }
    else  // now
    {
      $result = $config['now'];
    }
    return $result;
  }

  /**
   * Fungsi untuk mengetahui apakah tahun $tahun kabisat atau tidak
   *
   * @access	public
   * @param	int tahun yang hendak dicek
   * @return bool
   */
  function isKabisat($tahun)
  {
    return (($tahun % 400 == 0) or (($tahun % 4 == 0) and ($tahun % 100 != 0)));
  }

  /**
   * Fungsi untuk mengubah format tanggal dari satu format ke format lain
   *
   * @access	public
   * @param	date tanggal yang hendak diubah formatnya
   * @param	string format tanggal input
   * @param	string format tanggal output yang diinginkan
   * @return bool
   */
  function KT_convertDate($date, $inFmt, $outFmt)
  {
    if (($inFmt == 'none') || ($outFmt == 'none'))
    {
      return $date;
    }
    if (ereg("^[0-9]+[/|-][0-9]+[/|-][0-9]+$", $date))
    {
      $outFmt = eregi_replace(" +.+$", "", $outFmt);
    }
    if (ereg ("%d[/.-]%m[/.-]%Y %H:%M:%S", $inFmt))
    {
      if (ereg ("([0-9]{1,2})[/.-]([0-9]{1,2})[/.-]([0-9]{2,4}) *([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}", $date, $regs))
      {
        for ($i=1;$i<7;$i++)
        {
          if ($regs[$i]=='' || !isset($regs[$i]))
          {
            $regs[$i]='00';
          }
        }
        $outdate = $outFmt;
        $outdate = ereg_replace("%Y",$regs[3],$outdate);
        $outdate = ereg_replace("%m",$regs[2],$outdate);
        $outdate = ereg_replace("%d",$regs[1],$outdate);
        $outdate = ereg_replace("%H",$regs[4],$outdate);
        $outdate = ereg_replace("%M",$regs[5],$outdate);
        $outdate = ereg_replace("%S",$regs[6],$outdate);
      }
      else
      {
        $outdate = $date;
      }
    }
    else if (ereg ("%Y[/|-]%m[/|-]%d %H:%M:%S", $inFmt))
    {
      if (ereg ("([0-9]{2,4})[/|-]([0-9]{1,2})[/|-]([0-9]{1,2}) *([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}", $date, $regs))
      {
        for ($i=1;$i<7;$i++)
        {
          if ($regs[$i]=='' || !isset($regs[$i]))
          {
            $regs[$i]='00';
          }
        }
        $outdate = $outFmt;
        $outdate = ereg_replace("%Y",$regs[1],$outdate);
        $outdate = ereg_replace("%m",$regs[2],$outdate);
        $outdate = ereg_replace("%d",$regs[3],$outdate);
        $outdate = ereg_replace("%H",$regs[4],$outdate);
        $outdate = ereg_replace("%M",$regs[5],$outdate);
        $outdate = ereg_replace("%S",$regs[6],$outdate);
      }
      else
      {
        $outdate = $date;
      }
    }
    else if (ereg ("%m[/|-]%d[/|-]%Y %H:%M:%S", $inFmt))
    {
      if (ereg ("([0-9]{1,2})[/|-]([0-9]{1,2})[/|-]([0-9]{2,4}) *([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}:{0,1}([0-9]{1,2}){0,1}", $date, $regs))
      {
        for ($i=1;$i<7;$i++)
        {
          if ($regs[$i]=='' || !isset($regs[$i]))
          {
            $regs[$i]='00';
          }
        }
        $outdate = $outFmt;
        $outdate = ereg_replace("%Y",$regs[3],$outdate);
        $outdate = ereg_replace("%m",$regs[1],$outdate);
        $outdate = ereg_replace("%d",$regs[2],$outdate);
        $outdate = ereg_replace("%H",$regs[4],$outdate);
        $outdate = ereg_replace("%M",$regs[5],$outdate);
        $outdate = ereg_replace("%S",$regs[6],$outdate);
      }
      else
      {
        $outdate = $date;
      }
    }
    else
    {
      show_error('Unknown data format : '.$inFmt.' .');
      log_message('error', 'Unknown data format : '.$inFmt.' .');
    }
    return $outdate;
  }

  /**
   * Fungsi untuk mendapatkan elemen tanggal dari database
   * dengan tipe data datetime pada MSSQL dan MySQL
   * dengan format yyyy-mm-dd hh:mm:ss ke dalam array
   * menggunakan fungsi getdate pada php dan menambahkan elemen :
   * - hariInd : nama hari
   * - bulanInd : nama bulan
   * elemen ini didapatkan dari library calendar
   * sesuai dengan language yang digunakan
   *
   * @access	public
   * @param	date tanggal yang hendak diubah formatnya
   * @param	array config
   * @return array
   */
  function getDateElements($dateInput)
  {
    list($tanggal, $waktu)        = explode(' ', $dateInput);
    list($year, $month, $day)     = split ('[/.-]', $tanggal);//explode('-', $tanggal);
    list($hour, $minute, $second) = explode(':', $waktu);
    if ($year >= 1970) // keterbatasan PHP
    {
      $tgl  = getdate(mktime($hour, $minute, $second, $month, $day, $year ));
      $wday = $tgl['wday'];
      $mon  = $tgl['mon'];
      $dayname          = $this->CI->calendar->get_day_names('long');
      $tgl['hariInd']   = $dayname[$wday];
      $tgl['bulanInd']  = $this->CI->calendar->get_month_name($month);
    }
    else  // untuk mengatasi keterbatasan fungsi mktime yang hanya mengembalikan nilai timestamp untuk tanggal >= 1 Januari 1970
    {
      $tgl['hariInd']   = '';
      $tgl['mday']      = $day;
      $tgl['bulanInd']  = $this->CI->calendar->get_month_name($month);
      $tgl['year']      = $year;
      $tgl['hours']     = $hour;
      $tgl['minutes']   = $minute;
      $tgl['seconds']   = $second;
    }
    $tgl['hours']   = (strlen($tgl['hours']) == 1 ? '0' : '') . $tgl['hours'];
    $tgl['minutes'] = (strlen($tgl['minutes']) == 1 ? '0' : '') . $tgl['minutes'];
    $tgl['seconds'] = (strlen($tgl['seconds']) == 1 ? '0' : '') . $tgl['seconds'];
    return $tgl;
  }

  /**
   * Fungsi untuk menambahkan tanggal tipe data datetime pada MSSQL dan MySQL
   * dengan format yyyy-mm-dd hh:mm:ss dengan nilai dalam jam lalu
   * mendapatkan elemen tanggal ke dalam array
   * menggunakan fungsi getdate pada php dan menambahkan elemen :
   * - hariInd : nama hari
   * - bulanInd : nama bulan
   * elemen ini elemen ini didapatkan dari library calendar
   * sesuai dengan language yang digunakan
   *
   * @access	public
   * @param	date tanggal yang akan ditambahkan
   * @param	hour jumlah jam yang akan ditambahkan terhadap tanggal
   * @param	array config
   * @return array
   */
  function addDate($dateInput, $houradd)
  {
    list($tanggal, $waktu)        = explode(' ', $dateInput);
    list($year, $month, $day)     = explode('-', $tanggal);
    list($hour, $minute, $second) = explode(':', $waktu);
    if ($year >= 1970) // keterbatasan PHP
    {
      $tgl  = getdate(mktime($hour, $minute, $second, $month, $day, $year ) + ($houradd * 3600));
      $wday = $tgl['wday'];
      $mon  = $tgl['mon'];
      $dayname          = $this->CI->calendar->get_day_names('long');
      $tgl['hariInd']   = $dayname[$wday];
      $tgl['bulanInd']  = $this->CI->calendar->get_month_name($month);
    }
    else  // untuk mengatasi keterbatasan fungsi mktime yang hanya mengembalikan nilai timestamp untuk tanggal >= 1 Januari 1970
    {
      // TODO : melakukan penambahan manual
      // sementara ini tidak berfungsi, hanya mengembalikan dateInput tanpa dijumlahkan dengan $houradd
      $tgl['hariInd']   = '';
      $tgl['mday']      = $day;
      $tgl['bulanInd']  = $this->CI->calendar->get_month_name($month);
      $tgl['year']      = $year;
      $tgl['hours']     = $hour;
      $tgl['minutes']   = $minute;
      $tgl['seconds']   = $second;
    }
    $tgl['hours']   = (strlen($tgl['hours']) == 1 ? '0' : '') . $tgl['hours'];
    $tgl['minutes'] = (strlen($tgl['minutes']) == 1 ? '0' : '') . $tgl['minutes'];
    $tgl['seconds'] = (strlen($tgl['seconds']) == 1 ? '0' : '') . $tgl['seconds'];
    return $tgl;
  }

  /**
   * Fungsi untuk memformat tampilan Tanggal dari nilai timestamp pada database
   *
   * @access	public
   * @param	string timestamp yang didapatkan dari database MySQL
   * @param	string format date PHP yang hendak digunakan sebagai output
   * @return array
   */
  // Fungsi untuk memformat tampilan Tanggal dari nilai timestamp pada database
  function Format_Date($timestamp, $format)
  {
    list($tanggal, $waktu) = explode(' ', $timestamp);
    list($year, $month, $day) = explode('-', $tanggal);
    list($hour, $minute, $second) = explode(':', $waktu);
    return (date($format,mktime($hour,$minute,$second,$month,$day,$year)));
  }
}
