<div class="row section_header">
    <div class="span12">
        <hr/>
    </div>
</div>

<ul class="thumbnails news">
    <?php foreach($this->news as $news) : ?>
        <li class="span6">
            <a href="/<?php echo $this->document->getKey() ?>/<?php echo $news->getKey() ?>">
                <div class="thumbnail">
                    <div class="img-container span2">
                        <img src="<?php echo $news->getImage()->getThumbnail("grid-thumb") ?>" />
                    </div>

                    <div class="caption span3">
                        <p><strong><?php echo $news->getTitle() ?></strong></p>
                        <p class="artwork-title"><?php echo $news->getSubtitle() ?></p>
                        <p><small><?php echo substr(strip_tags($news->getBody()),0,200) ?></small></p>
                        <p><small>MORE</small></p>
                    </div>
                </div>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

