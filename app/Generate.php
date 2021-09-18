<?php

namespace sc\app;


class Generate {

    use MyFunc;

    private $text;
    private $newText;
    private $image;
    private $odds;
    public $imgHtml = '<img src="/img/{name}" alt="">';

    public function __construct($file, $imageName = false, $odds = 25)
    {
        if (!is_file($file))
            die('псс, это точно файл с текстом?');
        if ($imageName !==false && (!is_array($imageName) || empty($imageName)))
            die('ну ты чего, надо массив с наименованием картинок и усе');
        if ($odds > 100)
            die('хмм, мне б число и до ста, это ж процент так то');


        $this->text = file_get_contents($file);
        $this->image = $imageName;
        $this->odds = (integer)$odds;
    }

    public function getArticle() {

        $this->generate();
        return $this->newText;
    }

    //сохрание файла, по желанию директория и наименование
    public function saveFiles($nameFile = false, $putSaveFile = __DIR__ . '/fileGener/') {


        if($nameFile === false)
            $nameFile = $this->uniqName();

        $this->generate(); //генерация статьи
        if(file_put_contents($putSaveFile . $nameFile, $this->newText))
            return $putSaveFile . $nameFile . "\n";
        else
            return false;
    }

    //сама генерация
    private function generate() {

        //генерируем текст
        if($this->text)
            $this->generateText();

        //подкидываем картоинки если есть и нужно
        if($this->image) {
            $this->pasteImage();
        }
    }

    private function generateText() {

        //делаем выюорку из предложенных слов {...}
        $text = $this->text;
        preg_match_all('/{.+?}/', $text, $matches);  //массив из нужных замен

        foreach ($matches[0] as $item) {
            $arrInside = explode('|', str_replace(['{', '}'], '', $item)); //массив слов под замену
            $word = $arrInside[rand(0, count($arrInside) - 1)]; //выбор слова

            $text = $this->str_replace_once($item, $word, $text); //вставка
        }

        $this->newText = $text;
    }



    private function pasteImage() {
        $text = $this->newText;
        $arrImage = $this->image;
        preg_match_all('/' . preg_quote('[image]') .'/', $text, $paste);

        foreach ($paste[0] as $item) {

            $img = "";
            if(rand(0, 100) > $this->odds) {

                if(!empty($arrImage)) {
                    $k = rand(0, count($arrImage) - 1);
                    $img = str_replace('{name}', $arrImage[$k], $this->imgHtml); //присвоили картинку
                    unset($arrImage[$k]);  //удалили
                    $arrImage = array_values($arrImage);  //пересобрали

                }
            }
            $text = $this->str_replace_once($item, $img, $text);
        }
        $this->newText = $text;
    }

    private function uniqName() {
        return md5(uniqid()) . '.txt';
    }





}












