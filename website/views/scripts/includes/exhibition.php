<div class="row">
    <div class="span12 mb10">
        <h1><?php echo $this->exhibition->getName() ?> <span class="date"><?php echo $this->exhibition->getStart()->toString("d MMM Y") ?> - <?php echo $this->exhibition->getEnd()->toString("d MMM Y") ?></span></h1>
    </div>
    <div class="span6">
        <ul class="thumbnails">
            <?php foreach($this->exhibition->getArtworks() as $artwork) : ?>
                <li class="span3">
                    <a href="#img<?php echo $artwork->getId() ?>" data-toggle="lightbox">
                        <div class="thumbnail">
                            <div class="img-container">
                                <img src="<?php echo $artwork->getImage()->getThumbnail("grid-thumb") ?>" />
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
    <div class="span6">
        <?php echo $this->exhibition->getDescription() ?>
    </div>
</div>
