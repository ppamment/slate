<ul class="thumbnails">
    <?php
    /** @var $artist Object_Artist */
    foreach($this->exhibitions as $exhibition) : ?>
        <li class="span3">
            <div class="thumbnail">
                <a class="" href="?view=<?php echo $exhibition->getKey() ?>">
                    <div class="img-container">
                        <img src="<?php $first = current($exhibition->getArtworks()); echo $first->getImage()->getThumbnail("grid-thumb") ?>" />
                    </div>
                    <div class="caption">
                        <p><?php echo $exhibition->getName() ?></p>
                        <p class="artwork-title"><?php echo $exhibition->getStart()->toString("d MMM Y")." - ".$exhibition->getEnd()->toString("d MMM Y") ?></p>
                    </div>
                </a>
            </div>
        </li>
    <?php endforeach; ?>
</ul>