<?php
require_once '../vendor/autoload.php';
session_start();
?>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php clsMain::include_online_jquery(); ?>
        <link href="fbalbum.css" type="text/css" rel="stylesheet"/>
    </head>

    <body>
        <?php
        clsMain::getGACode();
        ?>

        <div id="fb-root"></div>
        <script src="//connect.facebook.net/en_US/sdk.js"></script>
        <script>
            FB.init({
                appId: "<?php echo AppConfig::appid; ?>",
                xfbml: true,
                status: true, // check login status
                cookie: true, // enable cookies to allow the server to access the session
                version: "v2.0"
            });
            FB.Canvas.setAutoGrow(100);
        </script>
        <div class="album">
            <div class="inner_album">
                <div class="choose_photo">Choose From Your Albums</div>
                <div class="recent_photo">
                    <div class="upload">Photo Albums</div>
                    <div class="view_upload"><a href="getPhotosFromFB.php">Photos of You</a></div>
                </div>
                <div class="album_images">

                    <div class="all_images">
                        <ul id="fbimages"></ul>
                    </div>
                </div>
                <div class="view_upload"><a href="javascript:void(0);" onclick="parent.$.fancybox.close();">Cancel</a></div>
            </div>
        </div>
        <script>

            nexturl = '';
            page = 1;
            done = false;
            function myphotos() {

                page++;
                if (nexturl === '') {
                    FB.api('/me/albums?fields=cover_photo,name,count&limit=27&access_token=<?php echo $_SESSION['User']['access_token']; ?>', function(info) {
                        dataAppend(info);
                    });
                } else {
                    $.getJSON(nexturl, function(info) {
                        dataAppend(info);
                    });
                }
            }
            function dataAppend(info) {
                var lenght = info.data.length;
                if (lenght !== 0) {
                    nexturl = info.paging.next;
                    for (var i = 0; i < lenght; i++) {
                        $("#fbimages").append("<li><a href='getAlbumsPhotos.php?name=" + encodeURIComponent(info.data[i].name) + "&album_id=" + info.data[i].id + "'><img src='https://graph.facebook.com/" + info.data[i].id + "/picture?access_token=<?php echo $_SESSION['User']['access_token']; ?>' width='158' height='125'/></a><span>" + info.data[i].name + "</span></br><span>" + info.data[i].count + " photos</span></li>");
                    }
                } else {
                    done = true;
                }
            }
            myphotos();

        </script>
        <script src="jshelperGetPhotosFromFB.js"></script>
    </body>
</html>