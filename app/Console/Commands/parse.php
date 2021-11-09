<?php

namespace App\Console\Commands;

use App\Models\Publikation;
use Illuminate\Console\Command;
use simplehtmldom\HtmlWeb;

class parse extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:parse';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    for ($i = 2; $i <= 585; $i++) {

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
        } else {
          $arr['authors'] = [];
        }


        if ($content->find('a[href^="/php/organisation.phtml]')) {
          foreach ($content->find('a[href^="/php/organisation.phtml]') as $a) {
            $arr['organisation'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        } else {
          $arr['organisation'] = [];
        }

        if ($content->find('a[href^="/php/getFT.phtml]')) {
          foreach ($content->find('a[href^="/php/getFT.phtml]') as $a) {
            $arr['fullText'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        } else {
          $arr['fullText'] = [];
        }

        if ($content->find('a[href^="/php/getRefFromDB.phtml]')) {
          foreach ($content->find('a[href^="/php/getRefFromDB.phtml]') as $a) {
            $arr['refBoocks'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        } else {
          $arr['refBoocks'] = [];
        }

        if ($content->find('a[href^="/php/getFT.phtml]')) {
          foreach ($content->find('a[href^="/php/getFT.phtml]') as $a) {
            $arr['langs'][] = ['title' => $a->innertext, 'href' => $a->href];
          }
        } else {
          $arr['langs'] = [];
        }

        if ($content->find('span.showUDC')) {
          foreach ($content->find('span.showUDC') as $a) {
            $arr['UDC'][] = $a->innertext;
          }
        } else {
          $arr['UDC'] = [];
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
        preg_match("/[0-9]{2}.[0-9]{2}.[0-9]{4}/", isset($data[1]) ? $data[1] : '31.12.2020', $out);
        $arr['date'] = isset($out[0]) ? $out[0] : '';

        $arr['FTfiles'] = '';
        $arr['RBfiles'] = '';

        Publikation::create($arr);
        echo $i.PHP_EOL;
      }
    }
  }
}
