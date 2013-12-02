<?php
/** @var $artist Object_Artist */
$artist = $this->artist;
/** @var $artwork Object_Artwork */
?>

<div class="row section_header">
    <div class="span4">
        <hr/>
    </div>
    <div class="span4">
        <h2><?php echo $artist->getName() ?></h2>
    </div>
    <div class="span4">
        <hr/>
    </div>
</div>

<ul class="nav nav-pills pull-right">
    <li class="active"><a href="#works" data-toggle="tab">WORKS</a></li>
    <li><a href="#bio" data-toggle="tab">BIOGRAPHY</a></li>
</ul>
<div class="clearfix"></div>
<div class="tab-content">
    <div class="tab-pane active" id="works">
        <ul class="thumbnails artist">
            <?php foreach($artist->getChilds() as $artwork) : ?>
                <li class="span3">
                    <a href="#img<?php echo $artwork->getId() ?>" data-toggle="lightbox">
                        <div class="thumbnail">
                            <div class="img-container">
                                <img src="<?php echo $artwork->getImage()->getThumbnail("grid-thumb") ?>" />
                            </div>

                            <div class="caption">
                                <p class="artwork-title"><?php echo $artwork->getTitle() ?></p>

                                <p><?php echo $artwork->getDescription() ?></p>
                            </div>
                        </div>
                    </a>
                    <div id="img<?php echo $artwork->getId() ?>" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
                        <div class='lightbox-content'>
                            <img src="<?php echo $artwork->getImage()->getFullPath() ?>" />
                            <?php if($artwork->getCaption()) : ?><div class="lightbox-caption"><p><?php echo $artwork->getCaption() ?></p></div><?php endif; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="tab-pane" id="bio">
        <div class="span6">
            <img src="<?php echo $artist->getCover()->getImage() ?>" />
        </div>
        <div class="span5">
            <p><?php echo $artist->getBio() ?></p>
        </div>
    </div>
</div>
