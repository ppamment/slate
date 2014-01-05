<div class="row section_header">
    <div class="span12">
        <hr/>
    </div>
</div>

<div class="row news">
    <div class="span6">
        <h1><?php echo $this->news->getTitle() ?></h1>
        <p class="artwork-title"><?php echo $this->news->getSubtitle() ?></p>
        <p><?php echo $this->news->getBody() ?></p>
    </div>
    <div class="span6">
        <img src="<?php echo $this->news->getImage()->getFullPath() ?>" />
    </div>
</div>

