<?php
class wordCommand extends BatchBase
{
    private $word_favorit;

    /**
     * 禁止ワード
     */
    private $prohibit = array(
          'あすつく',
          'わけあり',
          's'
        );

    public function run($args)
    {
        // 
        $url = $this->getURL();
        $keyword_set = $this->getKeyword($url);

        // 文字化けするかもしれないのでUTF-8に変換
        $json = mb_convert_encoding($keyword_set, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        // オブジェクト毎にパース
        $obj = json_decode($json);
        $tt = 0;
        $obj_word = $obj->ResultSet->$tt->Result;
        $totalResultsReturned = $obj->ResultSet->totalResultsReturned;

        $cnt = 0;
        for ($cnt = 0; $cnt < $totalResultsReturned; $cnt++) {
            $word = $obj_word->$cnt->Query;
            echo $word;
        }
        /*
            // Validation Check
            $result = $this->validationWord($obj_word->$cnt->Query);
            if ($result) {
               continue;
            }
            
            /*
            // DB Save
            $this->word_favorit = new News;
            $this->news_db->subject = $news->title;
                $this->news_db->contents = $news->content;
                $this->news_db->topic = $topic;
                $this->news_db->url = $news->unescapedUrl;
                $this->news_db->new_flag = 1;
                $this->news_db->deleted = 0;
                $this->news_db->expired = 0;
                $this->news_db->value = 1.0;

                if (!empty($news->image->url)) {
                    $this->news_db->image = $news->image->url;
                }

                $cnt = 1;
                if (!empty($news->relatedStories)) {
                    foreach ($news->relatedStories as $related) {
                        $related_subject = 'related_subject' . $cnt;
                        $related_url = 'related_url' . $cnt;
                        $this->news_db->$related_subject = $related->title;
                        $this->news_db->$related_url = $related->unescapedUrl;
                        $cnt++;

                        if ($cnt === 5) {
                            break;
                        }
                    }
                }

                // DB Save
                $this->news_db->save();
            }
        }*/
    }

    /**
     * YAHOOショッピングの人気キーワード用のURLを取得する
     * @return    string URL
     */
    function getURL() {
        // Make URL
        $res = "http://shopping.yahooapis.jp/ShoppingWebService/V1/json/queryRanking?appid=dj0zaiZpPWl0QUVYQkVManoybSZzPWNvbnN1bWVyc2VjcmV0Jng9ZjA-&type=ranking&hits=20";
        return $res;
    }

    /**
     * APIからキーワード情報を取り出す（JSON形式）
     * @param    string $url リスクエストURL
     * @return   string JSON
     */
    function getKeyword($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
