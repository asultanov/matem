<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Publikation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use simplehtmldom\HtmlWeb;


class ParseController extends Controller
{


  public function sname($name)
  {
    $name = trim($name);
    $name = htmlspecialchars_decode($name);
    $a = [
      'O. olota' => 'O. A. Zolota',
      'Н.Вык' => 'Н. Садык',
      'R. arifullin' => 'R. N. Garifullin',
      'R. amilov' => 'R. I. Yamilov',
      'A. lexandrova' => 'A. A. Alexandrova',
      'N. bragimov' => 'N. H. Ibragimov',
      'K. mamutdinova' => 'K. V. Imamutdinova',
      'V. ukashchuk' => 'V. O. Lukashchuk',
      'V. orodnitsyn' => 'V. A. Dorodnitsyn',
      'C. rs' => 'C. Rogers',
      'С.Вбов' => 'С. Якубов',
      'A. ëllima' => 'A. Bërdëllima',
      'K. tukhin' => 'K. Zheltukhin',
      'S. atta' => 'S. K. Datta',
      'T. as' => 'T. Biswas',
      'P.' => 'P. Das',
      'L. aergoiz' => 'L. S. Maergoiz',
      'Р.Вов' => 'Р. Пиров',
      'A. Khrystiyanyn' => 'A. Ya. Khrystiyanyn',
      'A. ondratyuk' => 'A. A. Kondratyuk',
      'B. ilalov' => 'B. T. Bilalov',
      'T. asymov' => 'T. B. Gasymov',
      'B. ateswarlu' => 'B. Venkateswarlu',
      'N.' => 'N. Rani',
      'F. inger' => 'F. Haslinger',
      'A. ov' => 'A. Sukhov',
      'T. alo' => 'T. M. Salo',
      'O. kaskiv' => 'O. B. Skaskiv',
      'M. ani' => 'M. Saidani',
      'B. їdi' => 'B. Belaїdi',
      'N. han' => 'N. U. Khan',
      'T. n' => 'T. Usman',
      'M. yev' => 'M. Garayev',
      'H. iri' => 'H. Guediri',
      'H. aoui' => 'H. Sadraoui',
      'E. os' => 'E. Zikkos',
      'Ya.yasov' => 'Ya. Il\'yasov',
      'N. ev' => 'N. Valeev',
      'A. andura' => 'A. I. Bandura',
      'H. zhar' => 'H. El-Azhar',
      'K. ssi' => 'K. Idrissi',
      'E. erouali' => 'E. H. Zerouali',
      'M. uznetsova' => 'M. N. Kuznetsova',
      'A. bdelwanis' => 'A. Y. Abdelwanis',
      'H. ahash' => 'H. A. Wahash',
      'M. bdo' => 'M. S. Abdo',
      'S. anchal' => 'S. K. Panchal',
      'M. llia' => 'M. Benallia',
      'M. sai' => 'M. Moussai',
      'M. ağlı' => 'M. C. Dağlı',
      'S. akaev' => 'S. N. Lakaev',
      'M. s' => 'M. Darus',
      'S. ustov' => 'S. T. Dustov',
      'B. llahverdiev' => 'B. P. Allahverdiev',
      'H.' => 'H. Tuna',
      'A. akhshimuratov' => 'A. B. Yakhshimuratov',
      'B. abajanov' => 'B. A. Babajanov',
      'A. od' => 'A. Rathod',
      'B. m' => 'B. Halim',
      'A. uci' => 'A. Senouci',
      'È. madiev' => 'È. Muhamadiev',
      'M. rov' => 'M. Nazarov',
      'V. avchin' => 'V. M. Savchin',
      'P. rinh' => 'P. T. Trinh',
      'I. hamdamov' => 'I. M. Khamdamov',
      'Kwon Ho' => 'Kwok-Pun Ho',
      'K. rasad' => 'K. R. Prasad',
      'M. mita' => 'M. Rashmita',
      'N. dhar' => 'N. Sreedhar',
      'D. kbaev' => 'D. Serikbaev',
      'R. liev' => 'R. A. Aliev',
      'A. hmadova' => 'A. N. Ahmadova',
      'Y. d' => 'Y. Ahmed',
      'W. udek' => 'W. A. Dudekb',
      'B. aktar' => 'B. Bayraktar',
      'M.  Özdemir' => 'M. Emin Özdemir',
      'H. wan' => 'H. Gunawan',
      'D. akim' => 'D. I. Hakim',
      'A. utri' => 'A. S. Putri',
      'A. momov' => 'A. A. Imomov',
      'A. Meyliev' => 'A. Kh. Meyliev',
      'V. dler' => 'V. E. Adler',
      'V. erdjikov' => 'V. S. Gerdjikov',
      'I. abibullin' => 'I. T. Habibullin',
      'A. hakimova' => 'A. R. Khakimova',
      'A. mirnov' => 'A. O. Smirnov',
      'Decevi' => 'Decio Levi',
      'MigA. Rodríguez' => 'Miguel A. Rodríguez',
      'S. Startsev' => 'S. Ya. Startsev',
      'N. tukhina' => 'N. Zheltukhina',
      'G. ayberganov' => 'G. Khudayberganov',
      'J. Abdullayev' => 'J. Sh. Abdullayev',
    ];

    if (array_key_exists($name, $a)) {
      return $a[$name];
    }
    return $name;
  }


