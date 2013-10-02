<?php
/*@#article !content!#@
  @#nav[](
        title,
        url 
     )  !struct! #@*/
?>
<article class="sturctural">
    <?php echo $article; ?>
</article>
<aside class="sturctural">
    <nav>
    <?php foreach($nav as $link){
	?><a href="<?=$link['url']?>"><?=$link['title']?></a><?php
    }?>
    </nav>
</aside>