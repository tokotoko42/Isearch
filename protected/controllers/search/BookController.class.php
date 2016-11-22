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

class BookController extends PCController
{
    private $word_history;
    /**
     * API検索
     */
    public function actionIndex()
    {
        $this->setPageTitle('楽天、アマゾンから最安値を検索します');

        // POST Request
        if (Yii::app()->request->isPostRequest) {
           // Get Param
           $keyword = Yii::app()->request->getParam('keyword');

           // Log
           Yii::log('Keyword = ' . $keyword,'info','keyword');

           // Validation Check
           if (!$this->checkValidation($keyword)) {
               $this->stash['errors'] = 'キーワードを入力してください';
               return true;
           }

           // Create Instance
           $SearchRakuten = new SearchRakutenBook;
           $SearchAmazon = new SearchAmazonBook;

           // Get Item
           $items = $SearchRakuten->getItems($keyword);
           // Get Item from Amazon
           $itemAmazon = $SearchAmazon->getItems($keyword);

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

           // DB Save<
           $this->word_history = new WordHistory;
           $this->word_history->keyword = $keyword;
           $this->word_history->api_type = 'Book';

           // Calucurate
           $merged_items = $this->meanPrice($merged_items);
           $sorted_items = $this->sortItem($merged_items);

           // DB Save<
           $this->word_history->save();

           // Take over Template
           $this->stash['items'] = $sorted_items;
           $this->stash['keyword'] = $keyword;
        }
    }
    /**
     * 重み付けソート関数
     */
    private function sortItem($merged_items)
    {
        foreach($merged_items as $key => $row){
            $price[$key] = $row["itemPrice"];
        }
        array_multisort($price,SORT_ASC,$merged_items);
        return $merged_items;
    }

    /**
     * 金額情報の重み付け
     */
    private function meanPrice($merged_items)
    {
        // 平均値
        $mean = 0.0;
        foreach ($merged_items as $key => $row) {
          $mean += $row["itemPrice"];
          $price_list[] = $row["itemPrice"];
        }
        $mean = $mean / count($merged_items);

        $this->word_history->average_price = $mean;
        $this->word_history->max_price = max($price_list);
        $this->word_history->min_price = min($price_list);

        // 重み付け
        foreach ($merged_items as $key => $row) {
            $variance = abs($row["itemPrice"] - $mean);
            $merged_items[$key]["ranking"] = $row["ranking"] + (1000 / $variance);
        }

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
