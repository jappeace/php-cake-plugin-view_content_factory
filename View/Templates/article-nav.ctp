<?php
/*@#article !content!#@
  @#nav[](
        title,
        url 
     )  !struct! #@*/
// some backwards compatibility
$vars = array('article', 'nav');
foreach($vars as $var){
    if(!isset($$var)){
        $$var = $this->fetch($var);
    }
}

?>
<article class="sturctural">
    <?php echo $article; ?>
</article>
<aside class="sturctural">
    <?=$nav?>
</aside>