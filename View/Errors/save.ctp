<article class="structural">
    <h1>DB No likey</h1>
    <?php
    echo $error->getMessage();
    if (Configure::read('debug') > 0) {
        ?>    
    <section>
        <h2>Dumps</h2>
        <article>
            <?php
            echo $this->element('exception_stack_trace');
            ?>  
        </article>
        <article>
            <h3>Class Dump</h3>
            <?php
            echo $error;
            ?>  
        </article>
    </section>
    <?php }
    ?>
</article>