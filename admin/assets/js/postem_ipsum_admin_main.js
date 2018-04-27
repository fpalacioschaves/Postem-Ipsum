(function ($) {
    "use strict";

    var delay = function (ms) {
        return new Promise(function (r) {
            setTimeout(r, ms)
        })
    };
    var time = 2000;
    var cat_random = 0;
    var bg_random = 0;
    var price_min = document.getElementById('price_slider-padding-value-min'),
        price_max = document.getElementById('price_slider-padding-value-max');

    $(document).ready(function () {

        // Color picker
        $(function () {
            $('.color-field').wpColorPicker();
        });

        // Creating sliders
        var price_slider = document.getElementById('price_slider');

        if ($("#price_slider").length > 0) {
            noUiSlider.create(price_slider, {
                start: [200, 800],
                connect: true,
                step: 10,
                tooltips: [true, true],
                range: {
                    'min': 0,
                    'max': 1000
                }
            });
        }

        $(".select_taxonomy").hide();
        $(".select_term").hide();

        // Price sliders
        if ($("#price_slider").length > 0) {
            price_slider.noUiSlider.on('update', function (values, handle) {
                if (handle) {
                    price_max = values[handle];
                } else {
                    price_min = values[handle];
                }
            });
        }
    });

    // Elegimos categoria aletatoria?
    $(document).on("change", "#cat_random", function () {

        if ($('input[name="cat_random"]').is(':checked')) {
            $("#cat").prop("disabled", true);
            cat_random = 1;
        } else {
            $("#cat").prop("disabled", false);

            cat_random = 0;
        }
    });

    $(document).on("click", ".table_header", function (e) {
        $(this).nextAll('.table_container').first().toggleClass("closed");
        $(this).find(".header_icon").toggleClass("dashicons-arrow-up-alt2 dashicons-arrow-down-alt2")
    });

    // Elegimos color aletatorio?
    $(document).on("change", "#bg_random", function () {
        if ($('input[name="bg_random"]').is(':checked')) {
            $('.wp-picker-container').hide();
            bg_random = 1;
        } else {
            $('.wp-picker-container').show();
            bg_random = 0;
        }
    });

    ///////////////////////////////////////////////// POSTS ///////////////////
    $(document).on("change", "#postem_ipsum_post_type", function (e) {
        var post_type = $("#postem_ipsum_post_type").val();

        // AJAX CALL
        $.post(ajaxurl,
            {
                action: "postem_ipsum_get_taxonomies",
                post_type: post_type,
            })

            .done(function (result) {
                if (result == 0) {
                    alert("Something goes wrong")
                } else {
                    $(".select_taxonomy").show().html(result);
                    $(".select_term").html("");
                }
            });
    });

    $(document).on("change", "#postem_ipsum_taxonomy", function (e) {
        var taxonomy = $("#postem_ipsum_taxonomy").val();
        // AJAX CALL
        $.post(ajaxurl,
            {
                action: "postem_ipsum_get_terms",
                taxonomy: taxonomy,
            })
            .done(function (result) {
                if (result == 0) {
                    alert("Something goes wrong")
                } else {
                    $(".select_term").show().html(result);
                }
            });
    });

    $(document).on("click", ".postem-ipsum-generate", function (e) {
        e.preventDefault();
        var $inputs = $('#postem-ipsum-generation:input');
        var bg_color = $("#postem_ipsum_featured_image_bg").val();
        var variables = {};
        $.each($('#postem-ipsum-generation').serializeArray(), function (i, field) {
            variables[field.name] = field.value;
        });
        // AJAX CALL
        if (
            $("#postem_ipsum_post_type").val() != "0" &&
            $("#postem_ipsum_taxonomy").val() != "0" &&
            $("#postem_ipsum_term").val() != "0" &&
            $("#postem_ipsum_post_number").val() != "" &&
            $("#postem_ipsum_paragraphs").val() != "") {

            $('body').loadingModal({
                position: 'auto',
                text: 'We are creating content for you...',
                color: '#fff',
                opacity: '0.7',
                backgroundColor: 'rgb(0,0,0)',
                animation: 'doubleBounce'
            });

            $.post(ajaxurl,
                {
                    action: "postem_ipsum_generate_posts",
                    variables: variables,
                    bg_color: bg_color,
                    bg_random: bg_random,
                })
                .done(function (result) {

                    $(".result").html(result);
                    $(".select_taxonomy").html("");
                    $(".select_term").html("");
                    $('#postem-ipsum-generation').trigger("reset");

                    $(".postem_ipsum_image_color").hide();
                    $(".postem_ipsum_image_w").hide();
                    $(".postem_ipsum_image_h").hide();
                    $(".postem_ipsum_image_table").css('min-height', '0px');

                    // hide the loading modal
                    delay(time)
                        .then(function () {
                            $('body').loadingModal('color', '#fff').loadingModal('text', 'Done :-)').loadingModal('backgroundColor', 'rgb(0,0,0)');
                            return delay(time);
                        })
                        .then(function () {
                            $('body').loadingModal('hide');
                            return delay(time);
                        })
                        .then(function () {
                            $('body').loadingModal('destroy');
                        });
                });
        }
        else {
            alert("Fill the form");
        }
    });

    $(document).on("click", ".postem-ipsum-delete", function (e) {
        e.preventDefault();
        $('body').loadingModal({
            position: 'auto',
            text: 'We are deleting content for you...',
            color: '#fff',
            opacity: '0.7',
            backgroundColor: 'rgb(0,0,0)',
            animation: 'doubleBounce'
        });

        $.post(ajaxurl,
            {
                action: "postem_ipsum_remove_posts",
            })
            .done(function (result) {

                $(".select_taxonomy").html("");
                $(".select_term").html("");
                // hide the loading modal
                delay(time)
                    .then(function () {
                        $('body').loadingModal('color', '#fff').loadingModal('text', 'Done :-)').loadingModal('backgroundColor', 'rgb(0,0,0)');
                        return delay(time);
                    })
                    .then(function () {
                        $('body').loadingModal('hide');
                        return delay(time);
                    })
                    .then(function () {
                        $('body').loadingModal('destroy');
                    });
            });
    });

    $(document).on("change", "#postem_ipsum_featured_image", function () {
        var selection = $("#postem_ipsum_featured_image").val();
        if (selection == "yes") {
            $(".postem_ipsum_image_color").show();
            $(".postem_ipsum_image_w").show();
            $(".postem_ipsum_image_h").show();
            $(".postem_ipsum_image_table").css('min-height', '400px');
        }
        else {
            $(".postem_ipsum_image_color").hide();
            $(".postem_ipsum_image_w").hide();
            $(".postem_ipsum_image_h").hide();
            $(".postem_ipsum_image_table").css('min-height', '0px');
        }
    });

    ///////////////////////////////////////////////// WOO ///////////////////
    $(document).on("click", ".postem-ipsum-generate-products", function (e) {
        e.preventDefault();
        var $inputs = $('#postem-ipsum-product-generation:input');
        var bg_color = $('#postem_ipsum_product_image_bg').val();
        var variables = {};
        $.each($('#postem-ipsum-product-generation').serializeArray(), function (i, field) {
            variables[field.name] = field.value;
        });
        // AJAX CALL
        if (
            $("#cat").val() != "" &&
            $("#postem_ipsum_woo_products_number").val() != "" &&
            $("#postem_ipsum_woo_product_paragraphs").val() != "") {

            $('body').loadingModal({
                position: 'auto',
                text: 'We are creating products for you...',
                color: '#fff',
                opacity: '0.7',
                backgroundColor: 'rgb(0,0,0)',
                animation: 'doubleBounce'
            });

            $.post(ajaxurl,
                {
                    action: "postem_ipsum_generate_products",
                    variables: variables,
                    price_min: price_min,
                    price_max: price_max,
                    cat_random: cat_random,
                    bg_random: bg_random,
                    bg_color: bg_color
                })
                .done(function (result) {
                    $(".result").html(result);
                    $(".image_color").hide();
                    $(".image_w").hide();
                    $(".image_h").hide();
                    $('.wp-picker-container').show();
                    price_slider.noUiSlider.set([200, 800]);
                    $("#cat").prop("disabled", false);
                    $('#postem-ipsum-product-generation').trigger("reset");
                    // hide the loading modal
                    delay(time)
                        .then(function () {
                            $('body').loadingModal('color', '#fff').loadingModal('text', 'Done :-)').loadingModal('backgroundColor', 'rgb(0,0,0)');
                            return delay(time);
                        })
                        .then(function () {
                            $('body').loadingModal('hide');
                            return delay(time);
                        })
                        .then(function () {
                            $('body').loadingModal('destroy');
                        });
                });
        }
        else {
            alert("Fill the form");
        }
    });

    $(document).on("click", ".postem-ipsum-delete-products", function (e) {
        e.preventDefault();
        $('body').loadingModal({
            position: 'auto',
            text: 'We are deleting products for you...',
            color: '#fff',
            opacity: '0.7',
            backgroundColor: 'rgb(0,0,0)',
            animation: 'doubleBounce'
        });
        $.post(ajaxurl,
            {
                action: "postem_ipsum_remove_products",
            })
            .done(function (result) {
                $('#postem-ipsum-product-generation').trigger("reset");
                // hide the loading modal
                delay(time)
                    .then(function () {
                        $('body').loadingModal('color', '#fff').loadingModal('text', 'Done :-)').loadingModal('backgroundColor', 'rgb(0,0,0)');
                        return delay(time);
                    })
                    .then(function () {
                        $('body').loadingModal('hide');
                        return delay(time);
                    })
                    .then(function () {
                        $('body').loadingModal('destroy');
                    });
            });
    });

    $(document).on("change", "#postem_ipsum_woo_product_image", function () {
        var selection = $("#postem_ipsum_woo_product_image").val();
        if (selection == "yes") {
            $(".postem_ipsum_image_color").show();
            $(".postem_ipsum_image_w").show();
            $(".postem_ipsum_image_h").show();
            $(".postem_ipsum_image_table").css('min-height', '400px');
        }
        else {
            $(".postem_ipsum_image_color").hide();
            $(".postem_ipsum_image_w").hide();
            $(".postem_ipsum_image_h").hide();
            $(".postem_ipsum_image_table").css('min-height', '0px');
        }
    });

}(jQuery));