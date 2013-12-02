<div class="row section_header">
    <div class="span12">
        <hr/>
    </div>
</div>
<div class="row">
    <div class="span8">
        <?php echo $this->wysiwyg("content") ?>
    </div>
    <div class="span4 newsletter">
        <div class="box">
            <h3><?php echo $this->input("subscribe") ?></h3>
            <form method="post">
                <div class="control-group">
                    <label class="control-label">First Name:</label>
                    <div class="controls">
                        <input class="input-xlarge" type="text" name="newsletter[first_name]" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Last Name:</label>
                    <div class="controls">
                        <input class="input-xlarge" type="text" name="newsletter[last_name]" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Email:</label>
                    <div class="controls">
                        <input class="input-xlarge"   type="text" name="newsletter[email]" />
                    </div>
                </div>
                <div class="control-group">
                    <input type="submit" class="btn" />
                </div>
            </form>
        </div>
    </div>
</div>
