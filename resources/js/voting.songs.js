
$(document).ready(function() {
    $('.already_voted').hide();
});

function vote(id, rating) {
    $target_votes = $("#votes_" + id);
    $target_text_vote = $(".text_vote_" + id);
    $target_text_unvote = $(".text_unvote_" + id);
    $.ajax({
        type: 'POST',
        url: '/votes/ajax_vote',
        data: {id: id ,rating: rating},
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $target_votes.html('err');
        },
        success: function (data) {
            if (data.error === true) {} else {

                $target_votes.html(data.votes);

                var url = $(location).attr('href');
                url = url.split('/');
                var page = url[3];

                if (page != 'song') {
                    if ($(window).width() >= 992) {
                        if (data.votes === 1) {
                            $target_votes.html(data.votes + ' Vote');
                        } else {
                            $target_votes.html(data.votes + ' Votes');
                        };
                    };
                };

                if (data.votes === undefined) {
                    $target_votes.html('Voted!');
                };

                $target_votes.addClass('vote_color_top_just_voted');
                if (rating > 0 && data.undo == false) {

                    $('.text_vote_up_'+ id).addClass('vote_just_voted');
                    $('.text_vote_down_'+ id).removeClass('vote_just_voted');
                    
                    ga('send', 'event', 'vote', 'up', + id);
               
                } else if (rating < 0 && data.undo == false) {

                    $('.text_vote_down_'+ id).addClass('vote_just_voted');
                    $('.text_vote_up_'+ id).removeClass('vote_just_voted');
    
                    ga('send', 'event', 'vote', 'down', + id);
    
                }
                if (rating > 0 && data.undo == true) {
                    $('.text_vote_down_'+ id).removeClass('vote_just_voted');
                    
                    ga('send', 'event', 'vote', 'up', + id);

                };
                if (rating < 0 && data.undo == true) {
                    $('.text_vote_up_'+ id).removeClass('vote_just_voted');
    
                    ga('send', 'event', 'vote', 'down', + id);
    
                };

                if (data.result) {
                    if (data.rating > 0) {
                        $('.text_vote_up_'+ id).fadeOut(100, function(){
                            $('#already_voted_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('.text_vote_up_'+ id).fadeIn(100);
                            });
                        });
                    };

                    if (data.rating < 0) {
                        $('.text_vote_down_'+ id).fadeOut(100, function(){
                            $('#already_voted_down_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('.text_vote_down_'+ id).fadeIn(100);
                            });
                        });
                    };
                };

            }
        },
        dataType: "json"
    });
}