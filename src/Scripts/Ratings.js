(function () {
    $(document).ready(function () {
        function paintStars($container, value) {
            $container.find('.star').each(function () {
                var starValue = parseInt($(this).data('value'), 10);
                $(this).text(starValue <= value ? '★' : '☆');
            });
        }

        $(document).on('mouseenter', '.rating_stars .star', function () {
            paintStars($(this).closest('.rating_stars'), parseInt($(this).data('value'), 10));
        });

        $(document).on('mouseleave', '.rating_stars', function () {
            paintStars($(this), parseInt($(this).data('selected'), 10) || 0);
        });

        $(document).on('click', '.rating_stars .star', function () {
            var $star = $(this);
            var $container = $star.closest('.rating_stars');
            var articleId = $container.data('article_id');
            var value = parseInt($star.data('value'), 10);

            $.post('index.php', {
                action: 'add_rating',
                article_id: articleId,
                rating: value
            }, function (response) {
                if (!response.success) {
                    alert(response.message);
                    return;
                }

                $container.data('selected', value);
                paintStars($container, value);

                var $avg = $container.closest('.infopanel-rating').find('.avg_rating');
                var average = Math.round(response.average * 100) / 100;
                $avg.text('Average: ' + average + '/5 (' + response.count + ')');
            }, 'json');
        });
    });
})();