  public function index()
  {
    $pubs = Publikation::get();

//    dd($authors->toarray());
////    foreach ($pubs as $pub) {
////      $arr = array();
////      foreach ($pub->authors as $author) {
////        //$this->sname($author['title']) . ' / ';
////        $arr = [
////          "pub_id" => $pub->id,
////          "title" => $author['title'],
////          "href" => $author['href']
////        ];
//////        dd($arr);
////      Author::create($arr);
////      }
////      //$pub->update($arr);
////    }
//
//    dd();
////    dd($arr);


    $site = "http://matem.zkamen.com/files/";
    echo "<table border='1'>";
    ?>
    <tr>
      <td>Оригинал</td>
      <td>Название</td>
      <td>Аннотация</td>
      <td>Ключевые<br>слова</td>
      <td>MSC</td>
      <td>Авторы</td>
      <td>Организации</td>
      <td>УДК</td>
      <td>Полный текст</td>
      <td>Список<br>литературы</td>
      <td>Дата<br>публикации</td>
    </tr>

    <?php

    foreach ($pubs as $pub) {
      echo "<tr><td><a href=\"http://mi.mathnet.ru/ufa$pub->count\">$pub->count</a></td>";
      echo "<td>$pub->name</td>";
      echo "<td>$pub->annotation</td>";
      echo "<td>$pub->keywords</td>";
      echo "<td>$pub->MSC</td><td>";

      foreach ($pub->authors as $author) {
        echo $author['title'] . "<br>";
      }
      echo "</td><td>";
      foreach ($pub->organisation as $organisation) {
        echo  $organisation['title'] . "<br>";
      }
      echo "</td><td>";
      foreach ($pub->UDC as $udc) {
        echo  $udc . "<br>";
      }
      echo "</td>";

      echo "</td><td>";
      foreach ($pub->FTfiles as $FTfiles) {
        echo '<a href="' . $site . $FTfiles . '">' . $FTfiles . "</a><br>";
      }
      echo "</td>";

      echo "</td><td>";
      if (is_array($pub->RBfiles)) {
        foreach ($pub->RBfiles as $RBfiles) {
          echo '<a href="' . $site . $RBfiles . '">' . $RBfiles . "</a><br>";
        }
      }
      echo "</td>";
      echo "<td>$pub->date</td>";

      echo "</tr>";
    }
    echo "</table>";

  }

  public function stepOne()
  {
    for ($i = 531; $i <= 585; $i++) {

      $arr = array();

      $client = new HtmlWeb;
      $html = $client->load('http://mi.mathnet.ru/ufa' . $i);
      $content = $html->find('table', 3)->find('tr', 0)->find('td', 49);

      if (!is_null($content)) {
        $arr['count'] = $i;
        $arr['name'] = strip_tags($content->find('span.red', 1)->innertext);
        if ($content->find('a[href^="/php/person.phtml]')) {
          foreach ($content->find('a[href^="/php/person.phtml]') as $a) {
            $arr['authors'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        }

        if ($content->find('a[href^="/php/organisation.phtml]')) {
          foreach ($content->find('a[href^="/php/organisation.phtml]') as $a) {
            $arr['organisation'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        }

        if ($content->find('a[href^="/php/getFT.phtml]')) {
          foreach ($content->find('a[href^="/php/getFT.phtml]') as $a) {
            $arr['fullText'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        }

        if ($content->find('a[href^="/php/getRefFromDB.phtml]')) {
          foreach ($content->find('a[href^="/php/getRefFromDB.phtml]') as $a) {
            $arr['refBoocks'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        }

        if ($content->find('a[href^="/php/getFT.phtml]')) {
          foreach ($content->find('a[href^="/php/getFT.phtml]') as $a) {
            $arr['langs'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        }

        if ($content->find('span.showUDC')) {
          foreach ($content->find('span.showUDC') as $a) {
            $arr['UDC'][] = $a->innertext;
          }
        }

        $pattern = "|Аннотация:(.+?)Ключевые|is";
        preg_match($pattern, $content->innertext, $out);
        $arr['annotation'] = isset($out[1]) ? strip_tags($out[1]) : '';

        $pattern = "|слова:(.+?)Полный|is";
        preg_match($pattern, $content->innertext, $out);
        $arr['keywords'] = isset($out[1]) ? strip_tags($out[1]) : '';

        $pattern = "|MSC:(.+?)Поступила|is";
        preg_match($pattern, $content->innertext, $out);
        $arr['MSC'] = isset($out[1]) ? strip_tags($out[1]) : '';


        $pattern = "|редакцию:(.+?)Цитирование|is";
        preg_match($pattern, $content->innertext, $data);
        preg_match("/[0-9]{2}.[0-9]{2}.[0-9]{4}/", $data[1], $out);
        $arr['date'] = isset($out[0]) ? $out[0] : '';

        $arr['FTfiles'] = '';
        $arr['RBfiles'] = '';

        Publikation::create($arr);
      }
    }
  }
}
