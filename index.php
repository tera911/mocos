<?php
  session_start();
  $allow_users = array(
    "tera911",
    "oniiiipooooon"
  );
  if(isset($_SESSION['access_token'])){
    $screen_name = $_SESSION['access_token']['screen_name'];
    if(!checkAllowUser($screen_name, $allow_users)){
      header("Location: /mocos/oauth/clearsessions.php");
    }
  }
  $checks = array(
    "◎","○", "△", "☓", "〒"
  );
  $texts = array(
  "リクエスト無視",
  "オリーブオイルﾄﾞﾊﾞｧ",
  "野菜(ｻﾞｸｻﾞｸｯ",
  "謎の食材",
  "塩胡椒ﾌｧｻｰ",
  "打点",
  "三種のチーズ",
  "僕の好きなハーブ",
  "レモン汁ｷﾞｭｯ",
  "荒い盛り付け",
  "追いオリーブオイル",
  "器くるくる",
  "熱い自画自賛",
  "寒いギャグ",
  "スタジオ半笑い"
  );


  function view(){
    global $texts;
    foreach($texts as $i => $text){
      echo '<div class="row">';
        echo '<div class="col-xs-6">';
          echo '<input class="form-control" type="text" name="text'. $i .'" value="'. $text .'">';
        echo '</div>';
        echo '<div class="col-xs-6">';
          echo '<div class="radio">';
            viewChecks($i);
          echo '</div>';
        echo '</div>';
      echo '</div>';
    }
  }

  function viewChecks($number){
    global $checks;
    foreach($checks as $i => $check){
      echo '<div class="checkbox-inline">';
      echo '<label><input type="radio" name="check'.$number.'" value="'.$check.'">'. $check .'</label>';
      echo '</div>';
    }
  }
  function checkAllowUser($user, $allowUsers){
    foreach($allowUsers as $u){
      if($user == $u){
        return true;
      }
    }
    return false;
  }
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(function(){
        $('[name^="check"]').bind('change click',function(){
          generateTweet();
        });
        $('#loading-example-btn').click(function () {
          var btn = $(this);
          btn.button('loading');
           $.ajax({url: './oauth/tweet.php', type: 'POST', data: {tweet: $('[name="tweet"]').text()}}).done(function(){
             $('input[name^="check"]').each(function(i,e){
               if($(e).val() == "〒"){
                $(e).prop("checked",true);
               }
             });
           }).always(function () {
            btn.button('reset')
          });
        });
        $('[name="finish"]').bind('keyup change keydown keypress',function(){
          generateTweet();
        });
        function generateTweet(){
          var text = "";
          $('[name^="text"]').each(function(i,e){
            var checkbox = $('[name="check'+ (i) +'"]');
            if(checkbox.is(':checked')){
              var check = $('[name="check'+ (i) +'"]:checked');
              if(check.val() == "〒"){
                return;
              }
              text += $(e).val() + "\t"+ check.val() + "\n";
            }
          });
          text += $('[name="finish"]').val() + "\n";
          $('[name="tweet"]').text(text);
          countTweet();
        }
        function countTweet(){
          $('#tweetlength').text($('[name="tweet"]').text().length);
        }
      });
    </script>
  </head>
  <body>
    <div class="wrapper">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
              <a href="./oauth/redirect.php">login</a>
              <a href="./oauth/clearsessions.php">logout</a>
          </div>
        </div>
        <?php if(isset($_SESSION['access_token'])): ?>
        <div class="row">
          <div class="col-sm-12">
              <h1>moco's kittin.<span class="badge">(仮)</span></h1>
          </div>
        </div>
        <form>
          <?php view(); ?>
          <div class="row">
            <div class="col-sm-12 text-left">
              <p class="helper-text">感想</p>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <input class="form-control" type="text" name="finish">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <p id="tweetlength">0</p>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <textarea name="tweet" class="form-control" rows="4" maxlength="140"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
              <p>
                <button class="btn btn-primary btn-block" id="loading-example-btn" data-loading-text="Loading..." type="button">ツイート</button>
              </p>
            </div>
          </div>
        <?php endif; ?>
        </form>
      </div><!--/container -->
    </div>
  </body>
</html>
