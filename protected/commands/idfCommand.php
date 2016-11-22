<?php
class idfCommand extends BatchBase
{
    private $news;
    private $keyword_array;
    private $count;

    public function run($args)
    {
        echo "Start\n";
        // Initialize function
        $this->initialize();

        // DF Calculate
        $this->caluculateDF();

        // IDF Calculate
        echo $this->count;

        echo "END\n";
    }

    public function initialize()
    {
        $this->news = News::model()->findAll(array());
        $this->keyword_array = array();
        $this->count = 0;
    }

    public function caluculateDF()
    {
        foreach ($this->news as $news) {
            // Total issues
            $this->count++;

            // 
            $mecab = new MeCab_Tagger();
        }
    }
}

//$mecab = new MeCab_Tagger();
//$ret = $mecab->parseToString($str);
