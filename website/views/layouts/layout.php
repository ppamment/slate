<!DOCTYPE html>
<html>
<head>
    <?php echo $this->headTitle() ?>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->headMeta() ?>
    <!-- Bootstrap -->
    <link href="/website/static/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/website/static/css/bootstrap/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="/website/static/css/bootstrap-lightbox.min.css"/>
    <link rel="stylesheet" href="/website/static/css/style.css"/>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap-lightbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var lightboxes = $(".lightbox");
            $(document).keydown(function(e){
                if(e.which == 37 || e.which == 39){
                    var open = $(".lightbox.in");
                    if(open.length){
                        var openIndex = lightboxes.index(open);
                        var nextIndex = ((e.which == 37) ? openIndex-1 : openIndex+1);
                        if(nextIndex >= 0 && nextIndex < lightboxes.length){
                            var next = $(lightboxes.get(nextIndex));
                            open.data("lightbox").hide();
                            $("a[href=#"+next.attr("id")+"]").click();
                        }
                    }
                }
            });
        });
    </script>
</head>
<body>
<div class="container">

    <div class="row">
        <div class="span2 offset1">
            <a href="/"><img src="<?php echo ($this->site == 'slate' ? '/website/static/img/Logo_large.png' : '/website/static/img/averardhotel_web.jpg') ?>" width="109" height="67"></a>
        </div>
        <?php echo $this->mainMenu() ?>
    </div>
    <?php echo $this->layout()->content ?>
    <div class="row section_header">
        <div class="span12">
            <hr>
        </div>
    </div>
    <div class="row footer">
        <?php if($this->site != "averard") : ?>
            <div class="span8">
                <p class="artwork-title">© Slate Projects <?php echo date("Y") ?> | +44 (0)7792 302850 | alex@slateprojects.com | open by appointment</p>
            </div>
            <div class="span4">
                <p class="artwork-title align-right">
                    <a href="https://twitter.com/slateprojects"><img src="/website/static/img/twitter.png" /> Twitter</a> | <a href="https://www.instagram.com/theaverardhotel/"><img src="/website/static/img/instagram.PNG" /> Instagram</a>
                </p>
            </div>
        <?php else : ?>
            <div class="span6">
                <p class="artwork-title">
                    Alex Meurice | +44 (0)7792 302850 | alex@theaverardhotel.com | <a href="https://www.instagram.com/theaverardhotel/"><img src="/website/static/img/instagram.PNG" /> Instagram</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>