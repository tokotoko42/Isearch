<div id="contents">

<div id="main">

<section>
<h2>画像検索(FLICKR)</h2>
<h3 class="mb15">検索フォーム</h3>
  {{if $errors}}
  <p><font color=red>※キーワードを入力してください。</font>
  {{/if}}
<form action="" method="post" name="retrieve" id="retrieve" class="exitAlert" data-ajax="false">
<p><input type="text" name="keyword" size=50 value="{{if $keyword}}{{$keyword}}{{/if}}"></p>
<p><input type="submit" size=5 value="検索する"></p>
</form>

{{if $keyword}}
<section class="list2">
<h3 class="mb15">画像検索結果</h3>
<script type="text/javascript" src="/js/flickr-search.js"></script>
<script type="text/javascript"><!--
    window.onload = function () {
        photo_search({ text: '{{$keyword}}' });
    }
--></script>
<div id="photos_here">Loading...</div>
</section><!--/list2-->
{{/if}}

</section>

</div>
<!--/main-->
