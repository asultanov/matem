<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publikation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use simplehtmldom\HtmlWeb;


class ParseController extends Controller
{

  public function getExt($mime)
  {
    $arr = [
      'application/pdf' => '.pdf',
      'text/html' => '.html',
      'text/htm' => '.htm',
    ];

    return $arr[$mime];
  }

  public function dw_file($href, $count)
  {
    $url = 'http://www.mathnet.ru' . $href;
    $file_name = $count . '_' . rand(100, 999);
    $file = file_get_contents($url);
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $a = $finfo->buffer($file);
    if ($file) {
      if (file_exists('files/' . $file_name . $this->getExt($a))) {
        return $file_name;
      } else {
        if (file_put_contents('files/' . $file_name . $this->getExt($a), $file)) {
          return $file_name . $this->getExt($a);
        } else {
          return 'none.tmp';
        }
      }
    } else {
      return 'none.tmp';
    }
  }


  public function index()
  {
    $pubs = Publikation::get();

//    $arr['FTfiles']
//    $arr['RBfiles']

    foreach ($pubs as $pub)
      foreach ($pub->fullText as $file) {
        $arr['FTfiles'][] = $this->dw_file($file['href'], $pub->count);
      }

    foreach ($pub->refBoocks as $file) {
      $arr['RBfiles'][] = $this->dw_file($file['href'], $pub->count);
    }
    $pub->update($arr);
  }

  public function stepOne()
  {
    for ($i = 531; $i <= 585; $i++) {

      $arr = array();

      $client = new HtmlWeb;
      $html = $client->load('http://mi.mathnet.ru/ufa585');
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

        Publikation::create($arr);
      }
    }
  }
}
