<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/website/static/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/website/static/css/bootstrap/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="/website/static/css/bootstrap-lightbox.min.css"/>
    <link rel="stylesheet" href="/website/static/css/style.css"/>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/website/static/js/bootstrap-lightbox.min.js"></script>
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
</div>
</body>
</html>