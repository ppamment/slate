<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="/pimcore/static/js/pimcore/namespace.js"></script>
    <script type="text/javascript" src="/pimcore/static/js/pimcore/functions.js"></script>
    <script type="text/javascript" src="/pimcore/static/js/pimcore/helpers.js"></script>
    <script type="text/javascript">
        <?php if ($this->tab) { ?>
            pimcore.helpers.rememberOpenTab("<?php echo $this->tab ?>");
        <?php } ?>
        window.location.href = "/admin/";
    </script>
</head>
<body>


</body>
</html>