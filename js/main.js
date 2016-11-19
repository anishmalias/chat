

    $(function(){
     
        
        function chatBoxHeight(){
            var winHeight = $(window).height();
            var boxTop = $(".chtBx").offset().top;
            var boxBottom = $(".creat-msg").height();
            var chtBxHt = winHeight - boxTop - boxBottom -80;
            $("#chatbox").height(chtBxHt);
        }
        
        chatBoxHeight();
        
        $(window).resize(function(){
           chatBoxHeight(); 
        });
        
       
        
        
    });
