<?php
/**
 * News
 *
 * @package
 * @subpackage
 * @author
 * @version $Revision$
 * $Id$
 */

class NewsController extends PCController
{
    private $news;
    private $comment;

     /**
      * TOPIC
      */
    private $topic_array = array(
             'h', // top headlines
             'w', // world
             'b', // business
             'n', // nation
             't', // science and technology
             'p', // politics
             'e', // entertainment
             's'  // sports
            );

    /**
     * TOPIC KEY
     */
    private $topic_key = array(
            'h' => 'トップページ',
            'w' => '国際',
            'b' => 'ビジネス',
            'n' => '国内',
            't' => 'テクノロジー',
            'p' => '政治',
            'e' => 'エンターテイメント',
            's' => 'スポーツ'
          );

    /**
     * News Page
     */
    public function actionIndex()
    {
        $this->setPageTitle('最新のニュースをお届けします。匿名のコメントも可能です。');

        // For Each Category topi
        foreach ($this->topic_array as $topic) {
            $this->news = News::model()->findAll('expired=0 and
                                                  deleted=0 and
                                                  new_flag=1 and
                                                  topic=:topic',
                                              array(':topic'=>$topic));

            // Take over template
            $this->stash['news_'.$topic] = $this->news;
        }
    }

    /**
     * News_detail page
     */
    public function actionDetail()
    {
        $this->setPageTitle('最新のニュースをお届けします。匿名のコメントも可能です。');

        // Search News
        $id = Yii::app()->request->getQuery('id');
        $this->news = News::model()->find('id=:id',
                                             array(':id'=>$id));
        $contents = str_replace("&nbsp", "", $this->news['contents']);

        if (Yii::app()->request->isPostRequest) {
            $comment = Yii::app()->request->getParam('text');
            $validate = $this->validateComment($id, $comment);
            if ($validate['result']) {
                $this->addComment($id, $comment);
            } else {
                $this->stash['error'] = $validate['msg'];
            }
        }

        if (Yii::app()->request->getQuery('comid')) {
            $comid = Yii::app()->request->getQuery('comid');
            $alert = Comment::model()->find('id=:id',
                                        array(':id'=>$comid));
            if ($alert->alert == 0) {
                $alert->alert = 1;
                $alert->update();
                $this->stash['alert'] = '違反報告を受け付けました。';
            } else {
                $this->stash['alert'] = '既に違反登録済みです。';
            }
        }

        $this->comment = Comment::model()->findAll('news_id=:news_id',
                                                  array(':news_id'=>$id));

        // Take over template
        $this->stash['news'] = $this->news;
        $this->stash['contents'] = $contents;
        $this->stash['comment'] = $this->comment;
    }

    /**
     * News Category Page
     */
    public function actionCategory()
    {
        $this->setPageTitle('最新のニュースをお届けします。匿名のコメントも可能です。');

        // Search News
        $topic = Yii::app()->request->getQuery('topic');
        $this->news = News::model()->findAll(array(
                  "condition" => "topic = '".$topic."'",
                  "order" => "id DESC",
                  "limit" => 50
                ));

        // Topic Key
        $topic_key = $this->topic_key[$topic];

        // Take over template
        $this->stash['news'] = $this->news;
        $this->stash['topic'] = $topic_key;
    }

    /**
     * Add Comment
     */
    public function addComment($id, $comment)
    {
        $this->comment = new Comment;
        $this->comment->news_topic = $this->news->topic;
        $this->comment->news_subject = $this->news->subject;
        $this->comment->news_id = $id;
        $this->comment->comment = $comment;
        $this->comment->alert = 0;
        $this->comment->value = 1.0;
        $this->comment->save();
    }

    /**
     * Validation check
     */
    public function validateComment($id, $comment)
    {
        if ($comment === '') {
            return array('result' => false, 'msg' => 'コメントが空です');
        }
        if (mb_strlen($comment) > 70) {
            return array('result' => false, 'msg' => 'コメントは70文字以内で入力してください');
        }
        $sets = Comment::model()->findAll('news_id=:news_id',
                                             array(':news_id'=>$id));
        foreach ($sets as $set) {
            if ($set['comment'] === $comment) {
                return array('result' => false, 'msg' => 'コメントが重複しています');
            }
        }
        return array('result' => true, 'msg' => 'コメントが重複しています');
    }
}
