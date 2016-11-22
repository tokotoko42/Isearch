<script type="text/javascript">
  $(window).on('load', function () {
    $('.selectpicker').selectpicker({
      'selectedText': 'cat'
    });
  });
</script>

<div id="contents">

<div id="main">

<section>
<h2>商品検索</h2>
<h3 class="mb15">検索フォーム</h3>
  {{if $errors}}
  <p><font color=red>※キーワードを入力してください。</font>
  {{/if}}
<form action="" method="post" name="retrieve" id="retrieve" class="exitAlert" data-ajax="false">
<p>商品カテゴリ　
<select name="category" class="selectpicker" data-style="btn-info">
<option value="999" {{if $category == "999"}}selected{{/if}}>すべて</option>
<option value="200" {{if $category == "200"}}selected{{/if}}>書籍</option>
<option value="201" {{if $category == "201"}}selected{{/if}}>オークション</option>
<option value="100" {{if $category == "100"}}selected{{/if}}>CD・楽器</option>
<option value="101" {{if $category == "101"}}selected{{/if}}>DVD</option>
<option value="102" {{if $category == "102"}}selected{{/if}}>インテリア・寝具 </option>
<option value="103" {{if $category == "103"}}selected{{/if}}>おもちゃ</option>
<option value="104" {{if $category == "104"}}selected{{/if}}>ホビー</option>
<option value="105" {{if $category == "105"}}selected{{/if}}>ゲーム</option>
<option value="106" {{if $category == "106"}}selected{{/if}}>キッズ・ベビー・マタニティ</option>
<option value="107" {{if $category == "107"}}selected{{/if}}>キッチン・日用品雑貨・文具</option>
<option value="108" {{if $category == "108"}}selected{{/if}}>スポーツ・アウトドア </option>
<option value="109" {{if $category == "109"}}selected{{/if}}>パソコン・周辺機器</option>
<option value="110" {{if $category == "110"}}selected{{/if}}>ドリンク・お酒 </option>
<option value="111" {{if $category == "111"}}selected{{/if}}>ダイエット・健康・介護</option>
<option value="112" {{if $category == "112"}}selected{{/if}}>家電・AV・カメラ</option>
<option value="113" {{if $category == "113"}}selected{{/if}}>食品・スイーツ</option>
<option value="114" {{if $category == "114"}}selected{{/if}}>美容・コスメ・香水 </option>
<option value="115" {{if $category == "115"}}selected{{/if}}>ファッション・アパレル・靴</option>
<option value="116" {{if $category == "116"}}selected{{/if}}>ジュエリー</option>
<option value="117" {{if $category == "117"}}selected{{/if}}>腕時計</option>
</select>
</p>
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input type="text" name="keyword" value="{{if $keyword}}{{$keyword}}{{/if}}" class="form-control" placeholder="商品を入力してください...">
      <span class="input-group-btn">
        <button type="submit" class="btn btn-default">　検索　</button>
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->

</form>
<br>
{{if $items}}
<section class="list2">
<h3 class="mb15">商品検索結果</h3>

{{foreach from=$items key=key item=value}}
<section>
<a href="{{$value[affiliateUrl]}}" target="_blank">
<h4>価格：{{$value[itemPrice]}}円</h4>
<figure><img src="{{$value[imageUrl]}}" alt=""></figure>
<p><font color=green>{{if preg_match("/RAKU/", $key)}}提供：楽天{{else if preg_match("/AMAZ/", $key)}}提供：アマゾン{{/if}}</font></p>
<p>{{$value[itemName]}}</p>
{{if $value[startTime]}}
<p>取引開始　：　{{$value[startTime]}}</p>
<p>取引終了　：　{{$value[endTime]}}</p>
{{/if}}
</a>
</section>
{{/foreach}}

</section><!--/list2-->
{{/if}}

</section>

</div>
<!--/main-->
