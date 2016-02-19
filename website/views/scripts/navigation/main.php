<div class="span8 offset1 navigation">
    <h1>
        <?php foreach($this->pages as $page) : ?>
                <a class="<?php if($page->getId() == $this->active) echo "active"; ?>" href="<?php echo $page->getFullPath() ?>"><?php echo $page->getTitle() ?></a>
        <?php endforeach; ?>
    </h1>
</div>