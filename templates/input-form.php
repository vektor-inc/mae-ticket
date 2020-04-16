
<div id="maetick_input">
    <form method="POST">

        <?php do_action('maet_input_before'); ?>

        <input type="number" name="number-1" id="number-1" />
        <span class="_border">-</span>
        <input type="number" name="number-2" id="number-2" />
        <span class="_border">-</span>
        <input type="number" name="number-3" id="number-3" />
        <span class="_border">-</span>
        <input type="number" name="number-4" id="number-4" />

        <?php do_action('maet_input_after'); ?>

        <input type="submit" />

    </form>
</div>
