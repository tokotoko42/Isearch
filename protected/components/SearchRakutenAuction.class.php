<?php
class SearchRakutenAuction
{
    /**
     * 商品ジャンル定義
     */
    public static $itemGenre = array(
        999 => 999999,
        100 => 101240,
        101 => 101240,
        102 => 100804,
        103 => 101164,
        104 => 101164,
        105 => 101164,
        106 => 100533,
        107 => 215783,
        108 => 101070,
        109 => 100026,
        110 => 100316,
        111 => 100938,
        112 => 211742,
        113 => 100227,
        114 => 100939,
        115 => 100371,
        200 => 216129,
        201 => 200162,
        202 => 101297
    );

    /**
     * 楽天商品検索APIのURLを取得する
     * @param    string $query 商品名またはキーワード
     * @return    string URL
     */
    function getSearchURL($query, $genre_id) {
        //アプリID
        $applicationId = "";

        //アフィリエイトID
        $affiliateId = '';

        // URL Encode
        $query = urlencode($query);
        $sort  = urlencode('standard');
        $genre_id = 999999;
        // Make URL
        // All Genre
        if ($genre_id === 999999) {
            $res = "https://app.rakuten.co.jp/services/api/AuctionItem/Search/20130110?applicationId={$applicationId}&affiliateId={$affiliateId}&format=xml&keyword={$query}&sort={$sort}";
        } else {
            $res = "https://app.rakuten.co.jp/services/api/AuctionItem/Search/20130110?applicationId={$applicationId}&affiliateId={$affiliateId}&format=xml&keyword={$query}&auctionGenreId=${genre_id}&sort={$sort}";
        }

        return $res;
    }

    /**
     * 楽天商品検索APIから商品情報を取り出す（XML形式）
     * @param    string $url リスクエストURL
     * @return   string 書籍情報／FALSE=失敗
     */
    function getItemInfo($url) {
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

    /**
     * 楽天商品検索APIから必要な情報を配列に格納する
     * @param   string $query 商品名またはキーワード
     * @param   array  $items 情報を格納する配列
     * @return  ヒットした件数／FALSE：検索に失敗
    */
    function getItems($query, $genre=999) {
        $items = array();
        $genre_id = self::$itemGenre[$genre];

        $url = $this->getSearchURL($query, $genre_id);

        // Log
        Yii::log("URL = " . $url,"info","URL");

        $res = $this->getItemInfo($url);
        if ($res === FALSE){
            return FALSE;
        } else {
            $xml = simplexml_load_string($res);
            //レスポンス・チェック
            $count = (int)$xml->count;
            if ($count <= 0) {
                return FALSE;
            }
            $obj = $xml->Items->Item;

            if (empty($obj)) {
                return FALSE;
            }
            $cnt = 1;
            foreach ($obj as $node) {
                $items['RAKU'.$cnt]['itemName'] = (string)$node->itemName;
                $items['RAKU'.$cnt]['itemPrice'] = (string)$node->itemPrice;
                $items['RAKU'.$cnt]['affiliateUrl'] = (string)$node->affiliateUrl;
                $items['RAKU'.$cnt]['ranking'] = 1.0;

                $items['RAKU'.$cnt]['imageUrl'] = (string)$node->mediumImageUrl;
                $items['RAKU'.$cnt]['startTime'] = (string)$node->startTime;
                $items['RAKU'.$cnt]['endTime'] = (string)$node->endTime;
                $cnt++;
            }
        }
        return $items;
    }
}
