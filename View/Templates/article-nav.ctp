<?php
/*@#article !content!#@
  @#nav[](
        title,
        url 
     )  !struct! #@*/
?>
<article class="structural">
    <?php echo $article; ?>
</article>
<aside class="structural">
    <nav>
    <?php foreach($nav as $link){
	?><a href="<?=$link['url']?>"><?=$link['title']?></a><?php
    }?>
    </nav>
</aside>