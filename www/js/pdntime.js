/*
 * Vonaq Cable Guardian
 * Server time changer add-on
 * 18.08.2014
 * 
 * Armagan Corlu aka Psy_chip
 * psychip.net
 * elance.com/s/psychip
 * 
 * contact:
 * root@psychip.net
 */

var pdntime = {
    spath: "includes/changetime.php",
    container: "#servertime",
    pdndate: "#pdn_dateval",
    pdntime: "#pdn_timeval",
    handler: null,
    launch: function() {
        $('#servertime').html('Please Wait..');
        window.clearInterval(pdntime.handler);
        $.ajax({
            type: "GET",
            url: this.spath,
            success: function(data) {
                $('#servertime').html(data);
            }
        });
    },
    done: function() {
        $('#servertime').html('Getting system time..');
        $.ajax({
            type: "GET",
            url: 'includes/servertime.php',
            success: function(data) {
                $('#servertime').html(data);
                pdntime.handler = window.setInterval(function() {
                    $('#servertime').load('includes/servertime.php');
                }, 1000);
            }
        });
    },
    handlekey: function(e) {
        var key;
        if (window.event)
            key = window.event.keyCode;
        else
            key = e.which;
        if (key === 13) {
            this.submit();
            return false;
        } else {
            return true;
        }
    },
    submit: function() {
        var vpdndate = $(this.pdndate).val();
        var vpdntime = $(this.pdntime).val();
        var allok = true;

        if (allok === true) {
            $(this.container).html("Processing..");
            $.ajax({
                type: "POST",
                url: this.spath + '?set=1',
                data: {'pdn_dateval': vpdndate.toString(), 'pdn_timeval': vpdntime.toString()},
                success: function(data) {
                    pdntime.process(data);
                }
            });
        }
    },
    process: function(data) {
        if (data === "") {
            $(this.container).html("Connection Error");
            return;
        }
        try {
            var res = $.parseJSON(data);
        } catch (err) {
            $(this.container).html("Server Side Error");
            return;
        }
        switch (res['r']) {
            case "ok":
                this.done();
                break;
            default:
                $('#servertime').load('includes/servertime.php');
                break;
        }
    }
};
