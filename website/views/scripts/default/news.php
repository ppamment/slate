<div class="row section_header">
    <div class="span12">
        <hr/>
    </div>
</div>

<ul class="thumbnails news">
    <?php foreach($this->news as $column) : ?>
        <li class="span6">
            <?php foreach($column as $news) : ?>
                <a href="/<?php echo $this->document->getKey() ?>/<?php echo $news->getKey() ?>" class="clearfix">
                    <div class="thumbnail">
                        <div class="img-container span2">
                            <img src="<?php echo $news->getImage()->getThumbnail("grid-thumb") ?>" />
                        </div>

                        <div class="caption span3">
                            <p><strong><?php echo $news->getTitle() ?></strong></p>
                            <p class="artwork-title"><?php echo $news->getSubtitle() ?></p>
                            <p><small><?php echo strtok(wordwrap(strip_tags($news->getBody()), 200, "...\n"), "\n") ?></small></p>
                            <p><small>MORE</small></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </li>
    <?php endforeach; ?>
</ul>

