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

    <script src="http://code.jquery.com/jquery.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap-lightbox.min.js"></script>
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
            <a href="/"><img src="/website/static/img/Logo_large.png" width="163" height="100"></a>
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
        <div class="span12">
            <p class="artwork-title"><?php echo $this->input("footer_text") ?></p>
        </div>
    </div>
</div>
</body>
</html>