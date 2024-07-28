jQuery(document).ready(function($) {
    $('#site-visit-counter').fadeIn(1000, function() {
        var $counter = $('#site-visit-count');
        var countTo = $counter.text();
        $({ countNum: 0 }).animate({ countNum: countTo }, {
            duration: 1000,
            easing: 'swing',
            step: function() {
                $counter.text(Math.floor(this.countNum));
            },
            complete: function() {
                $counter.text(this.countNum);
            }
        });
    });
});
