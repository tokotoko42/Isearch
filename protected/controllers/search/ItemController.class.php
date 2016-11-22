<?php
/**
 * API検索
 *
 * @package
 * @subpackage
 * @author
 * @version $Revision$
 * $Id$
 */

class ItemController extends PCController
{
    private $word_history;
    /**
     * API検索
     */
    public function actionIndex()
    {
      $this->setPageTitle('楽天、アマゾンと連携した商品検索');

        // POST Request
        if (Yii::app()->request->isPostRequest) {
           // Get Param
           $keyword = Yii::app()->request->getParam('keyword');
           $genre = Yii::app()->request->getParam('category');

           // Log 
           Yii::log('Keyword = ' . $keyword,'info','keyword');
           Yii::log('Genre = ' . $genre, 'info', 'Genre');

           // Validation Check
           if (!$this->checkValidation($keyword)) {
               $this->stash['errors'] = 'キーワードを入力してください';
               return true;
           }

           // Initialize
           $items = array();
           $itemAmazon = array();

           // BOOK検索
           if ($genre === '200') {
               // Create Instance
               $SearchRakuten = new SearchRakutenBook;
               $SearchAmazon = new SearchAmazonBook;

               // Get Item
               $items = $SearchRakuten->getItems($keyword);
               // Get Item from Amazon
               $itemAmazon = $SearchAmazon->getItems($keyword);

               // オークション
           } else if ($genre === '201') {
               // Create Instance
               $SearchRakuten = new SearchRakutenAuction;

               // Get Item
               $items = $SearchRakuten->getItems($keyword);

           // その他
           } else {
               // Create Instance
               $SearchRakuten = new SearchRakuten;
               $SearchAmazon = new SearchAmazon;

               // Get Item
               $items = $SearchRakuten->getItems($keyword, $genre);
               // Get Item from Amazon
               $itemAmazon = $SearchAmazon->getItems($keyword, $genre);
           }

           // Merge Items
           $merged_items = array();
           if (empty($items) && empty($itemAmazon)) {
               $this->stash['errors'] = '該当商品がありませんでした';
               return true;
           } else if (empty($items)) {
               $merged_items = $itemAmazon;
           } else if (empty($itemAmazon)) {
               $merged_items = $items;
           } else {
               $merged_items = array_merge($items, $itemAmazon);
           }

           // DB Save
           $this->word_history = new WordHistory;
           $this->word_history->keyword = $keyword;
           $this->word_history->api_type = $genre;

           // Calucurate
           $merged_items = $this->meanPrice($merged_items);
           $sorted_items = $this->sortItem($merged_items);

           // DB Save
           $this->word_history->save();

           // Take over Template
           $this->stash['items'] = $sorted_items;
           $this->stash['keyword'] = $keyword;
           $this->stash['category'] = $genre;
        }
    }
    /**
     * 重み付けソート関数
     */
    private function sortItem($merged_items)
    {
        foreach($merged_items as $key => $row){
            $price[$key] = $row["ranking"];
        }
        array_multisort($price,SORT_DESC,$merged_items);
        return $merged_items;
    }

    /**
     * 金額情報の重み付け
     */
    private function meanPrice($merged_items)
    {
        // 平均値
        $mean = 0.0;
        $price_list = array();

        foreach ($merged_items as $key => $row) {
            $mean += $row["itemPrice"];
            $price_list[] = $row["itemPrice"];
        }
        $mean = $mean / count($merged_items);

        $this->word_history->average_price = $mean;
        $this->word_history->max_price = max($price_list);
        $this->word_history->min_price = min($price_list);

        return $merged_items;
    }

    /**
     * Validation Check
     */
    private function checkValidation($param) {
        if (empty($param)) {
            return false;
        }
        return true;
    }
}
