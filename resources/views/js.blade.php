<script>
    $('.newsletter-form').on('submit', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $button = $this.find('button');
        $button.prop('disabled', true);
        $button.find('i').show();
        $.post('api/newsletter/members', {
            email: $this.find('input').val()
        }).then(function () {
            $this.find('.alert-success').show();
            $this.find('.newsletter-form-inputs').hide();
        }, function () {
            $this.find('.alert-danger').show();
            $this.find('.newsletter-form-inputs').hide();
        });
    });
</script>