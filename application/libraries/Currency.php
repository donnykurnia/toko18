<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Currency Class
 * Class yang memuat fungsi2 yang berhubungan dengan format tampilan mata uang
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Donny Kurnia
 * @link        http://hantulab.blogspot.com/
 */
class Currency {

  /**
   * Fungsi untuk mencetak angka dalam format uang, sesuai setting aplikasi
   * @param int $number angka yang akan diformat
   * @param array konfigurasi untuk format output
   * @return string angka yang telah diformat
   */
  function formatCurrency ($number, $config = array())
  {
    //menghilangkan karakter non angka dari number
    $patterns = array('/$/','/\,/','/Rp/','/€/','/\s/');
    $replacements = array('','','','');
    $number = trim(preg_replace($patterns,$replacements,strval($number)));
    if (is_nan($number))
    {
      $number = '0';
    }
    //menentukan sign
    $isNegative = (substr($number,0,1) == '-');
    if ($isNegative)
    {
      $number = substr($number,1);
    }
    //mencari besarnya cent
    $cents = round(doubleval(strstr($number,'.'))*100);
    if ($cents < 10)
    {
      $cents = '0'.$cents;
    }
    if (intval($cents) == 0)
    {
      $cents = '-';
    }
    if ($pos = strpos($number,'.'))
    {
      $number = substr($number,0,$pos);
    }
    //menambahkan thousand separator ke number
    for ($i = 0; $i < floor((strlen($number)-(1+$i))/3); $i++)
    {
      $number = substr($number,0,strlen($number)-(4*$i+3)).$config['thousand_sep'].substr($number,strlen($number)-(4*$i+3));
    }
    return ((($isNegative)?'-&nbsp;':'') . (($config['showcurrency'])?$config['currency']:'') . $number . $config['decimal_sep'] . $cents);
  }

  /**
    * Fungsi Terbilang untuk mengubah angka menjadi bentuk terbilang
    * @param int $bilangan bilangan yang akan diubah bentuknya
    * @return string berupa bentuk terbilang $bilangan
    */
  function terbilang($bilangan)
  {
    $angka = array('0','0','0','0','0','0','0','0','0','0',
                   '0','0','0','0','0','0');
    $kata = array('','satu','dua','tiga','empat','lima',
                  'enam','tujuh','delapan','sembilan');
    $tingkat = array('','ribu','juta','milyar','triliun');
    $panjang_bilangan = strlen($bilangan);
    /* pengujian panjang bilangan */
    if ($panjang_bilangan > 15)
    {
      $kalimat = 'Diluar Batas';
      return $kalimat;
    }
    /* mengambil angka-angka yang ada dalam bilangan,
       dimasukkan ke dalam array */
    for ($i = 1; $i <= $panjang_bilangan; $i++)
    {
      $angka[$i] = substr($bilangan,-($i),1);
    }
    $i = 1;
    $j = 0;
    $kalimat = '';
    /* mulai proses iterasi terhadap array angka */
    while ($i <= $panjang_bilangan)
    {
      $subkalimat = '';
      $kata1 = '';
      $kata2 = '';
      $kata3 = '';
      /* untuk ratusan */
      if ($angka[$i+2] != '0')
      {
        if ($angka[$i+2] == '1')
        {
          $kata1 = 'seratus';
        }
        else
        {
          $kata1 = $kata[$angka[$i+2]] . ' ratus';
        }
      }
      /* untuk puluhan atau belasan */
      if ($angka[$i+1] != '0')
      {
        if ($angka[$i+1] == '1')
        {
          if ($angka[$i] == '0')
          {
            $kata2 = 'sepuluh';
          }
          elseif ($angka[$i] == '1')
          {
            $kata2 = 'sebelas';
          }
          else
          {
            $kata2 = $kata[$angka[$i]] . ' belas';
          }
        }
        else
        {
          $kata2 = $kata[$angka[$i+1]] . ' puluh';
        }
      }
      /* untuk satuan */
      if ($angka[$i] != '0')
      {
        if ($angka[$i+1] != '1')
        {
          $kata3 = $kata[$angka[$i]];
        }
      }
      /* pengujian angka apakah tidak nol semua,
         lalu ditambahkan tingkat */
      if (($angka[$i] != '0') OR ($angka[$i+1] != '0') OR
          ($angka[$i+2] != '0'))
      {
        $subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . ' ';
      }
      /* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
         ke variabel kalimat */
      $kalimat = $subkalimat . $kalimat;
      $i = $i + 3;
      $j = $j + 1;
    }
    /* mengganti satu ribu jadi seribu jika diperlukan */
    if (($angka[5] == '0') AND ($angka[6] == '0'))
    {
      $kalimat = str_replace('satu ribu','seribu',$kalimat);
    }
    return trim($kalimat);
  }
}
