// FLICKR Search
function photo_search ( param ) {
    // APIリクエストパラメタ
    param.api_key  = '6c6c656cb09698c10bf8b7fae95c2c0c';
    param.method   = 'flickr.photos.search';
    param.per_page = 30;
    param.sort     = 'relevance';
    param.format   = 'json';
    param.jsoncallback = 'jsonFlickrApi';

    // APIリクエストURL(GETメソッド)
    var url = 'http://www.flickr.com/services/rest/?'+
	obj2query( param );

    // script
    var script  = document.createElement( 'script' );
    script.type = 'text/javascript';
    script.src  = url;
    document.body.appendChild( script );
};

// クリアする
function remove_children ( id ) {
    var div = document.getElementById( id );
    while ( div.firstChild ) { 
        div.removeChild( div.lastChild );
    }
};

// オブジェクト
function obj2query ( obj ) {
    var list = [];
    for( var key in obj ) {
        var k = encodeURIComponent(key);
        var v = encodeURIComponent(obj[key]);
        list[list.length] = k+'='+v;
    }
    var query = list.join( '&' );
    return query;
}

// Flickr API Call Back
function jsonFlickrApi ( data ) {
    // データがCALLできているかチェック
    if ( ! data ) return;
    if ( ! data.photos ) return;
    var list = data.photos.photo;
    if ( ! list ) return;
    if ( ! list.length ) return;

    // (Loading...)をクリアする
    remove_children( 'photos_here' );

    // 
    var div = document.getElementById( 'photos_here' );
    for( var i=0; i<list.length; i++ ) {
        var photo = list[i];

        //
        var atag = document.createElement( 'a' );
        atag.href = 'http://www.flickr.com/photos/'+
	    photo.owner+'/'+photo.id+'/';

        //
        var img = document.createElement( 'img' );
        img.width = 100;
        img.height = 100;
        img.src = 'http://static.flickr.com/'+photo.server+
	    '/'+photo.id+'_'+photo.secret+'_s.jpg';
        img.style.border = '0';
        atag.appendChild( img );
        div.appendChild( atag );
    }
}
