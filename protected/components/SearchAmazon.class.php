<?php
class SearchAmazon
{

    /**
     * 商品ジャンル定義
     */
    public static $itemGenre = array(
        999 => 'ALL',
        100 => 'Music',
        101 => 'DVD',
        102 => 'Kitchen',
        103 => 'Toys',
        104 => 'Hobbies',
        105 => 'VideoGames',
        106 => 'Baby',
        107 => 'Kitchen',
        108 => 'SportingGoods',
        109 => 'Electronics',
        110 => 'Grocery',
        111 => 'HealthPersonalCare',
        112 => 'Electronics',
        113 => 'Grocery',
        114 => 'Beauty',
        115 => 'Apparel',
        116 => 'Jewelry',
        117 => 'Watches'
    );

    /**
     * Amazon商品検索APIのURLを取得する
     * @param    string $query 商品名またはキーワード
     * @return    string URL
     */
    function getSearchURL($query, $genre_id) {
        // Access Key
        $accessKey = "";

        // Tracking ID
        $trackingId = '';

        // Secret Key
        $secret = "";

        // Make parameter
        $params = array();
        $params['Service']        = 'AWSECommerceService';
        $params['AWSAccessKeyId'] = $accessKey;
        $params['Version']        = '2011-09-01';
        $params['Operation']      = 'ItemSearch';
        $params['SearchIndex']    = $genre_id;
        $params['AssociateTag']   = $trackingId;
        $params['Keywords']       = $query;
        $params['ResponseGroup']  = 'Medium,Reviews';
        // Kindleを除外する
        //$params['Power'] = 'binding:not kindle';

        // Make timestamp and sort
        $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        ksort($params);

        // Base URL
        $baseurl = 'http://ecs.amazonaws.jp/onca/xml';

        // canonical string を作成します
        $canonical_string = '';
        foreach ($params as $k => $v) {
            $canonical_string .= '&'.$this->urlencode_rfc3986($k).'='.$this->urlencode_rfc3986($v);
        }
        $canonical_string = substr($canonical_string, 1);

        // 署名を作成します
        // - 規定の文字列フォーマットを作成
        // - HMAC-SHA256 を計算
        // - BASE64 エンコード
        $parsed_url = parse_url($baseurl);
        $string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
        $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret, true));

        // Make URL
        $url = $baseurl.'?'.$canonical_string.'&Signature='.$this->urlencode_rfc3986($signature);

        return $url;
    }

    // RFC3986 形式で URL エンコードする関数
    function urlencode_rfc3986($str) {
        return str_replace('%7E', '~', rawurlencode($str));
    }

    /**
     * Amazon商品検索APIから商品情報を取り出す（XML形式）
     * @param    string $url リスクエストURL
     * @return   string 書籍情報／FALSE=失敗
     */
    function getItemInfo($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * 商品検索APIから必要な情報を配列に格納する
     * @param   string $query 商品名またはキーワード
     * @param   array  $items 情報を格納する配列
     * @return  ヒットした件数／FALSE：検索に失敗
    */
    function getItems($query, $genre) {
        $items = array();
        $genre_id = self::$itemGenre[$genre];
        $url = $this->getSearchURL($query, $genre_id);

        // Log
        Yii::log("Amazon URL = " . $url,"info","Amazon URL");
        Yii::log("Amazon Genre = " . $genre_id,"info","Amazon Genre");
        $res = $this->getItemInfo($url);

        if ($res === FALSE){
            return FALSE;
        } else {
            // XML Extraction
            $xml = simplexml_load_string($res);
            $obj = $xml->Items->Item;
            $cnt = 0;

            if (empty($obj)) {
                return false;
            }
            foreach ($obj as $node) {
              // 価格が取得できない場合、スキップ
                if (!empty($node->OfferSummary->LowestNewPrice->Amount)) {
                    $items['AMAZ'.$cnt]['itemPrice'] = (string)$node->OfferSummary->LowestNewPrice->Amount;
                } else if (!empty($node->ItemAttributes->ListPrice->Amount)) {
                    $items['AMAZ'.$cnt]['itemPrice'] = (string)$node->ItemAttributes->ListPrice->Amount;
                } else {
                  continue;
                }

                $items['AMAZ'.$cnt]['itemName'] = (string)$node->ItemAttributes->Title;
                $items['AMAZ'.$cnt]['affiliateUrl'] = (string)$node->DetailPageURL;
                $items['AMAZ'.$cnt]['ranking'] = 10.0 / ($cnt + 1.0);

                // 画像が存在しない場合
                if (empty($node->MediumImage->URL)) {
                    $items['AMAZ'.$cnt]['imageUrl'] = '/images/no_image.jpg';
                } else {
                    $items['AMAZ'.$cnt]['imageUrl'] = (string)$node->MediumImage->URL;
                }
                $cnt++;
            }
        }
        return $items;
    }
}
