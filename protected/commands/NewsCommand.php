<?php
class NewsCommand extends BatchBase
{
    private $news_db;

    /**
     * TOPIC
     */
    private $topic_array = array(
          'h', // トップニュース (top headlines)
          'w', // 国際 (world)
          'b', // ビジネス (business)
          'n', // 国内 (nation)
          't', // テクノロジー (science and technology)
          'p', // 政治 (politics)
          'e', // エンタメ (entertainment)
          's' // スポーツ (sports)
        );

    public function run($args)
    {
        // Initialize
        $this->initializeDB();

        // Top News
        foreach ($this->topic_array as $topic) {
            $url = $this->getSearchURL($topic);
            $news_set = $this->getNews($url);

            // 文字化けするかもしれないのでUTF-8に変換
            $json = mb_convert_encoding($news_set, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

            // オブジェクト毎にパース
            $obj = json_decode($json);
            $obj_news = $obj->responseData->results;

            foreach ($obj_news as $news) {
                // DB Save
                $this->news_db = new News;
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
        }
    }

    /*
     * DB Update
     */
    function initializeDB() {
        $update = Yii::app()->db->createCommand();
        $update->update('news', array(
              'new_flag'=>0,
            ));
    }

    /**
     * 楽天商品検索APIのURLを取得する
     * @param    string $query 商品名またはキーワード
     * @return    string URL
     */
    function getSearchURL($topic) {
        // Make URL
        $res = "http://ajax.googleapis.com/ajax/services/search/news?v=1.0&ned=jp&topic=" . $topic;
        return $res;
    }

    /**
     * 楽天商品検索APIから商品情報を取り出す（XML形式）
     * @param    string $url リスクエストURL
     * @return   string 書籍情報／FALSE=失敗
     */
    function getNews($url) {
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
