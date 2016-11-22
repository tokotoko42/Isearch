<div id="contents">

<div id="main">

<section>
<h2>オークション検索</h2>
<h3 class="mb15">検索フォーム</h3>
  {{if $errors}}
  <p><font color=red>※キーワードを入力してください。</font>
  {{/if}}
<form action="" method="post" name="retrieve" id="retrieve" class="exitAlert" data-ajax="false">
<p><input type="text" name="keyword" size=50 value="{{if $keyword}}{{$keyword}}{{/if}}"></p>
<p><input type="submit" size=5 value="検索する"></p>
</form>

{{if $items}}
<section class="list2">
<h3 class="mb15">オークション検索結果</h3>

{{foreach from=$items key=key item=value}}
<section>
<a href="{{$value[affiliateUrl]}}" target="_blank">
<h4>{{if preg_match("/Kindle/", $value[itemPrice])}}{{$value[itemPrice]}}版{{else}}価格：{{$value[itemPrice]}}円{{/if}}</h4>
<figure><img src="{{$value[imageUrl]}}" width="320" height="150" alt=""></figure>
<p><font color=green>{{if preg_match("/RAKU/", $key)}}提供：楽天{{else if preg_match("/AMAZ/", $key)}}提供：アマゾン{{/if}}</font></p>
<p>{{$value[itemName]}}</p>
<p>取引開始　：　{{$value[startTime]}}</p>
<p>取引終了　：　{{$value[endTime]}}</p>
</a>
</section>
{{/foreach}}

</section><!--/list2-->
{{/if}}

</section>

</div>
<!--/main-->
