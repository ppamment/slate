<div class="row section_header">
    <div class="span4">
        <hr/>
    </div>
    <div class="span4">
        <h2>Artists</h2>
    </div>
    <div class="span4">
        <hr/>
    </div>
</div>
<div class="artists">
    <ul class="thumbnails">
        <?php
        /** @var $artist Object_Artist */
        foreach($this->artists as $artist) : ?>
            <li class="span3">
                <div class="thumbnail">
                    <a class="" href="/<?php echo $this->document->getKey() ?>/<?php echo $artist->getKey() ?>">
                        <div class="img-container">
                            <img src="<?php echo $artist->getCover()->getImage()->getThumbnail("grid-thumb") ?>" />
                        </div>
                        <div class="caption">
                            <h3><?php echo $artist->getName() ?></h3>
                        </div>
                    </a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>