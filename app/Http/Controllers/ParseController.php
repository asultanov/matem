<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publikation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use simplehtmldom\HtmlWeb;


class ParseController extends Controller
{

  public function index()
  {
    $pubs = Publikation::get();

//    foreach ($pubs as $pub) {
//      $arr = array();
//      foreach ($pub->authors as $author) {
//        $arr['authors'][] = [
//          "title" => mb_substr($author['title'], 5, 1) ? mb_substr($author['title'], 0, 3) . mb_substr($author['title'], 7, strlen($author['title'])) : $author['title'],
//          "href" => $author['href']
//        ];
//      }
//      $pub->update($arr);
//    }

//    dd($arr);


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
    </tr>

    <?php

    foreach ($pubs as $pub) {
      echo "<tr><td><a href=\"http://mi.mathnet.ru/ufa$pub->count\">$pub->count</a></td>";
      echo "<td>$pub->name</td>";
      echo "<td>$pub->annotation</td>";
      echo "<td>$pub->keywords</td>";
      echo "<td>$pub->MSC</td><td>";

      foreach ($pub->authors as $author) {
        echo "<div>" . $author['title'] . "</div>";
      }
      echo "</td><td>";
      foreach ($pub->organisation as $organisation) {
        echo "<div>" . $organisation['title'] . "</div>";
      }
      echo "</td><td>";
      foreach ($pub->UDC as $udc) {
        echo "<div>" . $udc . "</div>";
      }
      echo "</td>";

      echo "</td><td>";
      foreach ($pub->FTfiles as $FTfiles) {
        echo '<div> <a href="' . $site . $FTfiles . '">' . $FTfiles . "</a></div>";
      }
      echo "</td>";

      echo "</td><td>";
      if (is_array($pub->RBfiles)) {
        foreach ($pub->RBfiles as $RBfiles) {
          echo '<div> <a href="' . $site . $RBfiles . '">' . $RBfiles . "</a></div>";
        }
      }
      echo "</td>";


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
