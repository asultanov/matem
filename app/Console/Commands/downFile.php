<?php

namespace App\Console\Commands;

use App\Models\Publikation;
use Illuminate\Console\Command;

class downFile extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:dwfiles';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Download files';

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
      if (file_put_contents(public_path('files/') . $file_name . $this->getExt($a), $file)) {
        return $file_name . $this->getExt($a);
      } else {
        return 'none.tmp';
      }
    } else {
      return 'none.tmp';
    }
  }


  public function handle()
  {
    $pubs = Publikation::get();

//    $arr['FTfiles']
//    $arr['RBfiles']

    foreach ($pubs as $pub) {
      foreach ($pub->fullText as $file) {
        $arr['FTfiles'][] = $this->dw_file($file['href'], $pub->count);
      }

      foreach ($pub->refBoocks as $file) {
        $arr['RBfiles'][] = $this->dw_file($file['href'], $pub->count);
      }
      $pub->update($arr);

      echo $pub->count;
      
    }
  }
}
