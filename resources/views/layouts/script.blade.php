<script>
    function loaderBtn(condition, id) {
        if (condition === true) {
            $(id).addClass('loading disabled');
            $(id).prop('disabled', true);
            $(id).data("temp-name", $(id).text());
            $(id).html('processing...');
        } else {
            $(id).html($(id).data("temp-name"));
            $(id).removeClass('loading disabled');
            $(id).prop('disabled', false);
        }
    }

    function loader(condition) {
        if (condition === true) {
            $(".app-loader").show();
        } else {
            $(".app-loader").hide();
        }
    }

</script>
