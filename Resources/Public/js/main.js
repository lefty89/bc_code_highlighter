jQuery(document).ready(function() {

    jQuery('.bugcluster-code-highlighter pre code').each(function(i, block) {
        //hljs.highlightBlock(block);
    });

    jQuery('.bugcluster-code-highlighter ul a').click(function(event){
        event.preventDefault();

        $(this).parent('li').siblings().removeClass('active');
        $(this).parent('li').addClass('active');

        var tab = $(this).attr("href");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();

    })

});