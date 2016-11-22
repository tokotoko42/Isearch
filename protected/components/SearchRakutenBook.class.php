<?php
class SearchRakutenBook
{
    /**
     * 楽天Book検索APIのURLを取得する
     * @param    string $query 商品名またはキーワード
     * @return    string URL
     */
    function getSearchURL($query) {
        //アプリID
        $applicationId = "";

        //アフィリエイトID
        $affiliateId = '';

        // URL Encode
        $query = urlencode($query);
        $sort  = urlencode('standard');

        // Make URL
        $res = "https://app.rakuten.co.jp/services/api/BooksTotal/Search/20130522?applicationId={$applicationId}&affiliateId={$affiliateId}&format=xml&keyword={$query}&sort={$sort}";

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
    function getItems($query) {
        $items = array();
        $url = $this->getSearchURL($query);
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
              return false;
            }
            $cnt = 1;
            foreach ($obj as $node) {
                $items['RAKU'.$cnt]['itemName'] = (string)$node->title;
                $items['RAKU'.$cnt]['itemPrice'] = (string)$node->itemPrice;
                $items['RAKU'.$cnt]['affiliateUrl'] = (string)$node->affiliateUrl;
                $items['RAKU'.$cnt]['ranking'] = 1.0;
                $items['RAKU'.$cnt]['imageUrl'] = (string)$node->largeImageUrl;
                $cnt++;
            }
        }
        return $items;
    }
}
