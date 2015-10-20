
$(document).ready(function() {
    $('.already_voted').hide();
});

function vote(id, rating) {
    $target_votes = $("#votes_" + id);
    $target_text_vote = $("#text_vote_" + id);
    $target_text_unvote = $("#text_unvote_" + id);
    $.ajax({
        type: 'POST',
        url: '/votes/ajax_vote_mixtapes',
        data: {id: id ,rating: rating },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest)
            console.log(textStatus)
            console.log(errorThrown)
            $target_votes.html('err');
        },
        success: function (data) {
            if (data.error === true) {} else {
                $target_votes.html(data.votes);
                $target_votes.addClass('vote_color_top_just_voted');
                if (rating > 0 && data.undo == false) {
                    $('#text_vote_up_'+ id).addClass('vote_just_voted');
                    $('#text_vote_down_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'up', + id);

                } else if (rating < 0 && data.undo == false) {
                    $('#text_vote_down_'+ id).addClass('vote_just_voted');
                    $('#text_vote_up_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'down', + id);
                }
                if (rating > 0 && data.undo == true) {
                    $('#text_vote_down_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'up', + id);

                };
                if (rating < 0 && data.undo == true) {
                    $('#text_vote_up_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'down', + id);
                };

                if (data.result) {
                    if (data.rating > 0) {
                        $('#text_vote_up_'+ id).fadeOut(100, function(){
                            $('#already_voted_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('#text_vote_up_'+ id).fadeIn(100);
                            });
                        });
                    };

                    if (data.rating < 0) {
                        $('#text_vote_down_'+ id).fadeOut(100, function(){
                            $('#already_voted_down_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('#text_vote_down_'+ id).fadeIn(100);
                            });
                        });
                    };
                };

            }
        },
        dataType: "json"
    });
}