    $(document).ready(function(){

        $(window).on('resize',function(){
            if ($(window).width() < 991) {
                $('.list-review-vote').hide();
            } else {
                $('.list-review-vote').show();
            }
        });


        if ($(window).width() >= 992) {        
            $('.list-review-score').each(function(i, obj) {
                    var id = $(this).attr('id');
                    var sum = $(this).text();
                    
                    if(sum == 1) {
                        $(this).append('<span class="list-review-vote">Vote</span>');
                    } else {
                        $(this).append('<span class="list-review-vote">Votes</span>');
                    }
            });
        };
    });
